@extends('layouts.admin')

@section('page_title', 'Add Staff')

@section('panel_heading')
    <x-admin-panel-heading title="Add Staff Account" subtitle="Create a new staff or admin user." />
@endsection

@section('content')
    <div class="mx-auto w-full max-w-3xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.staff.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full rounded-lg border-slate-300 px-3 py-2 text-sm">
                @error('name')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border-slate-300 px-3 py-2 text-sm">
                @error('email')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="mb-1 block text-sm font-medium text-slate-700">Role</label>
                <select id="role" name="role" required class="w-full rounded-lg border-slate-300 px-3 py-2 text-sm">
                    <option value="">Select a role</option>
                    <option value="staff" @selected(old('role') === 'staff')>Staff</option>
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                </select>
                @error('role')
                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-2">
                <a href="{{ route('admin.staff.index') }}"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Cancel
                </a>
                <button type="submit"
                    class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Create Account
                </button>
            </div>
        </form>
    </div>
@endsection
