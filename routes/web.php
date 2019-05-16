<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

/**
 * Enable/Disable Debug Bar
 * Start
 */
Route::get('/enable_debugbar/{value}', 'DebugbarController@changeDebugMode');
/**
 * Enable/Disable Debug Bar
 * End
 */

/**
 * Refresh Captcha
 * Start
 */
Route::post('/refresh_captcha', 'CaptchaController@refreshCaptcha');
/**
 * Refresh Captcha
 * End
 */

/**
 * dbSetup related Routes Start
 *
 */

Route::group(['namespace' => 'Setup', 'middleware' => 'dbNotAlreadySetup'], function () {
    Route::get('/setup', function () {
        return view('dbSetup.index');
    })->name('askDbCredentials');

    Route::post('/setup', 'DbSetupController@updateDbCredentials')
        ->name('postDbCredentials');
});

/**
 * DBSetup Related Routes End
 *
 */

/**
 * Start Merchant Route
 *
 */

Route::group(['domain' => 'merchant.' . config('app.url'), 'namespace' => 'Merchant', 'as' => 'merchants.',
    'middleware'           => 'blacklist'], function () {

    /**
     * Start Merchant Guest Route
     *
     */
    Route::group(['middleware' => ['guest:merchant']], function () {

        Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('/login', 'Auth\LoginController@login');

        Route::group(['middleware' => 'PreventBackHistory'], function () {
          //STAFF REGISTRATION
          Route::get('register/staff/{id}', 'Auth\RegisterController@staffRegistration')->name('staff_registration');
          Route::get('register/complete', 'Auth\RegisterController@completeRegistration')->name('complete_registration');
          //END STAFF REGISTRATION

          //VERIFICATION OTP LOGIN
          Route::get('verification/otp_login', 'Auth\LoginController@verificationOtpPage');
          Route::get('verification/resend_otp_login/resend', 'Auth\LoginController@resendOtp');
          Route::post('verification/otp_login', 'Auth\LoginController@verificationOtp');
          //END VERIFICATION OTP LOGIN

          //REGISTER
          Route::get('register', [
              'as'   => 'register',
              'uses' => 'Auth\RegisterController@viewRegister',
          ]);
          Route::post('register', [
              'as'   => 'register',
              'uses' => 'Auth\RegisterController@register',
          ]);
          //END REGISTER

          Route::get('/verification', function () {
              return view('auth.verification');
          })->name('verification');

          Route::get('/verification/{id}', 'Auth\RegisterController@verificationEmail');
          Route::get('/verification/login/{id}', 'Auth\LoginController@verificationEmailLogin');
          Route::get('/verification/register/{id}', 'Auth\RegisterController@verificationEmailRegistration');
          Route::post('/verification/otp', 'Auth\RegisterController@verificationOtp')->name('verification_otp');
          Route::get('/verification/resend_otp/resend', 'Auth\RegisterController@resendOtp')->name('resend_otp');
          Route::post('/verification/create_password', 'Auth\RegisterController@createPassword')->name('create_password');

          Route::get('forgot_password', 'Auth\ForgotPasswordController@forgotPassword')->name('forgot_password');
          Route::post('forgot_password', 'Auth\ForgotPasswordController@sendLinkForgot');
          Route::get('/verification/forgot_password/complete', 'Auth\ForgotPasswordController@changePasswordComplete')->name('change_password_complete');
          Route::get('/verification/forgot_password/resend_link/resend', function () {
              return view('auth.resend_link_forgot');
          })->name('resend_link_forgot');
          Route::get('/verification/forgot_password/otp', function () {
              return view('auth.verification_otp_forgot');
          });
          Route::get('/verification/forgot_password/resend_otp/resend', 'Auth\ForgotPasswordController@resendOtpForgot')->name('resend_otp_forgot');
          Route::post('/verification/forgot_password/otp', 'Auth\ForgotPasswordController@verificationOtpForgot')->name('verification_otp_forgot');
          Route::get('/verification/forgot_password/change_password_forgot', function () {
              return view('auth.change_password_forgot');
          });
          Route::post('/verification/forgot_password/change_password_forgot', 'Auth\ForgotPasswordController@changePasswordForgot')->name('change_password_forgot');
          Route::get('/verification/forgot_password/{id}', 'Auth\ForgotPasswordController@verificationEmailForgot');
          Route::post('/verification/forgot_password/resend_link/resend', 'Auth\ForgotPasswordController@sendLinkForgot');
          Route::post('/verification/forgot_password/verification_contact_no', 'Auth\ForgotPasswordController@verificationContactNoForgot')->name('verification_contact_no_forgot');
        });

    });

    Route::get('couriers/sync', 'CouriersController@sync');
    /**
     * End Merchant Guest Route
     *
     */

    /**
     * Start Auth Merchant Route
     * with default namespace of Merchant
     *
     */
    Route::group(['middleware' => ['auth:merchant']], function () {

        Route::group(['middleware' => ['lastpass']], function() {
            Route::get('/', function () {
                return view('merchant.backend.pages.dashboard');
            })->name('dashboard.merchant');

            Route::post('settings/webhook', [
                'uses' => 'SettingsController@webhook',
                'as'   => 'settings.webhook',
            ]);
            Route::resource('/settings', SettingsController::class, [
                'except' => ['show'],
            ]);

            //USER PROFILE
            Route::get('/profile', 'ProfileController@edit');
            Route::post('profile/update', 'ProfileController@update');
            Route::post('profile/update_avatar', 'ProfileController@updateAvatar');
            Route::post('/profile/bank_details/update', 'ProfileController@updateBankDetails')->name('update_bank_details');
            Route::post('/profile/send_otp', 'ProfileController@sendOTP')->name('send_otp.merchant');
            Route::post('/profile/verify_otp', 'ProfileController@verifyOTP')->name('verify_otp.merchant');
            Route::post('/profile/check_valid_otp', 'ProfileController@checkValidOtp')->name('check_valid_otp');
            Route::post('/profile/business_information/update', 'ProfileController@updateBusinessInformation')->name('update_business_information');
            Route::post('/profile/personal_information/update', 'ProfileController@updatePersonalInformation')->name('update_personal_information');
            //END USER PROFILE

            //ACTIVITY LOGS
            Route::get('/log', 'LogController@log');
            Route::get('/log/display', 'LogController@display');
            //END ACTIVITY LOGS

            //1st MERCHANT ONLY
            Route::group(['middleware' => 'MerchantOnly'], function () {
                //USER LISTS
                Route::get('/user_administration', 'UserAdministrationController@userAdministration')->name('admin_administration');
                Route::get('user_administration/display', 'UserAdministrationController@display');
                Route::get('user_administration/{id}/show', 'UserAdministrationController@show');
                Route::get('user_administration/{id}/edit', 'UserAdministrationController@edit');
                Route::post('user_administration/{id}/update', 'UserAdministrationController@update');
                Route::get('user_administration/add_new_staff', 'UserAdministrationController@addNewStaff')->name('add_new_staff');
                Route::get('user_administration/send_email_invitation/{id}', 'UserAdministrationController@sendEmailInvitation')->name('send_email_invitation');
                Route::post('user_administration/store_new_staff', 'UserAdministrationController@storeNewStaff')->name('store_new_staff');
                //END USER LISTS
            });
            //END 1st MERCHANT ONLY

            //ACTIVATE ACCOUNT
            Route::get('/activation/check_step', 'ActivationController@checkStep')->name('activation.check_step');
            Route::get('/activation/step_1', 'ActivationController@step1')->name('activation.step1');
            Route::get('/activation/process_step1', 'ActivationController@processStep1')->name('activation.process_step1');
            Route::get('/activation/step_2', 'ActivationController@step2')->name('activation.step2');
            Route::post('/activation/step_2', 'ActivationController@processStep2')->name('activation.process_step2');
            Route::get('/activation/step_3', 'ActivationController@step3')->name('activation.step3');
            Route::post('/activation/step_3', 'ActivationController@processStep3')->name('activation.process_step3');
            Route::get('/activation/step_4', 'ActivationController@step4')->name('activation.step4');
            Route::post('/activation/step_4', 'ActivationController@processStep4')->name('activation.process_step4');
        		Route::get('/activation/status', "ActivationController@documentApprovalStatus")->name('activation.status');
        		Route::get('/activation/settlement', "ActivationController@settlement")->name('activation.settlement');
            Route::get('/activation/not_approve', 'ActivationController@notApproveDocument')->name('activation.not_approve_document');
        		Route::post('/activation/settlement', "ActivationController@processSettlement")->name('activation.process_settlement');
        		Route::get('/activation/not_receive_document', "ActivationController@not_receive_document")->name('activation.not_receive_document');

    			//UPLOAD DOCUMENTS
    			Route::post('/merchant_document/upload', 'ActivationController@uploadMerchantDocument')->name('upload.merchant_document');
    			Route::post('/merchant_document/remove', 'ActivationController@removeMerchantDocument')->name('remove.merchant_document');
    			Route::get('/merchant_document/upload', 'ActivationController@getMerchantDocument')->name('get.merchant_document');
    			//END UPLOAD DOCUMENTS

            //END ACTIVATE ACCOUNT

            //GET STATE
            Route::get('/country/get_state_by_country', 'ActivationController@getStateByCountry')->name('get_state_by_country');
            Route::get('/country/get_city_by_state', 'ActivationController@getCityByState')->name('get_city_by_state');
            Route::get('/country/get_city_by_state_autocomplete', 'ActivationController@getCityByStateAutocomplete')->name('get_city_by_state_autocomplete');
            //END GET STATE

            //ACCESS FILE ON STORAGE
            Route::get('file/merchant_document/{id}/{file_name}', function ($id, $file_name) {
                $path = storage_path('app/merchant_document/' . $id . '/' . $file_name);

                if (!File::exists($path)) {
                    abort(404);
                }

                $file = File::get($path);
                $type = File::mimeType($path);

                $response = Response::make($file, 200);
                $response->header("Content-Type", $type);

                return $response;
            });
            //END ACCESS FILE ON STORAGE

            Route::get('transactions/search', "TransactionsController@search")->name('transaction.search');

            Route::resource('transactions', TransactionsController::class);
            Route::resource('refunds', RefundsController::class);

            /**
            * Route for Trackngs
            */
            Route::resource('trackings', TrackingsController::class);
        });

        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('password/change', 'PasswordsController@create');
        Route::post('password/change', 'PasswordsController@store');

    });

    /**
     * End Auth Merchant Route
     *
     */

});

