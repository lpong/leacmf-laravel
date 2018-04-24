<?php

namespace App\Http\Middleware;

use App\Library\Y;
use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Auth\Factory as Auth;


class AdminAuth
{

    /**
     * auth
     * @var
     */
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * andle an incoming request.
     * @param $request
     * @param Closure $next
     * @param $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard)
    {
        $this->auth->shouldUse($guard);

        //无需验证的，直接过
        if ($request->is(...$this->except)) {
            return $next($request);
        }

        //未登录的，登录
        if (!$this->auth->check()) {
            if ($request->isMethod('ajax')) {
                return Y::error('登录已过期，请重新登录');
            } else {
                return redirect(route('login'));
            }
        }

        //检查权限
        if (!($this->auth->user()->hasRole('super admin') || $this->auth->user()->can(Route::currentRouteName()))) {
            if ($request->isMethod('ajax')) {
                return Y::error('登录已过期，请重新登录');
            } else {
                return redirect(route('login'));
            }
        }

        //验证通过
        return $next($request);
    }

    protected $except = [
        'admin/login',
        'admin/logout'
    ];

}
