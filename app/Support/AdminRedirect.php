<?php

namespace App\Support;

/**
 * Validates post-login redirect targets for /admin/* — same-origin paths only, prevents open redirects.
 */
final class AdminRedirect
{
    /**
     * Accept only internal paths under /admin/ (excluding /admin/login).
     */
    public static function safePath(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        if (str_contains($path, '://') || str_starts_with($path, '//')) {
            return null;
        }

        if (! str_starts_with($path, '/admin/')) {
            return null;
        }

        if (str_starts_with($path, '/admin/login')) {
            return null;
        }

        return $path;
    }
}
