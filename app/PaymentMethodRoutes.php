<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethodRoutes extends Model
{
    protected $fillable = [
                          	'payment_method_id',
                            'route',
                            'status'
                          ];

    /*
  	 * Get Routes Class that related to PaymentMethod
  	 *
  	*/
    public static function getRoutes($payment_method, $payment_type='')
    {
        $result = self::leftJoin('payment_methods', 'payment_methods.id', '=', 'payment_method_routes.payment_method_id')
        ->where('payment_methods.status', true)
        ->where('payment_method', $payment_method)
        ->where('payment_method_routes.status', true);

        if($payment_type)
        {
            $result->where('payment_methods.payment_type', $payment_type);
        }
        $data = $result->get();
        return $data;
    }
    /*
  	 * END Get Routes Class that related to PaymentMethod
  	 *
  	*/
}
