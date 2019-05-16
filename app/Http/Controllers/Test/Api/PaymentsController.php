<?php

namespace App\Http\Controllers\Test\Api;

use App\AccessKey;
use App\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Validator;
use App\PaymentMethodRoutes;
use Mail;

class PaymentsController extends BaseController
{

    public function store(Request $request)
    {
        $errors = [];

        $v = Validator::make($request->all(), [
            'merchant_id'           => 'required',
            'access_key'            => 'required',
            'currency'              => 'required|max:3',
            'order_id'              => 'required|max:10',
            'order_amount'          => 'required|numeric|min:1',
            'customer_email'        => 'required|email',
            'customer_phone'        => 'required|digits_between:1,10|numeric',
            'success_url'           => 'required|url',
            'failed_url'            => 'required|url',
            'hash'            => 'required',
        ]);

        if ($v->fails()) {

            foreach ($v->errors()->toArray() as $k => $error) {

                $errors[] = [
                    'field'       => $k,
                    'description' => $error[0],
                ];
            }

            return response()->json([
                'errors' => [
                    'status_code' => 422,
                    'message'     => 'Missing/invalid parameters',
                    'parameters'  => $errors,
                ],
            ], 422);
        }

        $data = $request->merchant_id . $request->access_key . $request->order_id . $request->order_amount . $request->currency . $request->customer_email . $request->customer_phone;

        if ($request->hash != hash('sha512', $data)) {
            return response()->json([
                'errors' => [
                    'status_code' => 422,
                    'message'     => "Missing/invalid parameters",
                    'parameters'  => [
                        'field'   => 'hash',
                        'message' => "The hash field is invalid",
                    ],
                ],
            ], 422);
        }

        /* $access_key = AccessKey::where('merchant_id', $request->merchant_id)
            ->where('type', 'test')->get()
            ->filter(function ($data) use ($request) {
                return $data->access_key == $request->access_key;
            })
            ->first();

        if (!$access_key) {
            return response()->json([
                'errors' => [
                    'message'     => 'Unauthorized',
                    'status_code' => 401,
                ],
            ], 401);
        } */

        //Check if valid merchant_id
        $access_key = AccessKey::where('merchant_id', $request->merchant_id)
                                  ->where('type', 'test')
                                  ->first();

        //Check if merchant_id not valid
        if(!$access_key)
        {
          return response()->json([
              'errors' => [
                  '401' => 'Invalid Merchant ID',
              ],
          ], 401);
        }
        //END Check if merchant_id not valid

        if($access_key)
        {
            //Check if access_key not valid

            if(!$request->access_key == $access_key->access_key)
            {
              return response()->json([
                  'errors' => [
                      '401' => 'Invalid Access Key',
                  ],
              ], 401);
            }
            //END Check if access_key not valid
        }

        //Check if Language is 'EN'
        if($request->language != 'EN')
        {
          return response()->json([
              'errors' => [
                  'status_code' => 422,
                  'message'     => "Missing/invalid parameters",
                  'parameters'  => [
                      'field'   => 'language',
                      'message' => "The language field is invalid. Only 'EN' accepted",
                  ],
              ],
          ], 422);
        }
        //END Check if language is 'EN'

        //Check if Currency is 'INR'
        if($request->currency != 'INR')
        {
          return response()->json([
              'errors' => [
                  'status_code' => 422,
                  'message'     => "Missing/invalid parameters",
                  'parameters'  => [
                      'field'   => 'currency',
                      'message' => "The currenty field is invalid. Only 'INR' accepted",
                  ],
              ],
          ], 422);
        }
        //END Check if Currency is 'INR'

        //Check order_amount max has 2 decimal places
        if (!preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $request->order_amount))
        {
          return response()->json([
              'errors' => [
                  'status_code' => 422,
                  'message'     => "Missing/invalid parameters",
                  'parameters'  => [
                      'field'   => 'Order Amount',
                      'message' => "The merchant order amount field format is invalid. Only max 2 decimal places allowed",
                  ],
              ],
          ], 422);
        }
        //END Check order_amount max has 2 decimal places

        try {

            $transaction = Transaction::create([
                'merchant_id'           => $request->merchant_id,
                'access_key'            => $request->access_key,
                'currency'              => $request->currency,
                'order_id'              => $request->order_id,
                'order_amount'          => $request->order_amount,
                'customer_email'        => $request->customer_email,
                'customer_phone'        => $request->customer_phone,
                'success_url'           => $request->success_url,
                'failed_url'            => $request->failed_url,
                'encryption'            => $request->hash,
                'status'                => 'Pending',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'errors' => [
                    'message'     => $e->getMessage(),
                    'status_code' => 400,
                ],
            ], 400);
        }

