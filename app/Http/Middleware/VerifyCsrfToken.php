<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'api/*',
        'payment/notfiy/*',
        'exam/*',
        'save_exam',
        'inquiry/upload'
    ];
}
