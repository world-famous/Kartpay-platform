<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\CcavRequest;
use App\Models\ApiParametersMaster;
use App\Models\PaymentOption;
use App\Models\PaymentType;
use App\Models\Tax;
use App\Models\TdrPlanOption;
use App\Models\UserTransactions;
use App\User;
use App\Utilities\ApiHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CcavController extends ApiController
{

    /**
     * @return mixed
     */
    public function index()
    {
        return view("ccav.index1");
    }

    public function newRequest()
    {
        return view("ccav.index2");

    }

    /**
     * @param CcavRequest $ccavRequest
     */

    public function store(CcavRequest $ccavRequest, ApiHelper $apiHelper)
    {
        $access_code = $this->getAccessCode();//'AVBV05CG51AF62VBFA';//Shared by CCAVENUES
        $merchant_data = '';
        $i = 0;
        $len = count($ccavRequest->request);
        $transaction = UserTransactions::findOrFail($ccavRequest->merchant_param1);
        $transaction->payment_type_id = $this->translatePaymentType($ccavRequest->payment_option);
        $transaction->payment_option_id = $this->getPaymentOptionByPaymentType($ccavRequest->payment_option, $ccavRequest);
        $transaction->fees = $this->getTnxFees($ccavRequest->amount, $this->getPaymentOptionByPaymentType($ccavRequest->payment_option, $ccavRequest), $transaction->user_id);
        $tax = $this->calculateTnxTax($transaction->fees);
        $transaction->tax = is_array($tax) ? array_sum($this->calculateTnxTax($transaction->fees)) : 0;
        //dd($transaction->tax);
        $transaction->service_tax = (is_array($tax) && count($tax) > 0) ? $this->calculateTnxTax($transaction->fees)[0] : 0;
        $transaction->swachh_bharat_cess = (is_array($tax) && count($tax) > 1) ? $this->calculateTnxTax($transaction->fees)[1] : 0;;
        $transaction->krishi_kalyan_cess = (is_array($tax) && count($tax) > 2) ? $this->calculateTnxTax($transaction->fees)[2] : 0;;

        $transaction->final_amount = $ccavRequest->amount - ($transaction->fees + $transaction->tax);
        $transaction->save();
        foreach ($ccavRequest->request as $key => $value) {
            if ($key <> "_token" && $key <> 'route_name' && $key <> 'working_key' && $key <> 'access_code' && $key <> 'expiry_date' && $key <> 'card_number') {
                $merchant_data .= $key . '=' . urlencode($value);
            }
            if ($key == 'card_number') {
                $merchant_data .= $key . '=' . urlencode(str_replace(" ", "", $value));

            }
            if ($key == 'expiry_date') {
                $expirey_exploded = explode("/", $value);
                if (count($expirey_exploded) > 1) {
                    $merchant_data .= "expiry_month" . "=" . $expirey_exploded[0] . "&";
                    $merchant_data .= "expiry_year" . "=20" . $expirey_exploded[1];
                }
            }
            if ($i < $len - 1) {
                // last
                if ($key <> "_token" && $key <> 'route_name' && $key <> 'working_key' && $key <> 'access_code') {
                    $merchant_data .= '&';
                }
            }
            // …
            $i++;
        }
        //dd($merchant_data);
        $encrypted_data = $apiHelper->encrypt($merchant_data, $this->getWorkingKey());
        /*echo $this->workingKey;
        echo "<br/>";
        echo $this->access_code;
        dd($apiHelper->decrypt($encrypted_data, $this->workingKey));*/
        // Method for encrypting the data.
        //$production_url = 'https://test.ccavenue.com/transaction/transaction.do?command=initiatePayloadTransaction&encRequest=' . $encrypted_data . '&access_code=' . $this->access_code;
        $final_data = 'encRequest=' . $encrypted_data . '&access_code=' . $access_code;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     // return web page
        curl_setopt($ch, CURLOPT_HEADER, false);    // don't return headers
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);     // follow redirects
        curl_setopt($ch, CURLOPT_ENCODING, "");       // handle all encodings
        curl_setopt($ch, CURLOPT_USERAGENT, "spider"); // who am i
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);     // set referer on redirect
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);      // timeout on connect
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);     // timeout on response
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);      // stop after 10 redirects
        curl_setopt($ch, CURLOPT_URL,
            "https://secure.ccavenue.com/transaction/transaction.do?command=initiatePayloadTransaction");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $final_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($ch);
        curl_close($ch);
        //echo "<pre>";

        //dd($result);
        //$result = json_decode($result) ?? $result;
        // dd($result);
        // if (!empty(get_object_vars($result))) {
        /*if (isset($result->errorMessage)) {
        }*/
        if (json_decode($result)) {
            $result = json_decode($result);
            return view("ccav.iframe", ['error' => $result]);

        }
        return $result;
    }

    public function cancel(Request $request, ApiHelper $apiHelper)
    {
        $redirect_urlPassed = urldecode($request->cancel_url);
        $encSendedResponse = $apiHelper->encryptForResponse($request->all(), $this->getWorkingKey());

        return $apiHelper->responseRedirect($redirect_urlPassed, $encSendedResponse);

    }

    public function response(Request $request, ApiHelper $apiHelper)
    {

        $encResponse = $request->encResp;//$_POST["encResp"];			//This is the response sent by the CCAvenue Server
        $rcvdString = $apiHelper->decrypt($encResponse,
            $this->getWorkingKey());        //Crypto Decryption used as per the specified working key.
        $order_status = "";
        $decryptValues = explode('&', $rcvdString);
        $dataSize = sizeof($decryptValues);
        for ($i = 0; $i < $dataSize; $i++) {
            $information = explode('=', $decryptValues[$i]);
            if ($i == 3) {
                $order_status = $information[1];
            }
            if ($information[0] == 'merchant_param2') {
                $oldIP = $information[1];
            }

            if ($information[0] == 'merchant_param1') {
                $tnxid = $information[1];
            }
        }
        $tnxOurs = UserTransactions::find($tnxid);
        if ($oldIP <> $tnxOurs->transactionFromIp) {
            return $this->respondWithError('Changed Ip Detected');
        }
        //dd($tnxOurs);
        if ($tnxOurs->status == 1) {
            return $this->respondWithError('Reload Action Detected !!');
        }

        $tnxOurs->status = true;
        $tnxOurs->save();

        if ($order_status === "Success") {
            echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";

        } else {
            if ($order_status === "Aborted") {
                echo "<br>Thank you for shopping with us.We will keep you posted regarding the status of your order through e-mail";

            } else {
                if ($order_status === "Failure") {
                    echo "<br>Thank you for shopping with us.However,the transaction has been declined.";
                } else {
                    echo "<br>Security Error. Illegal access detected";

                }
            }
        }


        $redirect_urlPassed = "";
        $passedToMerchantData = "";
        for ($i = 0; $i < $dataSize; $i++) {
            $information = explode('=', $decryptValues[$i]);
            if ($information[0] <> 'merchant_param1') {
                // echo '<tr><td>' . $information[0] . '</td><td>' . $information[1] . '</td></tr>';
                if ($information[0] == 'order_id' || $information[0] == 'amount' || $information[0] == 'order_status') {
                    if ($i > 0) {
                        $passedToMerchantData .= "&";
                    }
                    switch ($information[0]) {
                        case 'order_id':
                            $passedToMerchantData .= "txnid=" . $information[1];
                            break;
                        case 'amount' :
                            $passedToMerchantData .= "amount=" . $information[1];
                            break;
                        case  'order_status':
                            $passedToMerchantData .= "status=" . $information[1];
                            break;
                    }
                    //$passedToMerchantData .= $information[0] . "=" . urlencode($information[1]);
                }
            } else {
                $tnxid = $information[1];
                $redirect_url = ApiParametersMaster::where('parameter-name', 's_url')
                    ->whereHas('tnxParameters', function ($q) use ($tnxid) {
                        $q->where('transaction_id', $tnxid);
                    })->with('tnxParameters')->first();
                $countedArray = count($redirect_url->tnxParameters);
                $redirect_urlPassed .= $redirect_url->tnxParameters[$countedArray - 1]->parameter_value;
                // echo '<tr><td>redirect URL </td><td>' . $redirect_urlPassed . '</td></tr>';
                if ($i > 0) {
                    $passedToMerchantData .= "&";
                }
                $passedToMerchantData .= "kartpay_id=" . $information[1];

            }
        }
        $encSendedResponse = $apiHelper->encrypt($passedToMerchantData, $this->getWorkingKey());

        return $apiHelper->responseRedirect($redirect_urlPassed, $encSendedResponse);
    }

    public function inner($errorTransfeered)
    {
        return view("ccav.iframe-inner", ['errorTransfeered' => $errorTransfeered]);
    }


    private function translatePaymentType($payment_option)
    {
        switch ($payment_option) {
            case 'OPTNBK' :
                $paymentType = PaymentType::where('shortapicode', 'NBK')->first();
                return $paymentType->id;
                break;
            case 'OPTCRDC':
                $paymentType = PaymentType::where('shortapicode', 'cc')->first();
                return $paymentType->id;
                break;
            case 'OPTDBCRD':
                $paymentType = PaymentType::where('shortapicode', 'DC')->first();
                return $paymentType->id;
                break;
            case 'OPTWLT':
                $paymentType = PaymentType::where('shortapicode', 'WLT')->first();
                return $paymentType->id;
                break;
            case 'OPTCASHC':
                $paymentType = PaymentType::where('shortapicode', 'CASHC')->first();
                return $paymentType->id;
                break;
        }
    }

    private function getPaymentOptionByPaymentType($paymentOption, Request $request)
    {
        switch ($paymentOption) {
            case 'OPTNBK' :
                $apiCode = $request->issuing_bank;
                break;
            case 'OPTCRDC' :
                $apiCode = $request->card_name;
                break;
            case 'OPTDBCRD' :
                $apiCode = $request->card_name;
                break;
            case 'OPTWLT' :
                $apiCode = $request->issuing_bank;
                break;
            case 'OPTCASHC' :
                $apiCode = $request->card_name;
                break;

        }
        $option = PaymentOption::where('apicode', $apiCode)->first();
        return $option->id;
    }

    private function getTnxFees($amount, $paymentOption, $merchantID)
    {
        $user = User::find($merchantID);
        $tdrPlan = TdrPlanOption::where('from', '<', $amount)
            ->where('to', '>', $amount)
            ->where('payment_option_id', $paymentOption);
        if ($user->tdr_plan_id) {
            $plan_id = $user->tdr_plan_id;
            $tdrPlan->where('tdr_plan_id', $plan_id);
        } else {
            $tdrPlan->where('merchant_id', $user->id);
        }
        $firstTdr = $tdrPlan->first();
        if (!$firstTdr) {

            return 0;
        }
        $fees_type = $firstTdr->tdr_type;
        if ($fees_type == 'percentage') {

            return $amount * $firstTdr->tdr_amount / 100;
        }

        return $firstTdr->tdr_amount;
    }

    private function calculateTnxTax($amount)
    {
        $taxPlan = Tax::active()->where('applicable_from_date', '<', Carbon::now()->toDateString())->first();
        $feeTax = $amount * ($taxPlan->service_tax / 100);
        $swachh_bharat_cess = $feeTax * ($taxPlan->swachh_bharat_cess / 100);
        $krishi_kalyan_cess = $feeTax * ($taxPlan->krishi_kalyan_cess / 100);

        return [$feeTax, $swachh_bharat_cess, $krishi_kalyan_cess];
    }
}
