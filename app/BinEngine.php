<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BinEngine extends Model
{
    protected $fillable = [
                              'bin_number',
                              'card_issuer',
                              'card_type',
                              'bank_name',
                              'country_name',
                              'country_code'
                          ];
}
