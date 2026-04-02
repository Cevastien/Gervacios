{{-- Unified top bar: menu, brand, optional actions — same navy as sidebar --}}
@php
    $venueName = config('app.venue_name');
    $user = auth()->user();
    $panelLabel = $user && $user->isStaff() ? 'Staff panel' : 'Admin panel';
@endphp

<header
    class="admin-header-shell shrink-0 border-b border-white/10 bg-panel-primary text-white shadow-md md:relative md:z-30">
    <div
        class="admin-header-inner flex w-full flex-wrap items-center justify-between gap-x-2 gap-y-2 px-3 py-2 sm:gap-x-3 md:gap-x-4 md:px-4">
        {{-- Left: sidebar toggle + venue --}}
        <div class="flex min-w-0 shrink-0 items-center gap-2.5 sm:gap-3">
            <button type="button" id="admin-sidebar-toggle" aria-expanded="true" aria-controls="admin-sidebar-nav"
                class="admin-header-menu-btn" title="Toggle navigation">
                <span class="sr-only">Toggle navigation</span>
                <i class="fa-solid fa-bars text-[13px]" aria-hidden="true"></i>
            </button>
            <div class="admin-header-brand min-w-0">
                <p class="truncate font-bold leading-tight tracking-tight text-white sm:text-[15px]">{{ $venueName }}</p>
                <p class="mt-0.5 text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400">{{ $panelLabel }}</p>
            </div>
        </div>

        <div class="flex shrink-0 flex-wrap items-center justify-end gap-1.5 sm:gap-2">
            @hasSection('header_actions')
                @yield('header_actions')
            @endif

            @if (auth()->user()->role === 'staff')
                <a href="{{ route('staff.profile') }}"
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-700 text-sm font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </a>
            @endif
        </div>
    </div>
</header>
