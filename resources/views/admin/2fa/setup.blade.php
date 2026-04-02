@extends('layouts.admin')

@section('page_title', '2FA Setup')

@section('panel_heading')
    <x-admin-panel-heading title="Two-Factor Authentication Setup" subtitle="Scan this QR code with Google Authenticator, then verify to enable." />
@endsection

@section('content')
    <div class="mx-auto w-full max-w-3xl rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="grid gap-6 md:grid-cols-[220px,1fr]">
            <div class="rounded-lg border border-slate-200 p-3">
                <img src="{{ $qrCode }}" alt="2FA QR Code" class="h-auto w-full">
            </div>
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-slate-800">Manual setup key</p>
                    <p class="mt-1 rounded-md bg-slate-100 px-3 py-2 font-mono text-sm text-slate-900">{{ $secret }}</p>
                </div>

                <form method="POST" action="{{ route('admin.2fa.enable') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label for="otp" class="mb-1 block text-sm font-medium text-slate-700">Enter 6-digit code</label>
                        <input id="otp" name="otp" type="text" inputmode="numeric" maxlength="6" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <button type="submit"
                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Enable 2FA
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
