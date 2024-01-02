<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use App\Enums\UserType;

class ShareUserInfo
{
    public function handle($request, Closure $next)
    {
        if (auth()->check() && auth()->user()->user_type == UserType::SystemUser) {
            View::share('userInfo', auth()->user()->only('id', 'name', 'email', 'user_type'));
        } else {
            View::share('userInfo', null);
        }

        return $next($request);
    }
}