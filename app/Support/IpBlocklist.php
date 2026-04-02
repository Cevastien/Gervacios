<?php

namespace App\Support;

/**
 * Loopback / local dev IPs must never be blocked by the global IP deny list
 * (prevents locking yourself out on 127.0.0.1 / ::1).
 */
final class IpBlocklist
{
    public static function isExemptFromBlocking(?string $ip): bool
    {
        if ($ip === null || $ip === '') {
            return false;
        }

        if ($ip === '127.0.0.1' || $ip === '::1') {
            return true;
        }

        // IPv4-mapped IPv6 loopback, e.g. ::ffff:127.0.0.1
        if (str_starts_with($ip, '::ffff:') && str_contains($ip, '127.0.0.1')) {
            return true;
        }

        // RFC1918 private IPv4 (LAN / dev) — never block (avoids locking yourself out on Wi‑Fi)
        $v4 = str_starts_with($ip, '::ffff:') ? substr($ip, 7) : $ip;
        if (filter_var($v4, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            if (filter_var($v4, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE) === false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  iterable<string>  $ips
     * @return list<string>
     */
    public static function filterStorable(iterable $ips): array
    {
        $out = [];
        foreach ($ips as $ip) {
            $ip = trim((string) $ip);
            if ($ip === '' || !filter_var($ip, FILTER_VALIDATE_IP)) {
                continue;
            }
            if (self::isExemptFromBlocking($ip)) {
                continue;
            }
            $out[] = $ip;
        }

        return array_values(array_unique($out));
    }
}
