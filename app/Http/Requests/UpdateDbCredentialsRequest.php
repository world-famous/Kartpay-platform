<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDbCredentialsRequest extends FormRequest
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
                  'username'=>'string|max:50',
                  'password'=>'min:0|max:50',
                  'database'=>'string|max:50',
                  'host'=>'string|max:50',
                  'port'=>'integer|digits_between:1,8',
              ];
    }
}