/**
 * Start Admin/Panel Route
 * This route is for admin/panel
 * with default namespace of Admin
 *
 */
Route::group(['domain' => 'panel.' . config('app.url'), 'namespace' => 'Admin', 'as' => 'admins.',
    'middleware'           => 'blacklist'], function () {

    /**
     * Start Guest Route
     *
     */

    Route::group(['middleware' => ['guest:admin']], function () {

        Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('/login', 'Auth\LoginController@login');

        Route::group(['middleware' => 'PreventBackHistory'], function () {
          //REGISTER
          Route::get('register', [
              'as'   => 'register',
              'uses' => 'Auth\RegisterController@viewRegister',
          ]);
          Route::post('register', [
              'as'   => 'register',
              'uses' => 'Auth\RegisterController@register',
          ]);
          //END REGISTER

          //STAFF REGISTRATION
          Route::get('register/staff/{id}', 'Auth\RegisterController@staffRegistration')->name('staff_registration');
          Route::get('register/complete', 'Auth\RegisterController@completeRegistration')->name('complete_registration');
          //END STAFF REGISTRATION

          //VERIFICATION OTP LOGIN
          Route::get('verification/otp_login', 'Auth\LoginController@verificationOtpPage');
          Route::get('verification/resend_otp_login/resend', 'Auth\LoginController@resendOtp');
          Route::post('verification/otp_login', 'Auth\LoginController@verificationOtp');
          Route::get('/verification/login/{id}', 'Auth\LoginController@verificationEmailLogin');
          //END VERIFICATION OTP LOGIN

          //VERIFICATION
          Route::get('/verification', function () {
              return view('auth.verification');
          })->name('verification');
          Route::get('/verification/{id}', 'Auth\RegisterController@verificationEmail');
          Route::post('verification/otp', 'Auth\RegisterController@verificationOtp')->name('verification_otp_panel');
          Route::get('verification/resend_otp/resend', 'Auth\RegisterController@resendOtp')->name('resend_otp_panel');
          Route::post('/verification/create_password', 'Auth\RegisterController@createPassword')->name('create_password');
          Route::get('verification/register/{id}', 'Auth\RegisterController@verificationEmailRegistration');
          //END VERIFICATION

          //FORGOT PASSWORD
          Route::get('/verification/forgot_password/email', function () {
              return view('auth.verification');
          });
          Route::get('forgot_password', function () {
              return view('auth.forgot_password_email');
          })->name('forgot_password');
          Route::post('forgot_password', 'Auth\ForgotPasswordController@sendLinkForgot');
          Route::get('/verification/forgot_password/otp', function () {
              return view('auth.verification_otp_forgot');
          });
          Route::get('/verification/forgot_password/resend_otp/resend', 'Auth\ForgotPasswordController@resendOtpForgot');
          Route::post('/verification/forgot_password/otp', 'Auth\ForgotPasswordController@verificationOtpForgot');
          Route::get('/verification/forgot_password/change_password_forgot', function () {
              return view('auth.change_password_forgot');
          });
          Route::post('/verification/forgot_password/change_password_forgot', 'Auth\ForgotPasswordController@changePasswordForgot');
          Route::get('/verification/forgot_password/complete', 'Auth\ForgotPasswordController@changePasswordComplete')->name('change_password_complete');
          Route::get('/verification/forgot_password/{id}', 'Auth\ForgotPasswordController@verificationEmailForgot');
          Route::post('/verification/forgot_password/verification_contact_no', 'Auth\ForgotPasswordController@verificationContactNoForgot');
          //END FORGOT PASSWORD
        });
    });

    /**
     * End Guest Route
     *
     */

    /**
     * Start Auth Route
     *
     */

    Route::group(['middleware' => ['auth:admin']], function () {

        Route::group(['middleware' => 'lastpass'], function(){
            Route::get('transactions/search', "TransactionsController@search")->name('transaction.search');
            Route::resource('transactions', TransactionsController::class);
            Route::post('transactions/set_status', "TransactionsController@setStatus")->name('transaction.set_status');

            //EMAIL SETTINGS
            Route::get('email-settings', "SettingController@getEmailSettings");
            Route::post('email-settings', "SettingController@addEmailSettings");
            //END EMAIL SETTINGS

            //SMS SETTINGS
            Route::get('sms-settings', "SettingController@getSmsSettings");
            Route::post('sms-settings', "SettingController@addSmsSettings");
            //END SMS SETTINGS

            //SESSION SETTINGS
            Route::get('session-settings', "SettingController@getSessionSettings");
            Route::post('session-settings', "SettingController@addSessionSettings");
            //END SESSION SETTINGS

            Route::get('/', function () {
                if (Auth::user()->is_active == '0') {
                    $type = 'panel';
                    return view('auth.register', compact('type'));
                } else {
                    return view('panel.backend.pages.dashboard');
                }

            })->name('dashboard.panel');

            //USER LISTS
            Route::get('/user_administration', 'UserAdministrationController@userAdministration')->name('admin_administration');
            Route::post('user_administration/{id}/block', 'UserAdministrationController@block');
            Route::post('user_administration/{id}/unblock', 'UserAdministrationController@unblock');
            Route::get('user_administration/display', 'UserAdministrationController@display');
            Route::get('user_administration/{id}/show', 'UserAdministrationController@show');
            Route::get('user_administration/{id}/edit', 'UserAdministrationController@edit');
            Route::post('user_administration/{id}/update', 'UserAdministrationController@update');
            Route::get('user_administration/{id}/destroy', 'UserAdministrationController@destroy');
            //END USER LISTS

            //USER PROFILE
            Route::get('/profile', 'ProfileController@edit');
            Route::post('profile/update', 'ProfileController@update');
            Route::post('profile/update_avatar', 'ProfileController@updateAvatar');
            Route::post('/profile/send_otp', 'ProfileController@sendOTP')->name('send_otp');
            Route::post('/profile/verify_otp', 'ProfileController@verifyOTP')->name('verify_otp');
            Route::post('/profile/check_valid_otp', 'ProfileController@checkValidOtp')->name('check_valid_otp');
            //END USER PROFILE

            //MERCHANT LISTS
            Route::get('/merchant_administration', 'MerchantAdministrationController@merchantAdministration')->name('merchant_administration');
            Route::get('merchant_administration/display', 'MerchantAdministrationController@display');
            Route::get('merchant_administration/{id}/show', 'MerchantAdministrationController@show')->name('merchant_administration_show');
            Route::get('merchant_administration/{id}/edit', 'MerchantAdministrationController@edit');
            Route::post('merchant_administration/{id}/update', 'MerchantAdministrationController@update');
            Route::post('merchant_administration/{id}/block', 'MerchantAdministrationController@block');
            Route::post('merchant_administration/{id}/unblock', 'MerchantAdministrationController@unblock');
            Route::get('merchant_administration/{id}/destroy', 'MerchantAdministrationController@destroy')->middleware('AdminOnly')->name('merchant_administration_destroy');
            //END MERCHANT LISTS

            //MERCHANT DOCUMENT APPROVAL
            Route::get('merchant_administration/{id}/document_approval_step1', 'MerchantAdministrationController@documentApprovalStep1')->name('merchant_administration.document_approval_step1');
            Route::post('merchant_administration/{id}/document_approval_step1', 'MerchantAdministrationController@processDocumentApprovalStep1')->name('merchant_administration.process_document_approval_step1');
    		Route::get('merchant_administration/{id}/document_approval_step2', 'MerchantAdministrationController@documentApprovalStep2')->name('merchant_administration.document_approval_step2');
            Route::post('merchant_administration/{id}/document_approval_step2', 'MerchantAdministrationController@processDocumentApprovalStep2')->name('merchant_administration.process_document_approval_step2');
            //END MERCHANT DOCUMENT APPROVAL

            //MERCHANT DETAILS
            Route::post('merchant_administration/{id}/profile/update', 'MerchantAdministrationController@update')->name('update_merchant_profile');
            Route::post('merchant_administration/{id}/update_avatar', 'MerchantAdministrationController@updateAvatar')->name('update_merchant_avatar');
            //END MERCHANT DETAILS

            //ACTIVITY LOGS
            Route::get('/log', 'LogController@log');
            Route::get('/log/display', 'LogController@display');
            //END ACTIVITY LOGS

            //GATEWAY SETTINGS
            Route::get('/gateway', 'GatewayController@gateway')->name('gateway');
            Route::get('/gateway/display', 'GatewayController@display')->name('display.gateway');
            Route::get('/gateway/add', 'GatewayController@add')->name('add.gateway');
            Route::get('/gateway/{id}/edit', 'GatewayController@edit')->name('edit.gateway');
            Route::post('/gateway/store', 'GatewayController@store')->name('store.gateway');
            Route::post('/gateway/{id}/update', 'GatewayController@update')->name('update.gateway');
            Route::get('/gateway/{id}/destroy', 'GatewayController@destroy')->name('destroy.gateway');

            Route::get('/gateway/{id}/type', 'GatewayTypeController@gatewayType')->name('gateway_type');
            Route::get('/gateway/{id}/type/display', 'GatewayTypeController@display')->name('display.gateway_type');
            Route::get('/gateway/{id}/type/add', 'GatewayTypeController@add')->name('add.gateway_type');
            Route::get('/gateway/{id}/type/{tid}/edit', 'GatewayTypeController@edit')->name('edit.gateway_type');
            Route::post('/gateway/{id}/type/store', 'GatewayTypeController@store')->name('store.gateway_type');
            Route::post('/gateway/{id}/type/{tid}/update', 'GatewayTypeController@update')->name('update.gateway_type');
            Route::get('/gateway/{id}/type/{tid}/destroy', 'GatewayTypeController@destroy')->name('destroy.gateway_type');
            //END GATEWAY SETTINGS

            //COUNTRY SETTINGS
            Route::get('/country-settings', 'CountryController@country')->name('country');
            Route::get('/country-settings/display', 'CountryController@display')->name('display.country');
            Route::get('/country-settings/add', 'CountryController@add')->name('add.country');
            Route::get('/country-settings/{id}/edit', 'CountryController@edit')->name('edit.country');
            Route::post('/country-settings/store', 'CountryController@store')->name('store.country');
            Route::post('/country-settings/{id}/update', 'CountryController@update')->name('update.country');
            Route::get('/country-settings/{id}/destroy', 'CountryController@destroy')->name('destroy.country');
            Route::post('/country-settings/set_country_status', 'CountryController@setCountryStatus')->name('set.country_status');
            //END COUNTRY SETTINGS

            //STATE STTINGS
  		      Route::get('/state-settings', 'StateController@state')->name('state');
            Route::get('/state-settings/display', 'StateController@display')->name('display.state');
            Route::get('/state-settings/add', 'StateController@add')->name('add.state');
            Route::get('/state-settings/{sid}/edit', 'StateController@edit')->name('edit.state');
            Route::post('/state-settings/store', 'StateController@store')->name('store.state');
            Route::post('/state-settings/{sid}/update', 'StateController@update')->name('update.state');
            Route::get('/state-settings/{sid}/destroy', 'StateController@destroy')->name('destroy.state');
            Route::post('/state-settings/set_state_status', 'StateController@setStateStatus')->name('set.state_status');
            Route::get('/state-settings/get_state_by_country', 'StateController@getStateByCountry')->name('get_state_by_country');
            //END STATE SETTINGS

            //CITY SETTINGS
  		      Route::get('/city-settings', 'CityController@city')->name('city');
            Route::get('/city-settings/display', 'CityController@display')->name('display.city');
            Route::get('/city-settings/add', 'CityController@add')->name('add.city');
            Route::get('/city-settings/{cid}/edit', 'CityController@edit')->name('edit.city');
            Route::post('/city-settings/store', 'CityController@store')->name('store.city');
            Route::post('/city-settings/{cid}/update', 'CityController@update')->name('update.city');
            Route::get('/city-settings/{cid}/destroy', 'CityController@destroy')->name('destroy.city');
            Route::post('/city-settings/set_state_status', 'CityController@setCityStatus')->name('set.city_status');
            //END CITY SETTINGS

  		      //SIGNUP STTINGS
            Route::get('/signup-settings', 'TermConditionController@term')->name('term');
            Route::post('/signup-settings', 'TermConditionController@update')->name('update.term');
            //END SIGNUP SETTINGS

            //aftership settings
            Route::get('aftership-settings', 'SettingController@getAftershipSettings');
            Route::post('aftership-settings', 'SettingController@addAftershipSettings');
            //end aftership settings

            //firewall settings
            Route::get('firewall-settings', 'SettingController@getFirewallSettings')->name('firewall.setting');
            Route::post('firewall-settings', 'SettingController@postFirewallSettings')->name('firewall.setting');
            Route::get('firewall-cache', 'FirewallController@cache')->name('firewall.cache');
            Route::get('firewall-ipaddress', 'FirewallController@ipaddress')->name('firewall.ipaddress');
            Route::post('firewall-cache', 'FirewallController@cacheClear')->name('firewall.cache');
            Route::post('firewall-blacklist', 'FirewallController@blacklist')->name('firewall.blacklist');
            Route::post('firewall-whitelist', 'FirewallController@whitelist')->name('firewall.whitelist');
            Route::post('firewall-remove', 'FirewallController@remove')->name('firewall.remove');
            //end firewall settings

            //SEARCH
            Route::post('search', 'SearchController@search')->name('search');
    		     //END SEARCH

            //payment methods
            Route::get('/payment_methods/list/{payment_method}', 'PaymentMethodsController@list');
            Route::get('/payment_methods/status/{id}/{status}', 'PaymentMethodsController@setStatus');
            Route::get('/payment_method_routes/method/{id}', 'PaymentMethodRoutesController@method');
            Route::post('/payment_method_routes/update_status/{id}', 'PaymentMethodRoutesController@updateStatus');
            //end payment methods

			      //BANK SETTINGS
            Route::get('/bank-settings', 'BankController@bank')->name('bank');
            Route::get('/bank-settings/display', 'BankController@display')->name('display.bank');
            Route::get('/bank-settings/add', 'BankController@add')->name('add.bank');
            Route::get('/bank-settings/{id}/edit', 'BankController@edit')->name('edit.bank');
            Route::post('/bank-settings/store', 'BankController@store')->name('store.bank');
            Route::post('/bank-settings/{id}/update', 'BankController@update')->name('update.bank');
            Route::get('/bank-settings/{id}/destroy', 'BankController@destroy')->name('destroy.bank');
            Route::post('/bank-settings/set_country_status', 'BankController@setBankStatus')->name('set.bank_status');
            //END BANK SETTINGS

            //STAGING SETTINGS
            Route::get('staging-settings', "SettingController@getStagingSettings")->name('staging');
            Route::post('staging-settings', "SettingController@addStagingSettings")->name('update.staging');;
            //END STAGING SETTINGS

            //BIN SETTINGS
            Route::get('bin-settings', "SettingController@getBinSettings")->name('bin');
            Route::post('bin-settings', "SettingController@addBinSettings")->name('update.bin');;
            //END BIN SETTINGS

            //BIN MASTER
            Route::get('bin', "BinEngineController@binEngine")->name('bin_engine');
            Route::get('bin/display', 'BinEngineController@display')->name('display.bin_engine');
            Route::get('bin/display/search', 'BinEngineController@search')->name('search.bin_engine');
            Route::post('bin/store_new_bin', "BinEngineController@storeNewBin")->name('store_bin_engine');
            Route::post('bin/destroy', "BinEngineController@destroyBin")->name('destroy_bin_engine');
            //END BIN MASTER

            //INVITATION EMAIL SETTINGS
            Route::get('invitation-email', "SettingController@getInvitationEmailSettings")->name('invitation_email');
            Route::post('invitation-email', "SettingController@addInvitationEmailSettings")->name('update.invitation_email');
            //END INVITATION EMAIL SETTINGS

            //ADMIN ONLY
            Route::group(['middleware' => 'AdminOnly'], function () {
                Route::get('user_administration/add_new_staff', 'UserAdministrationController@addNewStaff')->name('add_new_staff');
                Route::get('user_administration/send_email_invitation/{id}', 'UserAdministrationController@sendEmailInvitation')->name('send_email_invitation');
                Route::post('user_administration/store_new_staff', 'UserAdministrationController@storeNewStaff')->name('store_new_staff');
            });
            //END ADMIN ONLY

            //ACCESS FILE ON STORAGE
            Route::get('file/merchant_document/{id}/{file_name}', function ($id, $file_name) {
                $path = storage_path('app/merchant_document/' . $id . '/' . $file_name);

                if (!File::exists($path)) {
                    abort(404);
                }

                $file = File::get($path);
                $type = File::mimeType($path);

                $response = Response::make($file, 200);
                $response->header("Content-Type", $type);

                return $response;
            });
            //END ACCESS FILE ON STORAGE

            Route::resource('refunds', RefundsController::class);
        });


        Route::post('logout', 'Auth\LoginController@logout')->name('logout');
        Route::get('password/change', 'PasswordsController@create');
        Route::post('password/change', 'PasswordsController@store');
    });

    /**
     * End Auth Route
     *
     */

});

