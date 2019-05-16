<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantBankDetail extends Model
{
    protected $fillable = [
                              'merchant_id',
                              'bank_id',
                              'account_number',
                              'bank_isfc_code',
                          ];

    /*
  	 * Get Bank Class that related to Merchant (using 'bank_id' column)
  	 *
  	*/
    public function bank()
    {
        return $this->belongsTo('App\Bank', 'bank_id', 'id');
    }
    /*
  	 * END Get Bank Class that related to Merchant (using 'bank_id' column)
  	 *
  	*/
}
