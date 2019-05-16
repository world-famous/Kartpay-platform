<?php

/**
 * Created by PhpStorm.
 * User: mark
 * Date: 4/4/17
 * Time: 8:16 PM
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SendSMSFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'SendSMS';
    }
}