/*
 * End Admin/Panel Route
 *
 */

//DEMO
Route::get('/demo', function () {
    return view('demoDashBoard');
});
//END DEMO

/**
 * Test Api Domain
 *
 */

Route::group(['domain' => 'test.' . config('app.url'), 'namespace' => 'Test', 'prefix' => 'api', 'as' => 'test.',
    'middleware'           => 'blacklist'], function () {
    Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {

        Route::get('payments/fetch', 'PaymentsController@fetch');

        Route::get('/track', 'TrackingsController@track');

        Route::group(['middleware' => 'PreventBackHistory'],function(){

          Route::post('/payments', [
              'as'   => 'payments',
              'uses' => 'PaymentsController@store',
          ]);

            Route::get('/payments/{payment}', [
                'as'   => 'payments.show',
                'uses' => 'PaymentsController@show',
            ]);

          Route::post('/payments/{payment}/cancel', [
              'as'   => 'payments.cancel',
              'uses' => 'PaymentsController@cancel',
          ]);
          Route::post('/payments/{payment}/process', [
              'as'   => 'payments.process',
              'uses' => 'PaymentsController@process',
          ]);
        });

        Route::post('/txn_status', [
            'as'   => 'txn.status',
            'uses' => 'PaymentsController@txnStatus',
        ]);
    });
});

