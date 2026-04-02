<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Set Your Password — {{ config('app.venue_name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <x-tailwind-cdn />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 font-sans antialiased" style="font-family: Inter, system-ui, sans-serif">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl ring-1 ring-slate-200/80">
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 md:text-3xl">Set Your Password</h1>
                <p class="mt-2 text-sm text-slate-500">You must set a new password before continuing.</p>
            </div>

            @if (session('error'))
                <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.password.change.update') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="new_password" class="mb-1 block text-sm font-medium text-slate-700">New Password</label>
                    <input type="password" id="new_password" name="new_password" autocomplete="new-password" required
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-[#0f172a] focus:outline-none focus:ring-2 focus:ring-[#0f172a]/20">
                </div>

                <div>
                    <label for="new_password_confirmation" class="mb-1 block text-sm font-medium text-slate-700">Confirm Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password" required
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-[#0f172a] focus:outline-none focus:ring-2 focus:ring-[#0f172a]/20">
                </div>

                <button type="submit"
                    class="w-full rounded-lg bg-[#0f172a] py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1e293b] focus:outline-none focus:ring-2 focus:ring-[#0f172a] focus:ring-offset-2">
                    Save New Password
                </button>
            </form>
        </div>
    </div>
    <x-flash-toasts />
</body>

</html>
