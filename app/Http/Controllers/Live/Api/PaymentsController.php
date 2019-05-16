<?php

namespace App\Http\Controllers\Live\Api;

use App\AccessKey;
use App\Merchant;
use App\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Inacho\CreditCard;
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
            'currency'              => 'required|max:3|in:INR',
            'order_id'              => 'required|alpha_num|max:10',
            'order_amount'          => 'required|numeric|min:1',
            'customer_email'        => 'required|email',
            'customer_phone'        => 'required|digits_between:1,10|numeric',
            'success_url'           => 'required|url',
            'failed_url'            => 'required|url',
            'hash'                  => 'required',
            'language'              => 'min:2,max:2',
            'payment_option'        => 'in:hosted,iframe,seamless',
            'billing_name'          => 'max:100|alpha_spaces',
            'billing_address'       => 'max:200|alpha_spaces',
            'billing_city'          => 'max:20|alpha',
            'billing_state'         => 'max:20|alpha',
            'billing_zip'           => 'numeric|digits_between:1,8',
            'billing_phone'         => 'digits_between:1,10|numeric',
            'billing_email'         => 'email',
            'shipping_name'         => 'max:100|alpha_spaces',
            'shipping_address'      => 'max:200|alpha_spaces',
            'shipping_city'         => 'max:20|alpha',
            'shipping_state'        => 'max:20|alpha',
            'shipping_zip'          => 'numeric|digits_between:1,8',
            'shipping_phone'        => 'digits_between:1,10|numeric',
            'shipping_email'        => 'email',
        ], [
            'order_id.required'     => 'Merchant Order ID is missing',
            'order_id.alpha_num'    => 'Merchant Order Id is containing the invalid characters, it must be alphanumeric only',
            'order_amount.required' => 'Merchant order amount is missing',
            'order_amount.numeric'  => 'Merchant order amount is invalid, it should be like 25.00',
            'order_amount.min'      => 'Merchant order amount is invalid, it should be like 25.00',
            'customer_email.required'        => 'Customer email is missing',
            'customer_email.email'           => 'Customer email is invalid',
            'customer_phone.required'        => 'Customer phone is missing',
            'customer_phone.phone'           => 'Customer phone is invalid',
            'success_url.required'           => 'Success return url is missing',
            'success_url.url'                => 'Success return url is invalid, it should start with http url',
            'failed_url.required'            => 'Failed/canceled url is missing',
            'failed_url.url'                 => 'failed/canceled url is invalid, it should start with http url',
        ]);

        if ($v->fails()) {

            $errors = $v->errors()->getMessages();
            $obj    = $v->failed();
            $result = [];
            foreach ($obj as $input => $rules) {

                $i = 0;

                foreach ($rules as $rule => $ruleInfo) {

                    $error_code = 400;

                    if ($input == 'order_id' && strtolower($rule) == 'required') {
                        $error_code = 301;
                    } elseif ($input == 'order_id' && strtolower($rule) == 'alpha_num') {
                        $error_code = 302;
                    } elseif ($input == "order_amount" && strtolower($rule) == "required") {
                        $error_code = 303;
                    } elseif ($input == "order_amount" && strtolower($rule) == "numeric") {
                        $error_code = 304;
                    } elseif ($input == "customer_email" && strtolower($rule) == "required") {
                        $error_code = 305;
                    } elseif ($input == "customer_email" && strtolower($rule) == "email") {
                        $error_code = 306;
                    } elseif ($input == "customer_phone" && strtolower($rule) == "required") {
                        $error_code = 307;
                    } elseif ($input == "customer_phone" && strtolower($rule) == "phone") {
                        $error_code = 308;
                    } elseif ($input == "success_url" && strtolower($rule) == "required") {
                        $error_code = 309;
                    } elseif ($input == "success_url" && strtolower($rule) == "url") {
                        $error_code = 311;
                    } elseif ($input == "failed_url" && strtolower($rule) == "required") {
                        $error_code = 310;
                    } elseif ($input == "failed_url" && strtolower($rule) == "url") {
                        $error_code = 312;
                    } elseif ($input == "cuurency" && strtolower($rule) == "max") {
                        $error_code = 300;
                    }

                    $result[$error_code] = $errors[$input][$i];
                    $i++;
                }
            }

            return response()->json([
                'errors' => $result,
            ], 422);
        }

        $data = $request->merchant_id . $request->access_key . $request->order_id . $request->order_amount . $request->currency . $request->customer_email . $request->customer_phone;

        if ($request->hash != hash('sha512', $data)) {
            return response()->json([
                'errors' => [
                    313 => 'Hash key is invalid',
                ],
            ], 422);
        }

        /*$access_key = AccessKey::where('merchant_id', $request->merchant_id)
            ->where('type', 'live')->get()
            ->filter(function ($data) use ($request) {
                return $data->access_key == $request->access_key;
            })
            ->first();

        if (!$access_key) {
            return response()->json([
                'errors' => [
                    '401' => 'Unauthorized',
                ],
            ], 401);
        } */

        //Check if valid merchant_id
        $access_key = AccessKey::where('merchant_id', $request->merchant_id)
                                  ->where('type', 'live')
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
                'order_id'     => $request->order_id,
                'order_amount' => $request->order_amount,
                'customer_email'        => $request->customer_email,
                'customer_phone'        => $request->customer_phone,
                'success_url'           => $request->success_url,
                'failed_url'            => $request->failed_url,
                'encryption'            => $request->hash,
                'language'              => $request->language,
                'payment_option'        => 'hosted',
                'status'                => 'Pending',
            ]);

            $transaction->billing_address()->create([
                'type'    => 'billing',
                'name'    => $request->billing_name,
                'city'    => $request->billing_city,
                'state'   => $request->billing_state,
                'address' => $request->billing_address,
                'zip'     => $request->billing_zip,
                'email'   => $request->billing_email,
                'phone'   => $request->billing_phone,
            ]);

            $transaction->shipping_address()->create([
                'type'    => 'shipping',
                'name'    => $request->shipping_name,
                'city'    => $request->shipping_city,
                'state'   => $request->shipping_state,
                'address' => $request->shipping_address,
                'zip'     => $request->shipping_zip,
                'email'   => $request->shipping_email,
                'phone'   => $request->shipping_phone,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'errors' => [
                    400 => $e->getMessage(),
                ],
            ], 400);
        }

         return response()->json([
            'kartpay_id' => $transaction->kartpay_id,
            'url'        => route('live.payments.show', ['kartpay_id' => encrypt($transaction->kartpay_id)]),
        ]);

        /* return redirect()-> route('live.payments.show', encrypt($transaction->id)); */

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
            $this->send_email('Sushant', 'sushant@kartpay.com', 'Sushant', '(Live) Manipulated Payment URL', getLiveEnv('MAIL_FROM_ADDRESS'), getLiveEnv('MAIL_FROM_NAME'), '', 'User tried manipulated payment URL');
            //END SEND EMAIL
            abort(404);
        }

        $transaction = Transaction::where('kartpay_id', '=', $kartpay_id)->first();

        if (!$transaction) {
            // return response()->json([
            //     'errors' => [
            //         'status_code' => '404',
            //         'message'     => 'Not found',
            //     ],
            // ], 404);
            return view('live.pages.payments.error', ['error' => 'Payment not found.']);
        } elseif ($transaction->paid_at) {
            return view('live.pages.payments.error', ['error' => 'Payment already paid.']);
        } elseif ($transaction->status != 'Pending') {
            return view('test.pages.payments.error', ['error' => 'Payment invalid.']);
        } elseif (Carbon::now()->gte($transaction->created_at->addMinutes(10)) || $transaction->connect_ccavenue == '1') {
            return view('live.pages.payments.error', ['error' => 'Payment already expired.']);
        }

        return view('live.pages.payments.show', compact('transaction', 'kartpay_id'));
    }

    public function process(Request $request, $kartpay_id)
    {

        $current_year = date('Y');

        $first_two = substr($current_year, 0, 2);

        $request['expiry_year'] = $first_two . '' . $request['expiry_year'];
        try {

            $kartpay_id = decrypt($kartpay_id);
        } catch (Exception $e) {
            //SEND EMAIL
            $this->send_email('Sushant', 'sushant@kartpay.com', 'Sushant', '(Live) Manipulated Payment URL', getLiveEnv('MAIL_FROM_ADDRESS'), getLiveEnv('MAIL_FROM_NAME'), '', 'User tried manipulated payment URL');
            //END SEND EMAIL
            abort(404);
        }

        if(isset($request->card_number)){
            $request->card_number = str_replace(" ", "", $request->card_number);
        }

        if($request->payment_method == 'credit_card' || $request->payment_method == 'debit_card'){

          $bank = "";

          if($request->payment_method == 'net_banking' || $request->payment_method == 'wallet'){
            $bank = $request->card_name;
          }

          $routes = PaymentMethodRoutes::getRoutes($request->payment_method, $bank);

          if(!count($routes)){
            return view('live.pages.payments.error', ['error' => 'No service available for this payment method.']);
          }
        }

        $transaction = Transaction::where('kartpay_id', '=', $kartpay_id)->first();

        if (!$transaction) {
            return view('live.pages.payments.error', ['error' => 'Payment already expired or doesn\'t exists.']);
        }

        if ($transaction->paid_at) {
            return view('live.pages.payments.error', ['error' => 'Payment already paid.']);
        } elseif (Carbon::now()->gte($transaction->created_at->addMinutes(10)) || $transaction->connect_ccavenue == '1') {
            return view('live.pages.payments.error', ['error' => 'Payment already expired.']);
        }

        if ($request->payment_option == 'OPTCRDC' || $request->payment_option == 'OPTDBCRD') {

            $request['expiry_date'] = $request->expiry_month . '/' . $request->expiry_year;

            $v = Validator::make($request->all(), [
                'card_name'   => 'required',
                'card_number' => 'required|ccn',
                'card_cvc'    => 'required|cvc',
                'expiry_date' => 'required|ccd',
            ], [
                'card_number.ccn' => 'The :attribute field is invalid',
                'card_cvc.cvc'    => 'The :attribute field is invalid',
                'expiry_date.ccd' => 'The card is already expired',
            ]);

            $card = CreditCard::validCreditCard($request->card_number);

            if ($card['valid']) {
                $request['card_name'] = $card['type'];
            }

            $request['payment_option'] = 'OPTCRDC';

        } elseif ($request->payment_option == 'OPTNBK') {

            $v = Validator::make($request->all(), [
                'card_name' => 'required|in:Andhra Bank, Bank of Baharin and Kuwait,Bank of Baroda Corporate,Bank of Baroda Retail,Bank of India,Bank of Maharashtra,
                Canara Bank,Catholic Syrian Bank,Central Bank of India,Citibank,City Union Bank,Corporation Bank,DBS Bank Ltd,DCB BANK Business,DCB BANK Personal,
                Deutsche Bank,Dhanlaxmi Bank,Federal Bank,IDBI Bank,Indian Bank,Indian Overseas Bank,IndusInd Bank,ING Vysya Bank,Jammu and kashmir Bank,
                Karnataka Bank,Karur Vysya Bank,Lakshmi Vilas Bank,Oriental Bank Of Commerce,Punjab National Bank,Punjab National Bank Corporate Accounts,
                Royal Bank Of Scotland,Saraswat Bank,Shamrao Vithal Co-op. Bank Ltd,South Indian Bank,Standard Chartered Bank,State Bank Of Bikaner and Jaipur,
                State Bank Of Hyderabad,State Bank Of Mysore,State Bank of Patiala,State Bank of Travancore,Syndicate Bank,Tamilnad Mercantile Bank,
                Union Bank of India,United Bank of India,Vijaya Bank,YES Bank,Axis Bank,State Bank of India,Kotak Mahindra Bank,HDFC Bank,ICICI Bank',
            ]);

            $request['issuing_bank'] = $request->card_name;

            // } elseif ($request->payment_option == 'OPTCASHC') {

            // } elseif ($request->payment_option = 'OPTMOBP') {

            // } elseif ($request->payment_option = 'OPTEMI') {

        } elseif ($request->payment_option == 'OPTWLT') {

            $v = Validator::make($request->all(), [
                'card_name' => 'required|in:Mobikwik,Paytm,Jana Cash,jioMoney,SBI Buddy,PayZapp,FreeCharge',
            ]);

            $request['issuing_bank'] = '';

        } else {

            $v = Validator::make($request->all(), [
                'payment_option' => 'required|in:OPTCDRC,OPTDBCRD,OPTNBK,OPTCASHC,OPTMOBP,OPTEMI,OPTWLT',
            ]);
        }

        if ($v->fails()) {

            // if ($request->wantsJson()) {

            // foreach ($v->errors()->toArray() as $k => $error) {

            //     $errors[] = [
            //         'field'       => $k,
            //         'description' => $error[0],
            //     ];
            // }

            // return response()->json([
            //     'errors' => [
            //         'status_code' => 422,
            //         'message'     => 'Missing/invalid parameters',
            //         'parameters'  => $errors,
            //     ],
            // ], 422);
            // } else {
            // return $request->all();
            return back()->withErrors($v->errors());
            // }
            // return redirect(url($transaction->failed_url, [
            //     'id' => $id,
            // ]));
        }

        if (Carbon::now()->gte($transaction->created_at->addMinutes(10))) {
            return view('live.pages.payments.expired', compact('transaction'));
        } elseif ($transaction->paid_at || $transaction->connect_ccavenue == '1') {
            return view('live.pages.payments.expired', compact('transaction'));
        }

        $transaction->connect_ccavenue = '1';
        $transaction->status = 'Redirected';
        $transaction->save();

        $working_key = '6B960982747E90437E402FF1E0820F33'; //Shared by CCAVENUES
        $access_code = 'AVBV05CG51AF62VBFA'; //Shared by CCAVENUES
        $merchant_id = '70277';

        $merchant_data = '';

        $data = [
            'merchant_id'      => $merchant_id,
            'order_id'         => $transaction->kartpay_id,
            'amount'           => number_format($transaction->order_amount, 2),
            'currency'         => 'INR',
            'redirect_url'     => route('live.payments.success'),
            'cancel_url'       => route('live.payments.failed'),
            'language'         => 'EN',
            'billing_name'     => 'Salasar eCommerce Total Solutions Pvt Ltd',
            'billing_address'  => '235, old Bagadganj Layout,Shastri Nagar Chowk',
            'billing_city'     => 'Nagpur',
            'billing_state'    => 'MH',
            'billing_zip'      => '440008',
            'billing_country'  => 'India',
            'billing_tel'      => '8446688833',
            'billing_email'    => 'support@kartpay.com',
            'delivery_name'    => 'Salasar eCommerce Total Solutions Pvt Ltd',
            'delivery_address' => '235, old Bagadganj Layout,Shastri Nagar Chowk',
            'delivery_city'    => 'Nagpur',
            'delivery_state'   => 'MH',
            'delivery_zip'     => '440008',
            'delivery_country' => 'India',
            'delivery_tel'     => '8446688833',
            'delivery_email'   => 'support@kartpay.com',
            'payment_option'   => $request->payment_option,
            'card_type'        => substr($request->payment_option, 3),
            'card_number'      => $request->card_number,
            'data_accept'      => 'Y',
            'card_name'        => $request->card_name,
            'expiry_month'     => $request->expiry_month,
            'expiry_year'      => $request->expiry_year,
            'cvv_number'       => $request->card_cvc,
            'issuing_bank'     => $request->issuing_bank,
        ];

        $merchant_data = http_build_query($data, '', '&');

        // foreach ($data as $key => $value) {
        //     $merchant_data .= $key . '=' . urlencode($value) . '&';
        // }

        // return $merchant_data;

        $encrypted_data = @ccencrypt($merchant_data, $working_key); // Method for encrypting the data.
        //$encrypted_data = openssl_encrypt($merchant_data, config('app.cipher'), $working_key, 0, $access_key); // Method for encrypting the data.
        $final_data     = 'encRequest=' . $encrypted_data . '&access_code=' . $access_code;

        // return [
        //     'data'           => $data,
        //     'merchant_data'  => $merchant_data,
        //     'final_data'     => $final_data,
        //     'encrypted_data' => $encrypted_data,
        // ];

        if(isset($routes) && $routes[0]->route != 'ccavenue'){
            return view('live.pages.payments.error', ['error' => 'No service available for this payment method.']);
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return web page
        curl_setopt($ch, CURLOPT_HEADER, false); // don't return headers
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects
        curl_setopt($ch, CURLOPT_ENCODING, ""); // handle all encodings
        curl_setopt($ch, CURLOPT_USERAGENT, "kartpay"); // who am i
        curl_setopt($ch, CURLOPT_AUTOREFERER, true); // set referer on redirect
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // timeout on connect
        curl_setopt($ch, CURLOPT_TIMEOUT, 120); // timeout on response
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10); // stop after 10 redirects
        curl_setopt($ch, CURLOPT_URL, "https://secure.ccavenue.com/transaction/transaction.do?command=initiatePayloadTransaction");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $final_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($ch);

        $decode = json_decode($result, true);

        if (isset($decode['errorMessage'])) {
            return redirect(
                url($transaction->failed_url . '?' . http_build_query([
                    'kartpay_id'     => $transaction->kartpay_id,
                    'order_id'       => $transaction->order_id,
                    'order_amount'   => number_format($transaction->order_amount, 2),
                    'status'         => 'Failure',
                    'status_message' => $decode['errorMessage'],
                ]))
            );
        }

        return response($result);
    }

    public function success(Request $request)
    {
        $workingKey = '6B960982747E90437E402FF1E0820F33';

        $encResponse = $request->encResp;
        $kartpay_id  = $request->orderNo;

        $rcvdString    = @ccdecrypt($encResponse, $workingKey);
        $decryptValues = explode('&', $rcvdString);
        $dataSize      = sizeof($decryptValues);

        $transaction = Transaction::findOrFail($request->orderNo);

        for ($i = 0; $i < $dataSize; $i++) {

            $information = explode('=', $decryptValues[$i]);
            if ($i == 3) {
                $order_status = $information[1];
            }
            if ($i == 8) {
                $status_message = $information[1];
            }
        }

        if ($order_status === 'Success') {

            $transaction->status         = $order_status;
            $transaction->status_message = $status_message;
            $transaction->paid_at        = Carbon::now();
            $transaction->save();

            //START Send WebHook API
            if (($transaction->status == 'Failure' || $transaction->status == 'Canceled') && $order_status == 'Success') {
                $access_key = AccessKey::where('merchant_id', $transaction->merchant_id)
                    ->where('type', 'live')
                    ->first();

                if ($access_key) {
                    $merchant = Merchant::where('id', '=', $access_key->user_id)
                        ->where('webhook', '<>', null)
                        ->first();

                    if ($merchant) {
                        // Get cURL resource
                        $curl = curl_init();
                        // Set some options - we are passing in a useragent too here
                        curl_setopt_array($curl, array(
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_URL            => $merchant->webhook,
                            CURLOPT_USERAGENT      => 'kartpay',
                            CURLOPT_POST           => 1,
                            CURLOPT_POSTFIELDS     => array(
                                'kartpay_id'   => $transaction->kartpay_id,
                                'order_id'     => $transaction->order_id,
                                'order_amount' => $transaction->order_amount,
                                'order_status' => $order_status,
                            ),
                        ));
                        // Send the request & save response to $result
                        $result = curl_exec($curl);
                        // Close request to clear up some resources
                        curl_close($curl);
                    }
                }
            }
            //END Send WebHook API

            return view('live.pages.payments.ccavenue_redirect',
                [
                    'title'   => 'Success',
                    'message' => 'Your Transaction is Successful, Please do not Refresh or reload the Browser.',
                    'url'     => url($transaction->success_url . '?' . http_build_query([
                        'kartpay_id'            => $transaction->kartpay_id,
                        'order_id'     => $transaction->order_id,
                        'order_amount' => number_format($transaction->order_amount, 2),
                        'status'                => $transaction->status,
                        'status_message'        => $transaction->status_message,
                    ])),
                ]);

            /* return redirect(
        url($transaction->success_url . '?' . http_build_query([
        'kartpay_id'     => $transaction->id,
        'order_id'       => $transaction->order_id,
        'order_amount'   => number_format($transaction->order_amount, 2),
        'status'         => $order_status,
        'status_message' => $status_message,
        ]))
        ); */

        } else {

            $transaction->status         = $order_status;
            $transaction->status_message = $status_message;
            $transaction->save();

            return redirect()->route('live.payments.retry', encrypt($transaction->kartpay_id));
        }
    }

    public function failed(Request $request)
    {
        $workingKey = '6B960982747E90437E402FF1E0820F33';

        $encResponse = $request->encResp;
        $kartpay_id  = $request->orderNo;

        $rcvdString    = @ccdecrypt($encResponse, $workingKey);
        $decryptValues = explode('&', $rcvdString);
        $dataSize      = sizeof($decryptValues);

        for ($i = 0; $i < $dataSize; $i++) {

            $information = explode('=', $decryptValues[$i]);
            if ($i == 3) {
                $order_status = $information[1];
            }
            if ($i == 8) {
                $status_message = $information[1];
            }
        }

        $transaction                 = Transaction::findOrFail($request->orderNo);
        $transaction->status         = $order_status;
        $transaction->status_message = $status_message;
        $transaction->save();

        return redirect()->route('live.payments.retry', encrypt($kartpay_id, $url));

        /*
        if ($order_status === 'Failed') {
        return view('live.pages.payments.ccavenue_redirect',
        [
        'title'   => 'Failed',
        'message' => 'Your Transaction is Failed/',
        'url'     => url($transaction->failed_url . '?' . http_build_query([
        'kartpay_id'     => $transaction->id,
        'order_id'       => $transaction->order_id,
        'order_amount'   => number_format($transaction->order_amount, 2),
        'status'         => $order_status,
        'status_message' => $status_message,
        ])),
        ]);
        }
        if ($order_status === 'Aborted') {
        return view('live.pages.payments.ccavenue_redirect',
        [
        'title'   => 'Aborted',
        'message' => 'Your Transaction is Aborted.',
        'url'     => url($transaction->failed_url . '?' . http_build_query([
        'kartpay_id'     => $transaction->id,
        'order_id'       => $transaction->order_id,
        'order_amount'   => number_format($transaction->order_amount, 2),
        'status'         => $order_status,
        'status_message' => $status_message,
        ])),
        ]);
        }
        if ($order_status === 'Canceled') {
        return view('live.pages.payments.ccavenue_redirect',
        [
        'title'   => 'Canceled',
        'message' => 'Your Transaction is Canceled.',
        'url'     => url($transaction->failed_url . '?' . http_build_query([
        'kartpay_id'     => $transaction->id,
        'order_id'       => $transaction->order_id,
        'order_amount'   => number_format($transaction->order_amount, 2),
        'status'         => $order_status,
        'status_message' => $status_message,
        ])),
        ]);
        }

         */

        /* return redirect(
    url($transaction->failed_url . '?' . http_build_query([
    'kartpay_id'     => $transaction->id,
    'order_id'       => $transaction->order_id,
    'order_amount'   => number_format($transaction->order_amount, 2),
    'status'         => $order_status,
    'status_message' => $status_message,
    ]))
    ); */
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
            ->where('type', 'live')->first();

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
            'kartpay_id'   => $transaction->kartpay_id,
            'order_id'     => $transaction->order_id,
            'order_amount' => $transaction->order_amount,
            'status'       => $transaction->status,
        ]);

    }

    public function retry($kartpay_id)
    {
        try {

            $transaction = Transaction::findOrFail(decrypt($kartpay_id));
        } catch (Exception $e) {
            abort(404);
        }

        $url = url($transaction->success_url . '?' . http_build_query([
            'kartpay_id'            => $transaction->kartpay_id,
            'order_id'     => $transaction->order_id,
            'order_amount' => number_format($transaction->order_amount, 2),
            'status'                => $transaction->status,
            'status_message'        => $transaction->status_message,
        ]));

        return view('live.pages.payments.retry', compact('transaction', 'url'));
    }

    public function postRetry(Request $request, $kartpay_id)
    {
        try {
            $transaction = Transaction::findOrFail(decrypt($kartpay_id));
        } catch (Exception $e) {
            abort(404);
        }

        $new = Transaction::create([
            'merchant_id'           => $transaction->merchant_id,
            'access_key'            => $transaction->access_key,
            'order_id'     => $transaction->order_id,
            'currency'              => $transaction->currency,
            'order_amount' => $transaction->order_amount,
            'customer_email'        => $transaction->customer_email,
            'customer_phone'        => $transaction->customer_phone,
            'success_url'           => $transaction->success_url,
            'failed_url'            => $transaction->failed_url,
            'encryption'            => $transaction->encryption,
        ]);

        return redirect()->route('live.payments.show', encrypt($new->kartpay_id));
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
