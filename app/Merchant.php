<?php

namespace App;

use Illuminate\Cache\RateLimiter;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Merchant extends Authenticatable
{
    use Notifiable;
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
                              'is_active',
                              'type',
                              'merchant_id',
                          ];

    /*
  	 * Get Name Attribute
  	 *
  	*/
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    /*
  	 * END Get Name Attribute
  	 *
  	*/

    /*
  	 * Get Access Key that related to Merchant (using 'user_id' column)
  	 *
  	*/
    public function access_keys()
    {
        return $this->hasMany(AccessKey::class, 'user_id');
    }
    /*
  	 * END Get Access Key that related to Merchant (using 'user_id' column)
  	 *
  	*/

    /*
  	 * Do create new access_key when not exist which merchant_id = '0' and is_active' = '1'
  	 *
  	*/
    public function save(array $options = [])
    {
      parent::save($options);

      if ($this->is_active == 1 && !$this->access_keys->count() && $this->merchant_id == 0)
      {
        $this->access_keys()->create([
                                        'merchant_id' => $this->id,
                                        'access_key'  => str_random(20),
                                        'type'        => 'live',
                                    ]);
        $this->access_keys()->create([
                                        'merchant_id' => $this->id,
                                        'access_key'  => str_random(20),
                                        'type'        => 'test',
                                    ]);
      }

      return true;
    }
    /*
  	 * END Do create new access_key when not exist which merchant_id = '0' and is_active' = '1'
  	 *
  	*/

    /*
  	 * Get Avatar Attribute
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
  	 * END Get Avatar Attribute
  	 *
  	*/

    /*
  	 * Get Transaction class that related to Merchant
  	 *
  	*/
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    /*
  	 * END Get Transaction class that related to Merchant
  	 *
  	*/

    /*
  	 * Get Merchant Business Detail class that related to Merchant
  	 *
  	*/
    public function merchantBusinessDetail()
    {
        return $this->hasOne('App\MerchantBusinessDetail');
    }
    /*
  	 * END Get Merchant Business Detail class that related to Merchant
  	 *
  	*/

    /*
  	 * Get Merchant Contact Detail class that related to Merchant
  	 *
  	*/
    public function merchantContactDetail()
    {
        return $this->hasOne('App\MerchantContactDetail');
    }
    /*
  	 * END Get Merchant Contact Detail class that related to Merchant
  	 *
  	*/

    /*
  	 * Get Merchant Bank Detail class that related to Merchant
  	 *
  	*/
    public function merchantBankDetail()
    {
        return $this->hasOne('App\MerchantBankDetail');
    }
    /*
  	 * END Get Merchant Bank Detail class that related to Merchant
  	 *
  	*/

    /*
  	 * Get Merchant Website Detail class that related to Merchant
  	 *
  	*/
    public function merchantWebsiteDetail()
    {
        return $this->hasOne('App\MerchantWebsiteDetail');
    }
    /*
  	 * END Get Merchant Website Detail class that related to Merchant
  	 *
  	*/

    /*
  	 * Get Merchant Document class that related to Merchant
  	 *
  	*/
    public function merchantDocument()
    {
        return $this->hasOne('App\MerchantDocument');
    }
    /*
  	 * END Get Merchant Document class that related to Merchant
  	 *
  	*/

    /*
  	 * Get Rate Limiter class that related to Merchant which attempts > 6
  	 *
  	*/
    public function getIsBlockedAttribute()
    {
        return app(RateLimiter::class)->tooManyAttempts($this->email, 6);
    }
    /*
  	 * END Get Rate Limiter class that related to Merchant which attempts > 6
  	 *
  	*/

    /*
  	 * Get Rate Limiter class that related to Merchant
  	 *
  	*/
    public function limiter()
    {
        return app(RateLimiter::class);
    }
    /*
  	 * END Get Rate Limiter class that related to Merchant
  	 *
  	*/
}
