<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordLogs extends Model
{
    protected $fillable = [
                          	'user',
                          	'password'
                          ];
}
