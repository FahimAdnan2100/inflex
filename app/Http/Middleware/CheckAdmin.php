<?php

namespace App\Http\Middleware;

use App\Enums\UserType;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{

    public function handle(Request $request, Closure $next)
    {

        if (Auth::check() && auth()->user()->user_type == UserType::SystemUser) {
            return $next($request);
        }

        return redirect('/dashboard');
    }
}
