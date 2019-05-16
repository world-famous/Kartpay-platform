<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class restApiForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                  'currency'=> 'required|alpha|max:3',
                  'merchant_order_amount' => 'required|regex:/^[0-9]+(\.[0-9]{1,2})?$/',
                  'Customer_email' => 'required|email',
                  'merchant_order_id' => 'required|max:20',
                  'customer_phone' => 'required|regex:/(91)[0-9]{10}/'                
                ];
    }
}
