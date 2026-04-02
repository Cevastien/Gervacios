<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\StrongPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordChangeController extends Controller
{
    public function showChangeForm()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'new_password' => ['required', 'string', 'min:8', 'confirmed', new StrongPassword],
        ]);

        $user = $request->user();
        if ($user === null) {
            return redirect()->route('login');
        }

        $user->password = Hash::make($validated['new_password']);
        $user->must_change_password = false;
        $user->password_changed_at = now();
        $user->save();

        DB::table('sessions')
            ->where('user_id', auth()->id())
            ->where('id', '!=', session()->getId())
            ->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Password updated successfully.');
    }
}
