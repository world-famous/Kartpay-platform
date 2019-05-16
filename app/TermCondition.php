<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TermCondition extends Model
{
    protected $fillable = [
                              'message',
                              'content'
                          ];
}
