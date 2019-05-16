<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
                              'transaction_id',
                              'access_key',
                              'merchant_id',
                              'refund_amount',
                              'status',
                              'approved_at',
                              'user_id',
                              'reason',
                          ];

    protected $dates = [
                            'approved_at',
                        ];

    /*
  	 * Get Transaction Class that related to Refund
  	 *
  	*/
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    /*
  	 * END Get Transaction Class that related to Refund
  	 *
  	*/

    /*
  	 * Get Access Key Class that related to Refund (using 'merchant_id')
  	 *
  	*/
    public function access_code()
    {
        return $this->belongsTo(AccessKey::class, 'merchant_id', 'merchant_id');
    }
    /*
  	 * END Get Access Key Class that related to Refund (using 'merchant_id')
  	 *
  	*/

    /*
  	 * Set Encrypted Access Key Attribute
  	 *
  	*/
    public function setAccessKeyAttribute($value)
    {
        return $this->attributes['access_key'] = encrypt($value);
    }
    /*
  	 * END Set Encrypted Access Key Attribute
  	 *
  	*/

    /*
  	 * Get Descrypt Access Key Attribute
  	 *
  	*/
    public function getAccessKeyAttribute($value)
    {
        return decrypt($value);
    }
    /*
  	 * END Get Descrypt Access Key Attribute
  	 *
  	*/
}
