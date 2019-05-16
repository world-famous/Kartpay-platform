<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class AccessKey extends Authenticatable
{
    protected $fillable = [
                              'type', 'merchant_id', 'access_key',
                          ];

    /*
     * Get Access Key attributes
     *
    */
    public function getAccessKeyAttribute($value)
    {
        $decryptVal = $value;
        try
        {
            $decryptVal = decrypt($value);
        }
        catch (\Exception $e)
        {

        }

        return $decryptVal;
    }
    /*
     * END Get Access Key attributes
     *
    */

    /*
     * Set Access Key attributes
     *
    */
    public function setAccessKeyAttribute($value)
    {
        $this->attributes['access_key'] = encrypt($value);
    }
    /*
     * END Set Access Key attributes
     *
    */
}
