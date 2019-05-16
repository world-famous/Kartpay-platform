<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OTPController extends Controller
{
    /**
     * This is to show the contact no confirmation form
     */
    public function showVerifyNumberForm()
    {
        // must return a view
    }

    /**
     * generating a 4 digits otp code for contact no confirmation
     * @param Request $request
     */
    public function sendVerificationCode(Request $request)
    {
        $user = Auth::guard('merchant')->user();

        $otp       = $this->generateCode();
        $user->otp = $otp;

        // sending the opt into the contact no using the sms facade
        SendSMS::send($user->contact_no, $user->otp);
        $user->save();
    }

    /**
     * this is to show the OTP verification code
     */
    public function showVerifyCodeForm()
    {
        // must return a view
    }

    /**
     * verifythe 4 digits otp code for the contact no
     * @param Request $request
     * @return boolean
     */
    public function verifyCode(Request $request)
    {
        $otp  = $request->otp;
        $user = Auth::guard('merchant')->user();

        if ($user->opt == $otp) {
            return true;
        }
        return false;
    }

    /**
     * generate the 4 digits code
     * this is not official this will be change in the future
     * todo @MKB
     * @return int
     */
    private function generateCode()
    {
        $digits = 4;
        return rand(pow(10, $digits - 1), pow(10, $digits) - 1);
    }
}
