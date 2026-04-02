<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FALaravel\Facade as Google2FA;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    public function setup(Request $request): View
    {
        $user = $request->user();
        abort_if($user === null || ! in_array($user->role, ['admin', 'superadmin'], true), Response::HTTP_FORBIDDEN);

        $secret = Google2FA::generateSecretKey();
        $request->session()->put('2fa_setup_secret', $secret);

        $qrCode = Google2FA::getQRCodeInline(
            config('app.name'),
            $user->email,
            $secret
        );

        return view('admin.2fa.setup', [
            'qrCode' => $qrCode,
            'secret' => $secret,
        ]);
    }

    public function enable(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_if($user === null || ! in_array($user->role, ['admin', 'superadmin'], true), Response::HTTP_FORBIDDEN);

        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $secret = (string) $request->session()->get('2fa_setup_secret', '');
        if ($secret === '') {
            return redirect()->route('admin.2fa.setup')->withErrors([
                'otp' => 'Setup expired. Please generate a new QR code.',
            ]);
        }

        $valid = Google2FA::verifyKey($secret, $validated['otp']);
        if (! $valid) {
            return back()->withErrors([
                'otp' => 'Invalid code. Try again.',
            ]);
        }

        $user->update([
            'google2fa_secret' => $secret,
            'google2fa_enabled' => true,
        ]);

        $request->session()->forget('2fa_setup_secret');
        $request->session()->put('2fa_verified', true);
        $request->session()->forget('2fa_attempts');

        AdminLog::record('2fa_enabled', null, null, 'Two-factor authentication enabled');

        return redirect()->route('admin.settings')->with('success', '2FA enabled successfully.');
    }

    public function disable(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_if($user === null || ! in_array($user->role, ['admin', 'superadmin'], true), Response::HTTP_FORBIDDEN);

        $validated = $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Hash::check($validated['password'], (string) $user->password)) {
            return back()->withErrors([
                'password' => 'The provided password is incorrect.',
            ]);
        }

        $user->update([
            'google2fa_enabled' => false,
            'google2fa_secret' => null,
        ]);

        $request->session()->forget(['2fa_verified', '2fa_setup_secret', '2fa_attempts']);

        AdminLog::record('2fa_disabled', null, null, 'Two-factor authentication disabled');

        return redirect()->route('admin.settings')->with('success', '2FA has been disabled.');
    }

    public function showVerifyForm(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        if ($user === null || ! in_array($user->role, ['admin', 'superadmin'], true)) {
            return redirect()->route('login');
        }

        if (! $user->google2fa_enabled || empty($user->google2fa_secret)) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.2fa.verify');
    }

    public function verify(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user === null || ! in_array($user->role, ['admin', 'superadmin'], true)) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $valid = $user->google2fa_enabled
            && ! empty($user->google2fa_secret)
            && Google2FA::verifyKey((string) $user->google2fa_secret, $validated['otp']);

        if (! $valid) {
            $attempts = ((int) $request->session()->get('2fa_attempts', 0)) + 1;
            $request->session()->put('2fa_attempts', $attempts);

            if ($attempts >= 3) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Too many failed 2FA attempts.',
                ]);
            }

            return back()->withErrors([
                'otp' => 'Invalid code. Please try again.',
            ]);
        }

        $request->session()->put('2fa_verified', true);
        $request->session()->forget('2fa_attempts');

        return redirect()->intended(route('admin.dashboard'));
    }
}
