<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            return $request->expectsJson() ? null : route('account.login');
        }
    }

    // protected function redirectTo(Request $request): ?string
    // {
    //     return  route('account.login');
    // }
    
}
