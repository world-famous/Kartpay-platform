<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmailSettingRequest;
use App\Http\Requests\Admin\SmsSettingRequest;
use App\Http\Requests\Admin\AftershipSettingRequest;
use Artisan;
use Auth;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Traits\LogsActivity;

class SettingController extends Controller
{
    use LogsActivity;

    /**
     * Log Event
     *
     * @var string
     */
    protected $eventUpdateSms     = 'SMS-Setting';
    protected $eventUpdateEmail   = 'Email-Setting';
    protected $eventUpdateSession = 'Session-Setting';
    protected $eventUpdateAftership = 'Aftership-Setting';
    protected $eventUpdateStaging     = 'Staging-Setting';
    protected $eventUpdateBin   = 'Bin-Setting';

    /**
     * Log Description
     *
     * @var string
     */
    protected $updateSmsSuccess     = 'SMS setting updated successfuly';
    protected $updateEmailSuccess   = 'Email setting updated successfuly';
    protected $updateSessionSuccess = 'Session setting updated successfuly';
    protected $updateAftershipSuccess = 'Aftership setting updated successfuly';
    protected $updateStagingSuccess = 'Staging setting updated successfuly';
    protected $updateBinSuccess = 'Bin setting updated successfuly';

    /**
     * Get Emails Settings
     *
     * @return View
     */
    public function getEmailSettings()
    {
        return view('panel.backend.pages.email', config('mail'));
    }

    /**
     * Post Add Emails Settings to env
     *
     * @param EmailSettingRequest $request
     * @return redirect
     */
    public function addEmailSettings(EmailSettingRequest $request)
    {
        $user = Auth::guard('admin')->user();
        //ACTIVITY LOG
        activity($this->eventUpdateEmail)->causedBy($user)->withProperties([
            'attributes' =>
            [
                'MAIL_DRIVER'       => $request->driver,
                'MAIL_HOST'         => $request->host,
                'MAIL_PORT'         => $request->port,
                'MAIL_USERNAME'     => $request->username,
                'MAIL_PASSWORD'     => $request->password,
                'MAIL_ENCRYPTION'   => $request->encryption,
                'MAIL_FROM_ADDRESS' => $request->address,
                'MAIL_FROM_NAME'    => $request->name,
            ],
            'old'        =>
            [
                'MAIL_DRIVER'       => getLiveEnv('MAIL_DRIVER'),
                'MAIL_HOST'         => getLiveEnv('MAIL_HOST'),
                'MAIL_PORT'         => getLiveEnv('MAIL_PORT'),
                'MAIL_USERNAME'     => getLiveEnv('MAIL_USERNAME'),
                'MAIL_PASSWORD'     => getLiveEnv('MAIL_PASSWORD'),
                'MAIL_ENCRYPTION'   => getLiveEnv('MAIL_ENCRYPTION'),
                'MAIL_FROM_ADDRESS' => getLiveEnv('MAIL_FROM_ADDRESS'),
                'MAIL_FROM_NAME'    => getLiveEnv('MAIL_FROM_NAME'),
            ],
        ])->log($this->updateEmailSuccess);
        //END ACTIVITY LOG

        setEnv('MAIL_DRIVER', $request->driver);
        setEnv('MAIL_HOST', $request->host);
        setEnv('MAIL_PORT', $request->port);
        setEnv('MAIL_USERNAME', $request->username);
        setEnv('MAIL_PASSWORD', $request->password);
        setEnv('MAIL_ENCRYPTION', $request->encryption);
        setEnv('MAIL_FROM_ADDRESS', $request->address);
        setEnv('MAIL_FROM_NAME', $request->name);

        Artisan::call('config:cache');

        return redirect()->back();
    }

    /**
     * Get Aftership Settings
     *
     * @return View
     */
    public function getAftershipSettings()
    {
        return view('panel.backend.pages.aftership', config('aftership'));
    }

