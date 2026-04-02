<?php

namespace App\Http\Middleware;

use App\Support\AdminRedirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminNotFound
{
    /**
     * Guests are redirected to /admin/login?redirect=<requested path>.
     * Authenticated users continue to {@see AdminRole} (403 if role is insufficient).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            $requested = $request->getRequestUri();
            $safe = AdminRedirect::safePath($requested);

            if ($safe !== null) {
                return redirect()->route('login', ['redirect' => $safe]);
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
