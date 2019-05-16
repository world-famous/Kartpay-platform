<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtpGenerator extends Model
{
    protected $fillable = [
                              'contact_no',
                              'otp',
                              'is_valid',
                          ];
}
