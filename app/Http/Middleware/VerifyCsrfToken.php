<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/v1/track/',
        'api/v1/payments/',
        'api/v1/refunds/',
        'api/v1/refunds/*',
        'api/v1/payments/success',
        'api/v1/payments/failed',
        'api/v1/order_status',
        'restApi/*',
    ];
}