/**
 * Live Api Domain
 *
 */

Route::group(['domain' => 'live.' . config('app.url'), 'namespace' => 'Live', 'prefix' => 'api', 'as' => 'live.',
    'middleware'           => 'blacklist'], function () {
    Route::group(['prefix' => 'v1', 'namespace' => 'Api'], function () {

        Route::get('payments/fetch', 'PaymentsController@fetch');

        Route::get('/track', 'TrackingsController@track');

        Route::post('/payments', [
            'as'   => 'payments',
            'uses' => 'PaymentsController@store',
        ]);
        Route::post('/payments/{payment}/cancel', [
            'as'   => 'payments.cancel',
            'uses' => 'PaymentsController@cancel',
        ]);
        Route::post('/payments/success', [
            'as'   => 'payments.success',
            'uses' => 'PaymentsController@success',
        ]);
        Route::post('/payments/failed', [
            'as'   => 'payments.failed',
            'uses' => 'PaymentsController@failed',
        ]);
        Route::get('/payments/{payment}/retry', [
            'as'   => 'payments.retry',
            'uses' => 'PaymentsController@retry',
        ]);
        Route::post('/payments/{payment}/retry', [
            'as'   => 'payments.retry',
            'uses' => 'PaymentsController@postRetry',
        ]);
        Route::get('/payments/{payment}', [
            'as'   => 'payments.show',
            'uses' => 'PaymentsController@show',
        ]);
        Route::post('/payments/{payment}/process', [
            'as'   => 'payments.process',
            'uses' => 'PaymentsController@process',
        ]);

        Route::post('/txn_status', [
            'as'   => 'txn.status',
            'uses' => 'PaymentsController@txnStatus',
        ]);

        Route::resource('refunds', RefundsController::class, [
            'only' => ['store', 'show'],
        ]);

    });
});

