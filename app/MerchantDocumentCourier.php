<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantDocumentCourier extends Model
{
    protected $fillable = [
                              'merchant_document_id',
                              'courier_id',
                              'courier_tracking_id',
                          ];
}
