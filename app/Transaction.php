<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $primaryKey = 'kartpay_id';

    protected $fillable = [
                              'merchant_id',
                              'access_key',
                              'currency',
                              'order_id',
                              'order_amount',
                              'customer_email',
                              'customer_phone',
                              'success_url',
                              'failed_url',
                              'status',
                              'encryption',
                              'kartpay_id',
                              'language',
                              'payment_option',
                          ];

    protected $dates = [
                            'paid_at',
                        ];

    protected $hidden = [
                            'merchant_id',
                            'access_key',
                        ];

    public function getMerchantIdAttribute($value)
    {
        try
        {
            $value = decrypt($value);
        }
        catch (Exception $e)
        {
            $value             = encrypt($value);
            $this->merchant_id = $value;
            $this->save();
        }

        return $value;
    }

    public function setAccessKeyattribute($value)
    {
        $this->attributes['access_key'] = encrypt($value);
    }

    public function getAccessKeyattribute($value)
    {
        try
        {
            $value = decrypt($value);
        }
        catch (Exception $e)
        {
            $value            = encrypt($value);
            $this->access_key = $value;
            $this->save();
        }

        return $value;
    }

    /*
  	 * Get Billing Address from Transaction which type = 'billing'
  	 *
  	*/
    public function billing_address()
    {
        return $this->hasOne(Address::class)
            ->where('type', 'billing');
    }
    /*
  	 * END Get Billing Address from Transaction which type = 'billing'
  	 *
  	*/

    /*
  	 * Get Shipping Address from Transaction which type = 'shipping'
  	 *
  	*/
    public function shipping_address()
    {
        return $this->hasOne(Address::class)
            ->where('type', 'shipping');
    }
    /*
  	 * END Get Shipping Address from Transaction which type = 'shipping'
  	 *
  	*/

    /*
  	 * Get Access Key from Transaction (using 'merchant_id')
  	 *
  	*/
    public function access_code()
    {
        return $this->belongsTo(AccessKey::class, 'merchant_id', 'merchant_id');
    }
    /*
  	 *  END Get Access Key from Transaction (using 'merchant_id')
  	 *
  	*/

    /*
  	 * Get Refund Class from Transaction
  	 *
  	*/
    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }
    /*
  	 * END Get Refund Class from Transaction
  	 *
  	*/

    /*
  	 * Get Refund Class from Transaction where already approved
  	 *
  	*/
    public function approved_refunds()
    {
        return $this->hasMany(Refund::class)->whereNotNull('approved_at');
    }
    /*
  	 * END Get Refund Class from Transaction where already approved
  	 *
  	*/

    /*
  	 * Get SUM of Refund Amount on Transaction
  	 *
  	*/
    public function getRefundableAmountAttribute()
    {
        $amount = $this->order_amount;
        $refunds = $this->approved_refunds->sum('refund_amount');
        return $amount - $refunds;
    }
    /*
  	 * END Get SUM of Refund Amount on Transaction
  	 *
  	*/
}