    /**
     * Post Add Aftership Settings to env
     *
     * @param AftershipSettingRequest $request
     * @return redirect
     */
    public function addAftershipSettings(AftershipSettingRequest $request)
    {
        $user = Auth::guard('admin')->user();
        //ACTIVITY LOG
        activity($this->eventUpdateAftership)->causedBy($user)->withProperties([
            'attributes' =>
            [
                'AFTERSHIP_LIVE_KEY'      => $request->live_key
            ],
            'old'        =>
            [
                'AFTERSHIP_LIVE_KEY'      => getLiveEnv('AFTERSHIP_LIVE_KEY')
            ],
        ])->log($this->updateAftershipSuccess);
        //END ACTIVITY LOG

        setEnv('AFTERSHIP_LIVE_KEY', $request->live_key);

        Artisan::call('config:cache');

        $redirect = redirect()->back()->with('success', 'Successfully updated key.');

        return $redirect;
    }

    /**
     * Get Sms Settings
     *
     * @return View
     */
    public function getSmsSettings()
    {
        return view('panel.backend.pages.sms', config('sms'));
    }

    /**
     * Post Add Sms Settings to env
     *
     * @param SmsSettingRequest $request
     * @return redirect
     */
    public function addSmsSettings(SmsSettingRequest $request)
    {
        $user = Auth::guard('admin')->user();
        //ACTIVITY LOG
        activity($this->eventUpdateSms)->causedBy($user)->withProperties([
            'attributes' =>
            [
                'SMS_URL'      => $request->url,
                'SMS_SENDER'   => $request->sender,
                'SMS_USERNAME' => $request->username,
                'SMS_PASSWORD' => $request->password,
                'OTP_SETTING'  => $request->otp_setting,
            ],
            'old'        =>
            [
                'SMS_URL'      => getLiveEnv('SMS_URL'),
                'SMS_SENDER'   => getLiveEnv('SMS_SENDER'),
                'SMS_USERNAME' => getLiveEnv('SMS_USERNAME'),
                'SMS_PASSWORD' => getLiveEnv('SMS_PASSWORD'),
                'OTP_SETTING'  => getLiveEnv('OTP_SETTING'),
            ],
        ])->log($this->updateSmsSuccess);
        //END ACTIVITY LOG

        setEnv('SMS_URL', $request->url);
        setEnv('SMS_SENDER', $request->sender);
        setEnv('SMS_USERNAME', $request->username);
        setEnv('SMS_PASSWORD', $request->password);
        setEnv('OTP_SETTING', $request->otp_setting);

        Artisan::call('config:cache');

        return redirect()->back();
    }
    /**
     * END Post Add Sms Settings to env
     *
     * @param SmsSettingRequest $request
     * @return redirect
     */

     /**
      * Get Session Settings
      *
      */
    public function getSessionSettings()
    {
        $session = config('session');

        return view('panel.backend.pages.settings.session', compact('session'));
    }
    /**
     * END Get Session Settings
     *
     */

     /**
      * Post Add Session Settings to env
      *
      * @param SessionSettingRequest $request
      * @return redirect
      */
    public function addSessionSettings(Request $request)
    {
        $this->validate($request, [
            'timeout'         => 'min:1|max:15|required|numeric',
            'timeout_message' => 'string|required',
            'timeout_delay'   => 'min:1|numeric|required',
        ]);

        $user = Auth::guard('admin')->user();
        //ACTIVITY LOG
        activity($this->eventUpdateSession)->causedBy($user)->withProperties([
            'attributes' =>
            [
                'SESSION_TIMEOUT_KARTPAY' => $request->timeout,
                'SESSION_TIMEOUT_MESSAGE' => $request->timeout_message,
                'SESSION_TIMEOUT_DELAY'   => $request->timeout_delay,
            ],
            'old'        =>
            [
                'SESSION_TIMEOUT_KARTPAY' => config('session.timeout'),
                'SESSION_TIMEOUT_MESSAGE' => config('session.timeout_message'),
                'SESSION_TIMEOUT_DELAY'   => config('session.timeout_delay'),
            ],
        ])->log($this->updateSessionSuccess);

        setEnv('SESSION_TIMEOUT_KARTPAY', $request->timeout);
        setEnv('SESSION_TIMEOUT_MESSAGE', '"' . $request->timeout_message . '"');
        setEnv('SESSION_TIMEOUT_DELAY', $request->timeout_delay);

        Artisan::call('config:cache');
        sleep(3);

        return redirect()->back();
    }
    /**
     * END Post Add Session Settings to env
     *
     * @param SessionSettingRequest $request
     * @return redirect
     */