        return response()->json([
            'kartpay_id' => $transaction->kartpay_id,
            'url'        => route('test.payments.show', ['kartpay_id' => encrypt($transaction->kartpay_id)]),
        ]);
    }

    public function show($kartpay_id)
    {
        // $v = Validator::make($request->all(), [
        //     'id' => 'required',
        // ]);

        // if ($v->fails()) {
        //     return response()->json([
        //         'errors' => [
        //             'status_code' => 422,
        //             'message'     => 'Missing/invalid parameters',
        //             'parameters'  => [
        //                 [
        //                     'field'       => 'id',
        //                     'description' => 'The id is required',
        //                 ],
        //             ],
        //         ],
        //     ], 422);
        // }

        try {
            $kartpay_id = decrypt($kartpay_id);
        } catch (Exception $e) {
            //SEND EMAIL
            $this->send_email('Sushant', 'sushant@kartpay.com', 'Sushant', '(Test) Manipulated Payment URL', getLiveEnv('MAIL_FROM_ADDRESS'), getLiveEnv('MAIL_FROM_NAME'), '', 'User tried manipulated payment URL');
            //END SEND EMAIL
            abort(404);
        }

        $transaction = Transaction::where('kartpay_id', $kartpay_id)->first();

        if (!$transaction) {
            // return response()->json([
            //     'errors' => [
            //         'status_code' => '404',
            //         'message'     => 'Not found',
            //     ],
            // ], 404);
            return view('test.pages.payments.error', ['error' => 'Payment not found.']);
        } elseif ($transaction->paid_at) {
            return view('test.pages.payments.error', ['error' => 'Payment already paid.']);
        } elseif ($transaction->status != 'Pending') {
            return view('test.pages.payments.error', ['error' => 'Payment invalid.']);
        } elseif (Carbon::now()->gte($transaction->created_at->addMinutes(10))) {
            return view('test.pages.payments.error', ['error' => 'Payment already expired.']);
        }

        return view('test.pages.payments.show', compact('transaction', 'kartpay_id'));
    }

    public function process(Request $request, $kartpay_id)
    {

        try {
            $kartpay_id = decrypt($kartpay_id);
        } catch (Exception $e) {
            //SEND EMAIL
            $this->send_email('Sushant', 'sushant@kartpay.com', 'Sushant', '(Test) Manipulated Payment URL', getLiveEnv('MAIL_FROM_ADDRESS'), getLiveEnv('MAIL_FROM_NAME'), '', 'User tried manipulated payment URL');
            //END SEND EMAIL
            abort(404);
        }

        $routes = [];

        if(isset($request->card_number)){
          $request->card_number = str_replace(" ", "", $request->card_number);
        }

        if(isset($request->payment_method)){

          $bank = "";

          if($request->payment_method == 'net_banking' || $request->payment_method == 'wallet'){
            $bank = $request->card_name;
          }

          $routes = PaymentMethodRoutes::getRoutes($request->payment_method, $bank);

          if(!count($routes)){
            return view('test.pages.payments.error', ['error' => 'No service available for this payment method.']);
          }

        }

        $request['expiry_date'] = $request->expiry_month . '/' . $request->expiry_year;

        $transaction = Transaction::where('kartpay_id', $kartpay_id)->first();

        $v = Validator::make($request->all(), [
            'card_number' => 'required|ccn',
            'card_cvc'    => 'required|cvc',
            'expiry_date' => 'required|ccd',
        ], [
            'card_number.ccn' => 'The :attribute field is invalid',
            'card_cvc.cvc'    => 'The :attribute field is invalid',
            'expiry_date.ccd' => 'The card is already expired',
        ]);

        if ($v->fails()) {
            if ($request->wantsJson()) {

                foreach ($v->errors()->toArray() as $k => $error) {

                    $errors[] = [
                        'field'       => $k,
                        'description' => $error[0],
                    ];
                }

                return response()->json([
                    'errors' => [
                        'status_code' => 422,
                        'message'     => 'Missing/invalid parameters',
                        'parameters'  => $errors,
                    ],
                ], 422);
            } else {

                return back()->withErrors($v->errors());
            }
            return redirect(url($transaction->failed_url, [
                'kartpay_id' => $kartpay_id,
            ]));
        }

        if (!$transaction) {

            return response()->json([
                'errors' => [
                    'status_code' => 404,
                    'message'     => 'Not found',
                ],
            ]);
        } else {

            if (Carbon::now()->gte($transaction->created_at->addMinutes(10))) {

                return view('test.pages.payments.expired', compact('transaction'));
            } elseif ($transaction->paid_at) {
                return view('test.pages.payments.expired', compact('transaction'));
            }

            $transaction->paid_at        = Carbon::now();
            $transaction->status         = 'Paid';
            $transaction->status_message = 'Payment successful';
            $transaction->save();

            return redirect(
                url($transaction->success_url . '?' . http_build_query([
                    'kartpay_id'     => $transaction->kartpay_id,
                    'order_id'       => $transaction->order_id,
                    'order_amount'   => $transaction->order_amount,
                    'status'         => $transaction->status,
                    'status_message' => $transaction->status_message,
                ]))
            );
        }
    }

    public function txnStatus(Request $request)
    {
        if (empty($request->encryption)) {
            return response()->json([
                'errors' => [
                    422 => 'Missing/Invalid Parameters',
                ],
            ], 422);
        }

        $access_key = AccessKey::where('merchant_id', $request->merchant_id)
            ->where('access_key', $request->access_key)
            ->where('type', 'test')->first();

        if (!$access_key) {
            return response()->json([
                'errors' => [
                    '401' => 'Unauthorized',
                ],
            ], 401);
        }

        $transaction = Transaction::where('merchant_id', $request->merchant_id)
            ->where('access_key', $request->access_key)
            ->where('order_id', $request->order_id)
            ->first();
        if (!$transaction) {
            return response()->json([
                'errors' => [
                    423 => 'No Transaction Found',
                ],
            ], 423);
        }

        $data = $request->merchant_id . $request->access_key . $request->order_id;

        if ($request->encryption != hash('sha512', $data)) {
            return response()->json([
                'errors' => [
                    313 => 'Hash key is invalid',
                ],
            ], 422);
        }

        return response()->json([
            'kartpay_id'     => $transaction->kartpay_id,
            'order_id'       => $transaction->order_id,
            'order_amount'   => $transaction->order_amount,
            'status'         => $transaction->status,
            'status_message' => $transaction->status_message,
        ]);

    }

    public function cancel(Request $request, $kartpay_id)
    {
        try {
            $transaction = Transaction::findOrFail(decrypt($kartpay_id));
        } catch (Exception $e) {
            abort(404);
        }

        $transaction->status         = "Canceled";
        $transaction->status_message = "Canceled by the user";
        $transaction->save();

        return redirect(
            url($transaction->failed_url . '?' . http_build_query([
                'kartpay_id'     => $transaction->kartpay_id,
                'order_id'       => $transaction->order_id,
                'order_amount'   => number_format($transaction->order_amount, 2),
                'status'         => $transaction->status,
                'status_message' => $transaction->status_message,
            ]))
        );

    }

    public function fetch(){

        $request = '<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope">
                      <soap:Header />
                      <soap:Body>
                        <kot:PaymentFetch xmlns:kot="http://kotak.com">
                          <kot:xmlDoc>
                            <ns0:InitiateRequestRoot xmlns:ns0="http://www.kotak.com/PaymentTransactionService/InitiateRequest/">
                              <ns0:RequestHeader>
                                <ns0:MessageId>534986450</ns0:MessageId>
                                <ns0:MsgSource>SAMLLWORLD</ns0:MsgSource>
                                <ns0:ClientCode>TEMPTEST1</ns0:ClientCode>
                                <ns0:BatchRefNmbr>534986450</ns0:BatchRefNmbr>
                              </ns0:RequestHeader>
                              <ns0:InstrumentList>
                                <ns0:instrument>
                                  <ns0:InstRefNo>534986450</ns0:InstRefNo>
                                  <ns0:CompanyId />
                                  <ns0:CompBatchId />
                                  <ns0:ConfidentialInd />
                                  <ns0:MyProdCode>CMSPAY</ns0:MyProdCode>
                                  <ns0:CompTransNo />
                                  <ns0:PayMode />
                                  <ns0:TxnAmnt>99.50</ns0:TxnAmnt>
                                  <ns0:AccountNo>09582650000173</ns0:AccountNo>
                                  <ns0:DrRefNmbr>534986450</ns0:DrRefNmbr>
                                  <ns0:DrDesc>534986450</ns0:DrDesc>
                                  <ns0:PaymentDt>2015-12-11</ns0:PaymentDt>
                                  <ns0:BankCdInd />
                                  <ns0:BeneBnkCd />
                                  <ns0:RecBrCd>BOFA0BG3978</ns0:RecBrCd>
                                  <ns0:BeneAcctNo>1234569874</ns0:BeneAcctNo>
                                  <ns0:BeneName>INDIA TEST TEST</ns0:BeneName>
                                  <ns0:BeneCode />
                                  <ns0:BeneEmail />
                                  <ns0:BeneFax />
                                  <ns0:BeneMb />
                                  <ns0:BeneAddr1>IND</ns0:BeneAddr1>
                                  <ns0:BeneAddr2 />
                                  <ns0:BeneAddr3 />
                                  <ns0:BeneAddr4 />
                                  <ns0:BeneAddr5 />
                                  <ns0:city>IND</ns0:city>
                                  <ns0:zip />
                                  <ns0:Country>INDIA</ns0:Country>
                                  <ns0:State />
                                  <ns0:TelephoneNo />
                                  <ns0:BeneId />
                                  <ns0:BeneTaxId />
                                  <ns0:AuthPerson />
                                  <ns0:AuthPersonId />
                                  <ns0:DeliveryMode />
                                  <ns0:PayoutLoc />
                                  <ns0:PickupBr />
                                  <ns0:PaymentRef />
                                  <ns0:ChgBorneBy />
                                  <ns0:InstDt>2015-12-11</ns0:InstDt>
                                  <ns0:MICRNo />
                                  <ns0:CreditRefNo />
                                  <ns0:PaymentDtl />
                                  <ns0:PaymentDtl1>LONDON</ns0:PaymentDtl1>
                                  <ns0:PaymentDtl2>UNITED KINGDOM</ns0:PaymentDtl2>
                                  <ns0:PaymentDtl3 />
                                  <ns0:MailToAddr1 />
                                  <ns0:MailToAddr2 />
                                  <ns0:MailToAddr3 />
                                  <ns0:MailToAddr4 />
                                  <ns0:MailTo />
                                  <ns0:ExchDoc />
                                  <ns0:InstChecksum />
                                  <ns0:InstRF1 />
                                  <ns0:InstRF2 />
                                  <ns0:InstRF3 />
                                  <ns0:InstRF4 />
                                  <ns0:InstRF5 />
                                  <ns0:InstRF6 />
                                  <ns0:InstRF7 />
                                  <ns0:InstRF8 />
                                  <ns0:InstRF9 />
                                  <ns0:InstRF10 />
                                  <ns0:InstRF11 />
                                  <ns0:InstRF12 />
                                  <ns0:InstRF13 />
                                  <ns0:InstRF14 />
                                  <ns0:InstRF15 />
                                  <ns0:EnrichmentSet>
                                    <ns0:Enrichment>TEST CLIENT~SAVING~TEST~09582650000173~FAMILY_MAINTENANCE~1234569874</ns0:Enrichment>
                                  </ns0:EnrichmentSet>
                                </ns0:instrument>
                              </ns0:InstrumentList>
                            </ns0:InitiateRequestRoot>
                          </kot:xmlDoc>
                        </kot:PaymentFetch>
                      </soap:Body>
                    </soap:Envelope>';

        $headers = array('Content-Type: application/soap+xml;charset=UTF-8;action="/BusinessServices/CMS_Service.serviceagent/WebAPI_pay"');

        $endpoint = "https://203.196.200.42/cmspi/UCMSPAY/BusinessServices/StarterProcesses/CMS_Service.serviceagent/WebApI_payEndpoint1";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_FAILONERROR,true);

        $response = curl_exec($ch);

        if($response === FALSE){
            echo "Error : " . curl_error($ch);
        }

        curl_close($ch);

        header("Content-type: text/xml");

        echo $response;
    }

    /**
     * Send Email to Admin
     * @param name (string), recipient_mail (string), recipient_name (string), subject (string), sender_email (string), sender_name (string), url (url - ), body (html format)
     * @return string
     */
   public function send_email($name, $recipient_mail, $recipient_name, $subject, $sender_mail, $sender_name, $url, $body)
   {
       $data = array('name' => $name, 'url' => $url, 'body' => $body);
       Mail::send('auth.verification_mail', $data, function ($message) use (&$recipient_mail, $recipient_name, $subject, $sender_mail, $sender_name) {
           $message->to($recipient_mail, $recipient_name)->subject
               ($subject);
           $message->from($sender_mail, $sender_name);
       });
   }
   /**
    * END Send Email to Admin
    *
    */

}
