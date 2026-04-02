@extends('layouts.admin')

@section('page_title', 'Profile & Settings')

@section('panel_heading')
    <x-admin-panel-heading title="Profile & Settings" />
@endsection

@section('content')
    <div class="mx-auto w-full max-w-lg">
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm md:p-5">
            @if (session('success'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('staff.profile.update') }}" class="space-y-6">
                @csrf

                <section class="space-y-4">
                    <h2 class="text-sm font-semibold text-slate-900">Account Info</h2>

                    <div>
                        <label for="name" class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-500">Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                            class="min-h-11 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-0">
                        @error('name')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-500">Email</label>
                        <input type="text" value="{{ $user->email }}" readonly
                            class="min-h-11 w-full rounded-lg border border-slate-200 bg-slate-100 px-3 py-2 text-sm text-slate-500">
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-500">Role</label>
                        <span class="inline-flex min-h-11 items-center rounded-full bg-slate-100 px-3 text-sm font-semibold text-slate-700">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-500">Member since</label>
                        <p class="min-h-11 rounded-lg border border-slate-200 bg-slate-50 px-3 py-3 text-sm text-slate-700">
                            {{ optional($user->created_at)->format('F j, Y') }}
                        </p>
                    </div>
                </section>

                <section class="space-y-4 border-t border-slate-200 pt-5">
                    <h2 class="text-sm font-semibold text-slate-900">Change Password</h2>

                    <div>
                        <label for="current_password" class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-500">Current password</label>
                        <input id="current_password" name="current_password" type="password"
                            class="min-h-11 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-0">
                    </div>

                    <div>
                        <label for="password" class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-500">New password</label>
                        <input id="password" name="password" type="password"
                            class="min-h-11 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-0">
                        @error('password')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1 block text-xs font-medium uppercase tracking-wide text-slate-500">Confirm new password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="min-h-11 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-0">
                    </div>

                    <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center rounded-lg bg-slate-900 px-4 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Save
                    </button>
                </section>

                <section class="space-y-3 border-t border-slate-200 pt-5">
                    <h2 class="text-sm font-semibold text-slate-900">Security Info</h2>

                    <div class="flex items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-3">
                        <span class="text-sm text-slate-700">2FA enabled</span>
                        <span
                            class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->google2fa_enabled ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                            {{ $user->google2fa_enabled ? 'Yes' : 'No' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-3">
                        <span class="text-sm text-slate-700">Must change password</span>
                        <span
                            class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold {{ $user->must_change_password ? 'bg-amber-100 text-amber-700' : 'bg-slate-200 text-slate-700' }}">
                            {{ $user->must_change_password ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection
