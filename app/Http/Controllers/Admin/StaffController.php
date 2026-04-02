<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\StaffWelcomeMail;
use App\Models\AdminLog;
use App\Models\User;
use App\Rules\StrongPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->whereIn('role', ['staff', 'admin'])
            ->select('users.*')
            ->selectSub(
                AdminLog::query()
                    ->selectRaw('MAX(created_at)')
                    ->whereColumn('admin_logs.user_id', 'users.id')
                    ->where('action', 'login'),
                'last_login'
            )
            ->orderByDesc('created_at')
            ->get();

        return view('admin.staff.index', [
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        return view('admin.staff.create');
    }

    public function store(): RedirectResponse
    {
        $validated = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:staff,admin'],
        ]);

        $tempPassword = 'Cg' . random_int(10, 99) . '@' . Str::upper(Str::random(3));

        validator(
            [
                'password' => $tempPassword,
                'password_confirmation' => $tempPassword,
            ],
            [
                'password' => ['required', 'confirmed', new StrongPassword],
            ]
        )->validate();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => $tempPassword,
            'must_change_password' => true,
            'is_active' => true,
        ]);

        Mail::to($user->email)->send(new StaffWelcomeMail(
            name: $user->name,
            email: $user->email,
            temporaryPassword: $tempPassword,
            loginUrl: route('login'),
        ));

        AdminLog::record('staff.created', User::class, $user->id, "Created staff account: {$user->name}");

        return redirect()
            ->route('admin.staff.index')
            ->with('success', 'Staff account created and welcome email sent.');
    }

    public function deactivate(User $user): RedirectResponse
    {
        if (! in_array($user->role, ['staff', 'admin'], true)) {
            return back()->with('error', 'Only staff/admin accounts can be deactivated here.');
        }

        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update([
            'is_active' => false,
            'deactivated_at' => now(),
        ]);

        AdminLog::record('staff.deactivated', User::class, $user->id, "Deactivated staff account: {$user->name}");

        return back()->with('success', 'Staff account deactivated.');
    }

    public function activate(User $user): RedirectResponse
    {
        if (! in_array($user->role, ['staff', 'admin'], true)) {
            return back()->with('error', 'Only staff/admin accounts can be activated here.');
        }

        $user->update([
            'is_active' => true,
            'deactivated_at' => null,
        ]);

        AdminLog::record('staff.activated', User::class, $user->id, "Activated staff account: {$user->name}");

        return back()->with('success', 'Staff account activated.');
    }

    public function forceLogout(User $user): RedirectResponse
    {
        if (! in_array($user->role, ['staff', 'admin'], true)) {
            return back()->with('error', 'Only staff/admin accounts can be force logged out here.');
        }

        DB::table('sessions')
            ->where('user_id', $user->id)
            ->delete();

        $actorName = (string) (auth()->user()->name ?? 'Unknown');
        AdminLog::record('force_logout', User::class, $user->id, "Force logged out {$user->name} by {$actorName}");

        return back()->with('success', "{$user->name} has been logged out of all devices.");
    }
}
