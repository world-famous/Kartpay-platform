<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantDocument extends Model
{
    protected $fillable = [
                              'merchant_id',
                              'document_file_name',
                              'is_verified',
                              'verified_by_admin_id',
                          ];

    /*
  	 * Get Document Courier Class that related to Merchant (using 'merchant_document_id' column)
  	 *
  	*/
    public function documentCouriers()
    {
        return $this->hasMany(MerchantDocumentCourier::class, 'merchant_document_id', 'id');
    }
    /*
  	 * END Get Document Courier Class that related to Merchant (using 'merchant_document_id' column)
  	 *
  	*/
}
