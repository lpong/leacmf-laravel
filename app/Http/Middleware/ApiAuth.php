<?php

namespace App\Http\Middleware;

use App\Library\Y;
use Closure;
use Illuminate\Support\Facades\Auth;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'api')
    {
        if ($request->is(...$this->except) || (Auth::guard($guard)->check() && Auth::guard($guard)->user()->status == 1)) {
            return $next($request);
        }
        return Y::json(401, '用户验证失败，请重新登录');
    }

    protected $except = [
        'v1/init',
        'v1/auth/login',
        'v1/auth/register',
        'v1/sendSms',
    ];
}
