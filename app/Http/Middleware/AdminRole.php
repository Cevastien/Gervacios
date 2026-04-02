<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Authenticated users must have admin, staff, or superadmin role.
 * Returns 403 (not 404) when the user is logged in but lacks access.
 */
class AdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, ['admin', 'staff', 'superadmin'], true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Forbidden. Admin or staff access required.',
                ], 403);
            }

            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
