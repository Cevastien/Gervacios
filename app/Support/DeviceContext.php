<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Http\Request;

/**
 * Resolves reservation source + device type for tagging (source is always website for guests).
 */
final class DeviceContext
{
    public const SOURCE_WEBSITE = 'website';

    public const DEVICE_DESKTOP = 'desktop';

    public const DEVICE_MOBILE = 'mobile';

    public const DEVICE_TABLET = 'tablet';

    public static function fromRequest(Request $request): array
    {
        $deviceType = self::detectDeviceType($request);

        return [
            'source'      => self::SOURCE_WEBSITE,
            'device_type' => $deviceType,
        ];
    }

    public static function detectDeviceType(Request $request): string
    {
        $ua = strtolower($request->userAgent() ?? '');

        if ($ua === '') {
            return self::DEVICE_DESKTOP;
        }

        if (preg_match('/tablet|ipad|playbook|silk|(android(?!.*mobile))/i', $ua)) {
            return self::DEVICE_TABLET;
        }

        if (preg_match('/mobile|iphone|ipod|android.*mobile|windows phone|blackberry|opera mini|iemobile/i', $ua)) {
            return self::DEVICE_MOBILE;
        }

        return self::DEVICE_DESKTOP;
    }

    /**
     * Admin override: none | website | mobile | kiosk
     */
    public static function forcedExperience(Request $request): ?string
    {
        $q = $request->query('device');
        if (in_array($q, ['mobile', 'kiosk'], true)) {
            return $q;
        }
        if (in_array($q, ['desktop', 'website'], true)) {
            return 'desktop';
        }

        $session = $request->session()->get('device.force');
        if (in_array($session, ['mobile', 'kiosk'], true)) {
            return $session;
        }

        $adminForce = Setting::get('device_force_experience', '');
        if ($adminForce === 'mobile') {
            return 'mobile';
        }
        if ($adminForce === 'kiosk') {
            return 'kiosk';
        }

        return null;
    }

    public static function isMobilePhoneUa(Request $request): bool
    {
        return self::detectDeviceType($request) === self::DEVICE_MOBILE;
    }

    public static function sourceForDb(Request $request): string
    {
        $ctx = self::fromRequest($request);

        return $ctx['source'];
    }

    public static function deviceTypeForDb(Request $request): ?string
    {
        return self::fromRequest($request)['device_type'];
    }
}
