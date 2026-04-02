<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset password — {{ config('app.venue_name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <x-tailwind-cdn />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans antialiased" style="font-family: Inter, system-ui, sans-serif">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="flex w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-slate-200/80">
            <div class="w-full p-8 md:p-10 lg:w-1/2">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">Reset password</h1>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800" role="alert">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.password.reset.update') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ old('email', $email) }}">

                    <div>
                        <label for="password" class="mb-1 block text-sm font-medium text-slate-700">New password</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-[#0f172a] focus:outline-none focus:ring-2 focus:ring-[#0f172a]/20">
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-1 block text-sm font-medium text-slate-700">Confirm new password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-[#0f172a] focus:outline-none focus:ring-2 focus:ring-[#0f172a]/20">
                    </div>

                    <button type="submit"
                        class="w-full rounded-lg bg-[#0f172a] py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1e293b] focus:outline-none focus:ring-2 focus:ring-[#0f172a] focus:ring-offset-2">
                        Reset Password
                    </button>
                </form>
            </div>

            <div class="relative hidden flex-col items-center justify-center bg-[#0f172a] p-10 text-center lg:flex lg:w-1/2">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(255,255,255,0.06),transparent_55%)]"></div>
                <div class="relative flex max-w-sm flex-col items-center">
                    <img src="{{ asset('images/gervacios-login-logo.png') }}"
                        alt="Gervacio's Cafe — Coffee &amp; Bakery" width="300" height="360"
                        class="h-auto w-full max-w-[300px] object-contain drop-shadow-md">
                </div>
            </div>
        </div>
    </div>
    <x-flash-toasts />
</body>
</html>
