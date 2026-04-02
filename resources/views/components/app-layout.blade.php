<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Café Gervacios' }}</title>
    <x-tailwind-cdn />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body
    class="bg-dark text-cream font-satoshi m-0 p-0"
    x-data
    @keydown.escape.window="$store.nav.open = false"
>

    {{-- ============================================================
         FULLSCREEN NAV OVERLAY (#1 animation: fade-in + stagger)
         ============================================================ --}}
    <div
        id="mobile-nav"
        x-show="$store.nav.open"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 w-screen h-screen flex flex-col items-center justify-center"
        style="display: none; background: #0d0b09; z-index: 9999;"
    >
        {{-- Close button (fixed top-left) --}}
        <button
            @click="$store.nav.open = false"
            style="position:fixed; top:24px; left:24px; z-index:10000;"
            class="w-[41px] h-[41px] flex items-center justify-center rounded-lg bg-muted-bg border border-border-subtle hover:bg-cream/10 transition-colors"
        >
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M12.5 3.5L3.5 12.5" stroke="#EFE7D2" stroke-width="1.2" stroke-linecap="round"/>
                <path d="M3.5 3.5L12.5 12.5" stroke="#EFE7D2" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
        </button>

        {{-- Decorative ornament (top) --}}
        <div class="mb-10 flex items-center gap-3">
            <span class="w-2.5 h-2.5 border border-cream/25 rotate-45"></span>
            <span class="w-16 h-px bg-cream/20"></span>
            <span class="w-2.5 h-2.5 border border-cream/25 rotate-45"></span>
        </div>

        {{-- Menu links (staggered entrance) --}}
        <nav style="display:flex; flex-direction:column; align-items:center; gap:1.5rem;">
            @foreach([
                ['/menu', 'Menu'],
                ['/reservation', 'Book a Table'],
                ['/about', 'About'],
                ['/contact', 'Contact'],
                ['/blog', 'Blog'],
            ] as $i => [$href, $label])
                <a
                    href="{{ $href }}"
                    wire:navigate
                    @click="$store.nav.open = false"
                    x-show="$store.nav.open"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 translate-y-8"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    style="transition-delay: {{ 100 + ($i * 80) }}ms; font-family: 'Cormorant Garamond', Georgia, serif; text-decoration: none;"
                    class="text-[clamp(36px,4.5vw,64px)] leading-none uppercase text-center text-cream hover:text-white transition-colors duration-300 py-2
                           {{ request()->is(trim($href, '/')) ? 'text-white' : '' }}"
                >
                    {{ $label }}
                </a>
            @endforeach
        </nav>

        {{-- Decorative ornament (bottom) --}}
        <div class="mt-10 flex items-center gap-3">
            <span class="w-2.5 h-2.5 border border-cream/25 rotate-45"></span>
            <span class="w-16 h-px bg-cream/20"></span>
            <span class="w-2.5 h-2.5 border border-cream/25 rotate-45"></span>
        </div>
    </div>

    {{-- ============================================================
         PAGE CONTENT (#4 / #10: cross-fade page transitions)
         ============================================================ --}}
    <main
        id="page-content"
        class="page-transition-target min-w-0 overflow-x-hidden"
    >
        {{ $slot }}
    </main>

    @livewireScripts

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('nav', {
                open: false,
                toggle() { this.open = !this.open; }
            });
        });

        // #4 / #10: Page transition hooks (wire:navigate)
        document.addEventListener('livewire:navigating', () => {
            const el = document.getElementById('page-content');
            if (el) el.classList.add('page-leaving');
        });

        document.addEventListener('livewire:navigated', () => {
            const el = document.getElementById('page-content');
            if (el) {
                el.classList.remove('page-leaving');
                el.classList.add('page-entering');
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        el.classList.remove('page-entering');
                    });
                });
            }
            initScrollReveal();
        });

        // #8 / #9: Scroll reveal (staggered fade-up for menu items, blog items)
        function initScrollReveal() {
            document.querySelectorAll('[data-scroll-reveal]').forEach((el, i) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(30px)';

                const observer = new IntersectionObserver(([entry]) => {
                    if (entry.isIntersecting) {
                        const staggerIndex = el.dataset.scrollIndex || i;
                        const delay = parseInt(staggerIndex) * 100;
                        setTimeout(() => {
                            el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                            el.style.opacity = '1';
                            el.style.transform = 'translateY(0)';
                        }, delay);
                        observer.disconnect();
                    }
                }, { threshold: 0.1 });

                observer.observe(el);
            });
        }

        // Run on initial page load
        document.addEventListener('DOMContentLoaded', () => {
            initScrollReveal();
            // Prevent stuck invisible content if Livewire navigation classes never clear
            const main = document.getElementById('page-content');
            if (main) {
                main.classList.remove('page-leaving', 'page-entering');
            }
        });
    </script>
</body>

</html>
