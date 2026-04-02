@extends('layouts.admin')

@section('page_title', 'Staff')

@section('panel_heading')
    <x-admin-panel-heading title="Staff Accounts" subtitle="Manage staff and admin access." />
@endsection

@section('content')
    <div class="mx-auto w-full max-w-7xl space-y-4">
        <div class="flex items-center justify-end">
            <a href="{{ route('admin.staff.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                <i class="fa-solid fa-user-plus text-xs"></i>
                Add Staff
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50">
                        <tr class="text-left text-xs uppercase tracking-wide text-slate-500">
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Last Login</th>
                            <th class="px-4 py-3">Created</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            <tr class="text-slate-700">
                                <td class="px-4 py-3 font-medium text-slate-900">{{ $user->name }}</td>
                                <td class="px-4 py-3">{{ $user->email }}</td>
                                <td class="px-4 py-3 capitalize">{{ $user->role }}</td>
                                <td class="px-4 py-3">
                                    @if ($user->is_active)
                                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-700">
                                            Deactivated
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-slate-600">
                                    {{ $user->last_login ? \Illuminate\Support\Carbon::parse($user->last_login)->format('M d, Y h:i A') : 'Never' }}
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $user->created_at?->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-right">
                                    <form method="POST" action="{{ route('admin.staff.force-logout', $user) }}" class="inline"
                                        x-data
                                        x-on:submit.prevent="if (confirm('Are you sure you want to force logout {{ addslashes($user->name) }} from all devices?')) { $el.submit(); }">
                                        @csrf
                                        <button type="submit"
                                            class="mr-1 rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50"
                                            @disabled(auth()->id() === $user->id)>
                                            Force Logout
                                        </button>
                                    </form>

                                    @if ($user->is_active)
                                        <form method="POST" action="{{ route('admin.staff.deactivate', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="rounded-md border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-100"
                                                @disabled(auth()->id() === $user->id)>
                                                Deactivate
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.staff.activate', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="rounded-md border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100">
                                                Activate
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-sm text-slate-500">No staff accounts found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
