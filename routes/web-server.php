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
 
use Illuminate\Http\Request;

/* Route::get('/dashboard', function () {
    return view('panel.backend.pages.dashboard');
}); */

/**
 * Standard Login and Registration Routes
 * Start
 */
 /*
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/verification', function () {
    return view('auth.verification');
})->name('verification');

Route::get('/verification/{id}', 'Auth\RegisterController@verification_email');
Route::get('/verification/resend_link/resend', function () {
    return view('auth.resend_link');
})->name('resend_link');
Route::post('/verification/resend_link/resend', 'Auth\RegisterController@resendLink');
Route::post('/verification/otp', 'Auth\RegisterController@verificationOtp')->name('verification_otp');
Route::get('/verification/resend_otp/resend', 'Auth\RegisterController@resendOtp')->name('resend_otp');
Route::post('/verification/create_password', 'Auth\RegisterController@createPassword')->name('create_password');

Route::post('register', 'Auth\RegisterController@register');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
*/
/**
 * Standard Login and Registration Routes
 * End
 */

Route::get('/', 'controller@index')
    ->middleware('CheckFirstTimeAccess')
    ->name('home');
	
Route::get('/home', function () {
    return view('admin.home');
})->name('admin_home');

/**
 * OTP Generation Routes
 * Start
 */
Route::group(['prefix' => 'otp', 'middleware' => 'auth'], function() {
    Route::get('verifyNumber', 'OTPController@showVerifyNumberForm')->name('verifyContactNoForm');
    Route::post('verifyNumber', 'OTPController@sendVerificationCode')->name('verifyContactNo');

    Route::get('verifyCode', 'OTPController@showVerifyCodeForm')->name('verifyCodeForm');
    Route::post('verifyCode', 'OTPController@verifyCode')->name('verifyCode');
});
/**
 * OTP Generation Routes
 * End
 */

/**
 * Setup 5 Virtual Subdomain
 * start
 */
Route::group(['domain' => 'login.pg-application.dev'], function() {
	Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
	Route::post('login', 'Auth\LoginController@login');

	Route::get('/register', function () {
		return view('auth.register');
	})->name('register');

	Route::get('/verification', function () {
		return view('auth.verification');
	})->name('verification');

	Route::get('/verification/{id}', 'Auth\RegisterController@verification_email');
	Route::get('/verification/resend_link/resend', function () {
		return view('auth.resend_link');
	})->name('resend_link');
	Route::post('/verification/resend_link/resend', 'Auth\RegisterController@resendLink');
	Route::post('/verification/otp', 'Auth\RegisterController@verificationOtp')->name('verification_otp');
	Route::get('/verification/resend_otp/resend', 'Auth\RegisterController@resendOtp')->name('resend_otp');
	Route::post('/verification/create_password', 'Auth\RegisterController@createPassword')->name('create_password');

	Route::post('register', 'Auth\RegisterController@register');
});

Route::group(['domain' => 'merchant.pg-application.dev'], function() {
	// Dashboard (dashboard task are given on other task)
	Route::get('/', function () {
		return view('panel.backend.pages.dashboard');
	});
	
	Route::post('logout', 'Auth\LoginController@logout')->name('logout');
});

Route::group(['domain' => 'panel.pg-application.dev'], function() {
    Route::group(array('namespace' => 'admin'), function(){
        Route::get('email-settings', "SettingController@getEmailSettings");
        Route::post('email-settings', "SettingController@addEmailSettings");
        Route::get('sms-settings', "SettingController@getSmsSettings");
        Route::post('sms-settings', "SettingController@addSmsSettings");
    });
});

Route::group(['domain' => 'live.pg-application.dev'], function() {
	// Rest API (api Task given in another task)
});

Route::group(['domain' => 'test.pg-application.dev'], function() {
	// Rest API (api Task given in another task)
});
/**
 * Setup 5 Virtual Subdomain
 * end
 */

/*
 * dbSetup related Routes Start
 */

Route::group(['middleware' => 'dbNotAlreadySetup'], function () {
    Route::get('/setup', function () {
        return view('dbSetup.index');
    })->name('askDbCredentials');

    Route::post('/setup', 'DbSetupController@updateDbCredentials')
        ->name('postDbCredentials');
});
/*
 * dbSetup Related Routes End
 */
// Rest API

Route::group(['prefix'=>'restApi' , 'middleware' => ['web'] ], function(){

    Route::get('testForm' , function(){
        return view('restApi/testForm'); 
    });

    // Route::post('testFormPost' , function(Request $r){
        
    //     dd($r);

    // })->name('testFormPost');

    Route::post('testFormPost' , 'restApiController@restApiPostForm')->name('testFormPost');
});
