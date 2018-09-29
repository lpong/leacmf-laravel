<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;


class VerifyCsrfToken extends BaseVerifier
{


    public function handle($request, Closure $next)
    {
        return parent::addCookieToResponse($request, $next($request));
    }


    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
