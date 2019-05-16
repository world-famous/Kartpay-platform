<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Cache\RateLimiter;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                              'name',
                              'first_name',
                              'last_name',
                              'country_code',
                              'contact_no',
                              'email',
                              'password',
                              'verification_code',
                              'otp',
                              'last_send_email_secret_login',
                              'last_send_otp',
                              'is_active'
                          ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
                            'password', 'remember_token',
                        ];

    /*
  	 * Get Avatar Image that related to User
  	 *
  	*/
    public function getAvatarAttribute()
    {
        if ($this->avatar_file_name)
        {
            return asset('images/vendor/' . $this->avatar_file_name);
        }
        else
        {
            return asset('images/vendor/dummy-profile-pic.png');
        }
    }
    /*
  	 * END Get Avatar Image that related to User
  	 *
  	*/

    /*
     * Get Rate Limiter class that related to Admins which attempts > 6
     *
    */
    public function getIsBlockedAttribute()
    {
        return app(RateLimiter::class)->tooManyAttempts($this->email, 6);
    }
    /*
     * END Get Rate Limiter class that related to Admins which attempts > 6
     *
    */

    /*
     * Get Rate Limiter class that related to Admins
     *
    */
    public function limiter()
    {
        return app(RateLimiter::class);
    }
    /*
     * END Get Rate Limiter class that related to Admins
     *
    */
}