Route::get('/', 'WelcomeController@index')
    ->middleware('CheckFirstTimeAccess')
    ->name('home');
Route::get('access', 'WelcomeController@whome')
    ->middleware('CheckFirstTimeAccess')
    ->name('home');
/**
 * OTP Generation Routes
 * Start
 */
Route::group(['prefix' => 'otp', 'middleware' => ['auth', 'blacklist']], function () {
    Route::get('verifyNumber', 'OTPController@showVerifyNumberForm')->name('verifyContactNoForm');
    Route::post('verifyNumber', 'OTPController@sendVerificationCode')->name('verifyContactNo');

    Route::get('verifyCode', 'OTPController@showVerifyCodeForm')->name('verifyCodeForm');
    Route::post('verifyCode', 'OTPController@verifyCode')->name('verifyCode');
});
/**
 * OTP Generation Routes
 * End
 */

// Rest API


Route::group(['prefix' => 'restApi'], function () {

    Route::get('backup/create', 'BackupController@create');

    Route::group(['middleware' => 'web'], function () {
        Route::get('testForm', function () {
            return view('restApi/testForm');
        });

        // Route::post('testFormPost' , function(Request $r){

        //     dd($r);

        // })->name('testFormPost');

        Route::post('testFormPost', 'restApiController@restApiPostForm')->name('testFormPost');
    });

    Route::post('testEncryption', 'restApiController@testEncryption')->name('testEncryption');
    Route::post('testEncryptionOrderStatus', 'restApiController@testEncryptionOrderStatus')->name('testEncryptionOrderStatus');

	Route::get('testPaymentIframe', 'restApiController@testPaymentIframe')->name('testPaymentIframe');
});
