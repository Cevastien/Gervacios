<x-app-layout>
    <x-slot:title>About — Café Gervacios</x-slot:title>

    <div class="min-h-screen lg:h-screen w-full p-3 md:p-4 lg:p-6 overflow-x-hidden lg:overflow-hidden">
        <div class="flex flex-col lg:flex-row gap-3 md:gap-4 lg:h-full lg:min-h-0">

            {{-- ── LEFT: Hero Image Panel ──────────────────────── --}}
            <div class="relative h-[40vh] md:h-[45vh] lg:h-full lg:min-h-0 lg:w-1/2 shrink-0 overflow-hidden rounded-2xl bg-black">
                <img
                    src="{{ asset('images/about/about-hero.png') }}"
                    alt="About"
                    class="absolute inset-0 w-full h-full object-cover animate-ken-burns"
                />
                <div class="absolute inset-x-0 bottom-0 h-[381px] bg-gradient-to-t from-black to-transparent opacity-60"></div>
                <x-navbar />
                <h1 class="absolute z-10 font-forum text-[clamp(42px,8vw,112px)] leading-[1em] tracking-[0.018em] uppercase text-cream animate-fade-up
                    inset-x-0 bottom-[15%] text-center
                    md:inset-x-auto md:text-left md:bottom-10 md:left-12
                    lg:bottom-11 lg:left-[67px]">
                    About
                </h1>
            </div>

            {{-- ── RIGHT: fills viewport on lg (no inner scroll); mobile stacks & page scrolls ── --}}
            <div class="flex-1 flex flex-col gap-3 md:gap-4 lg:gap-2 lg:min-h-0 lg:overflow-hidden rounded-2xl min-h-0">

                {{-- ── Row 1: Text Card + Restaurant Photo ──── --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 md:min-h-[280px] lg:min-h-0 lg:flex-1 lg:basis-0 lg:grid-rows-[minmax(0,1fr)]">
                    <div class="border border-border-subtle rounded-2xl p-6 md:p-10 lg:p-5 lg:py-4 flex flex-col justify-center gap-4 md:gap-6 lg:gap-3 overflow-hidden min-h-0 lg:h-full">
                        <h3 class="font-forum text-2xl font-bold leading-[1.2] tracking-[0.031em] uppercase text-cream animate-fade-up" style="animation-delay: 200ms">
                            Culinary Artistry Redefined
                        </h3>
                        <p class="font-satoshi font-light text-sm leading-[1.6] text-cream animate-fade-up" style="animation-delay: 350ms">
                            Where culinary craftsmanship meets warm hospitality. Enjoy exceptional coffee, freshly baked pastries, and comforting meals in the heart of Davao City, Philippines.
                        </p>
                    </div>
                    <div class="overflow-hidden rounded-2xl bg-black animate-fade-in-right h-[200px] md:h-auto lg:h-full lg:min-h-0" style="animation-delay: 400ms;">
                        <img src="{{ asset('images/about/about-restaurant.png') }}" alt="Restaurant interior" class="w-full h-full min-h-0 object-cover" />
                    </div>
                </div>

                {{-- ── Row 2: Award Cards ───────────────────── --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4 lg:gap-4 lg:shrink-0">
                    @foreach([
                        ['title' => 'Trip Advisor',  'sub' => "Best Restaurant\nDavao"],
                        ['title' => 'Michelin Guide','sub' => "Best Restaurant\nDavao"],
                        ['title' => 'Star Dining',   'sub' => "Best Restaurant\nDavao"],
                    ] as $i => $award)
                        <div
                            class="border border-border-subtle rounded-2xl py-5 md:py-6 px-4 lg:p-4 flex flex-col items-center gap-2 lg:gap-1 animate-fade-up"
                            style="animation-delay: {{ 500 + ($i * 100) }}ms"
                        >
                            <div class="flex items-center gap-1">
                                @for($s = 0; $s < 5; $s++)
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#EFE7D2">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="font-forum text-lg md:text-xl leading-[1.2] tracking-[0.042em] uppercase text-cream text-center">
                                {{ $award['title'] }}
                            </span>
                            <span class="font-satoshi text-xs tracking-[0.08em] uppercase text-center leading-[1.3] text-[rgba(245,242,234,0.7)] whitespace-pre-line">{{ $award['sub'] }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- ── Row 3: Chef Photo + Our Story ────────── --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4 md:min-h-[280px] lg:min-h-0 lg:flex-1 lg:basis-0 lg:grid-rows-[minmax(0,1fr)]">
                    <div class="overflow-hidden rounded-2xl bg-black animate-fade-in-left h-[200px] md:h-auto lg:h-full lg:min-h-0" style="animation-delay: 800ms;">
                        <img src="{{ asset('images/about/about-chef.png') }}" alt="Our Chef" class="w-full h-full min-h-0 object-cover" />
                    </div>
                    <div class="border border-border-subtle rounded-2xl p-6 md:p-10 lg:p-5 lg:py-4 flex flex-col justify-center gap-4 md:gap-6 lg:gap-3 overflow-hidden text-left min-h-0 lg:h-full">
                        {{-- Left-aligned with body copy (matches “Culinary Artistry” card above) --}}
                        <h4 class="font-forum text-xl font-semibold leading-[1.2] tracking-[0.031em] uppercase text-cream animate-fade-up" style="animation-delay: 1000ms">
                            Our Story
                        </h4>
                        <p class="font-satoshi font-light text-sm leading-[1.6] text-cream animate-fade-up" style="animation-delay: 1200ms">
                            Founded with a passion for culinary excellence, Café Gervacios began in the heart of Davao City, Philippines. Over the years, it grew into a beloved destination for food enthusiasts, celebrated for its exceptional coffee, freshly baked goods, and warm hospitality that keeps guests coming back.
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>

</x-app-layout>
