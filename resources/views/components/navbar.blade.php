<nav class="absolute z-30 flex items-center justify-between lg:justify-start bg-dark rounded-xl
    top-4 left-4 right-4 gap-2 px-2 py-1.5
    lg:top-12 lg:left-12 lg:right-auto lg:w-auto lg:gap-3 lg:p-2">
    {{-- Hamburger --}}
    <button @click="$store.nav.toggle()"
        aria-label="Open navigation menu"
        :aria-expanded="$store.nav.open ? 'true' : 'false'"
        aria-controls="mobile-nav"
        class="flex items-center justify-center w-9 h-9 lg:w-[41px] lg:h-[41px] rounded-lg bg-muted-bg border border-border-subtle hover:bg-cream/10 transition-colors shrink-0">
        <svg width="18" height="12" viewBox="0 0 20 14" fill="none" class="lg:w-5 lg:h-3.5">
            <rect y="0" width="20" height="1" fill="#EFE7D2" />
            <rect y="6" width="20" height="1" fill="#EFE7D2" />
            <rect y="12" width="20" height="1" fill="#EFE7D2" />
        </svg>
    </button>

    {{-- Logo: centered on mobile only; inline on desktop --}}
    <a href="/" wire:navigate
        class="flex items-center absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 lg:static lg:left-auto lg:top-auto lg:translate-x-0 lg:translate-y-0">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 15 320 120" class="h-[18px] sm:h-[21px] lg:h-[38px] w-auto">
            <text x="160" y="38" text-anchor="middle" font-family="'Dancing Script', cursive" font-size="22"
                font-weight="400" fill="#EFE7D2" letter-spacing="1">cafe</text>
            <line x1="88" y1="32" x2="118" y2="32" stroke="#EFE7D2" stroke-width="0.8" opacity="0.6" />
            <line x1="202" y1="32" x2="232" y2="32" stroke="#EFE7D2" stroke-width="0.8" opacity="0.6" />
            <text x="160" y="82" text-anchor="middle" font-family="'Cormorant Garamond', Georgia, serif" font-size="52"
                font-weight="400" fill="#EFE7D2" letter-spacing="4">GERVACIOS</text>
            <circle cx="118" cy="104" r="2" fill="#EFE7D2" opacity="0.5" />
            <circle cx="202" cy="104" r="2" fill="#EFE7D2" opacity="0.5" />
            <ellipse cx="160" cy="104" rx="7" ry="5" fill="none" stroke="#EFE7D2" stroke-width="1" opacity="0.8" />
            <path d="M156 104 Q160 101 164 104" fill="none" stroke="#EFE7D2" stroke-width="0.8" opacity="0.8" />
            <text x="130" y="124" text-anchor="middle" font-family="'Jost', sans-serif" font-size="9" font-weight="300"
                fill="#EFE7D2" letter-spacing="3" opacity="0.75">COFFEE</text>
            <text x="192" y="124" text-anchor="middle" font-family="'Jost', sans-serif" font-size="9" font-weight="300"
                fill="#EFE7D2" letter-spacing="3" opacity="0.75">BAKERY</text>
        </svg>
    </a>

    {{-- Mobile only: Book a Table (ghost) --}}
    <a href="/reservation" wire:navigate
        class="lg:hidden shrink-0 rounded-lg border border-border-subtle bg-transparent px-2 py-2 font-satoshi text-[9px] sm:text-[10px] font-normal tracking-[0.08em] uppercase text-cream hover:bg-muted-bg hover:border-cream/25 transition-colors whitespace-nowrap">
        Book a Table
    </a>

    {{-- Desktop / website: Menu, About, Book --}}
    <div class="hidden lg:flex items-center gap-1">
        <a href="/menu" wire:navigate
            class="px-3 py-3 rounded-lg text-xs font-normal tracking-[0.08em] uppercase text-cream hover:bg-muted-bg transition-colors">
            Menu
        </a>
        <a href="/about" wire:navigate
            class="px-3 py-3 rounded-lg text-xs font-normal tracking-[0.08em] uppercase text-cream hover:bg-muted-bg transition-colors">
            About
        </a>
        <a href="/reservation" wire:navigate
            class="px-3 py-3 rounded-lg text-xs font-normal tracking-[0.08em] uppercase text-cream bg-muted-bg border border-border-subtle hover:bg-cream/10 transition-colors">
            Book a Table
        </a>
    </div>
</nav>