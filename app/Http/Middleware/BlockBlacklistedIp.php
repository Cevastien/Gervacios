<?php

namespace App\Http\Middleware;

use App\Models\BlockedIp;
use App\Models\Setting;
use App\Support\IpBlocklist;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockBlacklistedIp
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = ltrim($request->path(), '/');
        if (str_starts_with($path, 'webhook/')) {
            return $next($request);
        }

        if (app()->environment('local')) {
            return $next($request);
        }

        if (Setting::get('automation_ip_block_enabled', '1') !== '1') {
            return $next($request);
        }

        $ip = $request->ip();
        if (IpBlocklist::isExemptFromBlocking($ip)) {
            return $next($request);
        }

        if ($ip && BlockedIp::where('ip_address', $ip)->exists()) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}