     /**
      * Get Firewall Settings
      *
      */
    public function getFirewallSettings()
    {
        $firewall = config('services.sucuri');
        return view('panel.backend.pages.settings.firewall', compact('firewall'));
    }
    /**
     * END Get Firewall Settings
     *
     */

    /**
     * Post Add Firewall Settings to env
     *
     * @param FirewallSetting Request $request
     * @return redirect
     */
    public function postFirewallSettings(Request $request)
    {
        $this->validate($request, [
            'api_key' => 'required',
            'secret_key' => 'required'
        ]);

        setEnv('SUCURI_API_KEY', $request->api_key);
        setEnv('SUCURI_SECRET_KEY', $request->secret_key);

        Artisan::call('config:cache');
        sleep(3);

        return redirect()->back();
    }
    /**
     * END Post Add Firewall Settings to env
     *
     * @param FirewallSettingRequest $request
     * @return redirect
     */

     /**
      * Get Staging Settings
      *
      */
    public function getStagingSettings()
    {
        return view('panel.backend.pages.settings.staging', config('app_env'));
    }
    /**
     * END Get Staging Settings
     *
     */

     /**
      * Post Add Staging Settings to env
      *
      * @param StagingSetting Request $request
      * @return redirect
      */
    public function addStagingSettings(Request $request)
    {
        setEnv('APP_ENV', $request->app_env);

        Artisan::call('config:cache');
        sleep(3);

        return redirect()->back();
    }
    /**
     * END Post Add Staging Settings to env
     *
     * @param StagingSetting Request $request
     * @return redirect
     */

     /**
      * Get Bin Settings
      *
      */
    public function getBinSettings()
    {
        return view('panel.backend.pages.settings.bin', config('bin'));
    }
    /**
     * END Get Bin Settings
     *
     */

    /**
     * Post Add Bin Settings to env
     *
     * @param BinSettings Request $request
     * @return redirect
     */
    public function addBinSettings(Request $request)
    {
        setEnv('BIN_CODES_API_KEY', $request->bin_codes_api_key);
        setEnv('BIN_CODES_FORMAT', 'json');
        setEnv('BIN_CODES_ENABLE', $request->bin_codes_enable);

        Artisan::call('config:cache');
        sleep(3);

        return redirect()->back();
    }
    /**
     * END Post Add Bin Settings to env
     *
     * @param BinSettings Request $request
     * @return redirect
     */

     /**
      * Get Invitation Email Settings
      *
      */
    public function getInvitationEmailSettings()
    {
        return view('panel.backend.pages.settings.invitation_email', config('invitation_email'));
    }
    /**
     * END Get Bin Settings
     *
     */

    /**
     * Post Add Invitation Email Settings to env
     *
     * @param InvitationEmailSettings Request $request
     * @return redirect
     */
    public function addInvitationEmailSettings(Request $request)
    {
        setEnv('INVITATION_EMAIL_DELAY_SECONDS', $request->invitation_email_delay_seconds);

        Artisan::call('config:cache');
        sleep(3);

        return redirect()->back();
    }
    /**
     * END Post Add Invitation Email Settings to env
     *
     * @param InvitationEmailSettings Request $request
     * @return redirect
     */
}
