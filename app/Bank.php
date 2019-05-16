<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
	protected $table = 'banks';

    protected $fillable = [
											        'bank_name', 'bank_code'
											    ];
}
