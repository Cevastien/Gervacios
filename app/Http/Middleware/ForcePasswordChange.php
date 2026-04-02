<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (
            $user !== null
            && $user->must_change_password === true
            && ! $request->routeIs('admin.password.change')
            && ! $request->routeIs('admin.password.change.update')
            && ! $request->routeIs('logout')
        ) {
            return redirect()
                ->route('admin.password.change')
                ->with('error', 'Please set a new password to continue.');
        }

        return $next($request);
    }
}
