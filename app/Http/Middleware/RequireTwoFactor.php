<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireTwoFactor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            return $next($request);
        }

        if (! in_array($user->role, ['admin', 'superadmin'], true)) {
            return $next($request);
        }

        if (
            $request->routeIs('admin.2fa.verify')
            || $request->routeIs('admin.2fa.verify.submit')
            || $request->routeIs('admin.password.change')
            || $request->routeIs('admin.password.change.update')
        ) {
            return $next($request);
        }

        if ($user->google2fa_enabled === true && $request->session()->get('2fa_verified') !== true) {
            return redirect()->route('admin.2fa.verify');
        }

        return $next($request);
    }
}
