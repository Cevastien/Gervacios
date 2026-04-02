<x-app-layout>
    <x-slot:title>Book a Table — Café Gervacios</x-slot:title>

    <div class="min-h-screen lg:h-screen w-full p-3 md:p-4 lg:p-6 overflow-x-hidden lg:overflow-hidden bg-dark">
        <div class="flex flex-col lg:flex-row gap-3 md:gap-4 lg:h-full lg:min-h-0">

            {{-- ── LEFT: Hero Image Panel ──────────────────────── --}}
            <div class="relative h-[40vh] md:h-[45vh] lg:h-full lg:min-h-0 lg:flex-1 overflow-hidden rounded-2xl bg-black shrink-0">
                <img
                    src="{{ asset('images/reservation/reservation-hero.png') }}"
                    alt="Book a Table"
                    class="absolute inset-0 w-full h-full object-cover animate-ken-burns"
                />

                <div class="absolute inset-x-0 bottom-0 h-[381px] bg-gradient-to-t from-black to-transparent opacity-60"></div>

                <x-navbar />

                <h1 class="absolute z-10 font-forum text-[clamp(42px,8vw,112px)] leading-[1em] tracking-[0.018em] uppercase text-cream animate-fade-up
                    inset-x-0 bottom-[15%] text-center
                    md:inset-x-auto md:text-left md:bottom-10 md:left-12
                    lg:bottom-14 lg:left-16">
                    Book<br>a Table
                </h1>
            </div>

            {{-- ── RIGHT: fits viewport on lg (no inner scroll on step 1) ── --}}
            <div class="flex min-h-0 min-w-0 flex-1 flex-col gap-3 overflow-x-hidden md:gap-4 lg:min-h-0 lg:overflow-hidden">

                <div class="flex min-h-0 min-w-0 flex-1 flex-col items-center justify-center gap-8 overflow-x-hidden rounded-2xl border-0 border-border-subtle px-4 py-8 md:border md:gap-12 md:px-12 md:py-16 lg:gap-4 lg:justify-center lg:overflow-y-auto lg:px-12 lg:py-5">

                    {{-- Header Section --}}
                    <div class="flex flex-col items-center gap-3 md:gap-4 lg:gap-2 shrink-0">

                        <div class="flex items-center gap-3 md:gap-4 lg:gap-2">
                            <div class="flex items-center gap-2 md:gap-3 animate-fade-in" style="animation-delay: 300ms">
                                <span class="w-6 md:w-12 h-px bg-cream/20"></span>
                                <span class="w-2 h-2 md:w-2.5 md:h-2.5 border border-cream/25 rotate-45"></span>
                            </div>

                            <h2 class="font-forum text-[clamp(28px,5vw,40px)] lg:text-[clamp(22px,2.2vw,32px)] leading-[1.2] tracking-[0.025em] uppercase text-cream animate-fade-down" style="animation-delay: 100ms">
                                Book a Table
                            </h2>

                            <div class="flex items-center gap-2 md:gap-3 animate-fade-in" style="animation-delay: 300ms">
                                <span class="w-2 h-2 md:w-2.5 md:h-2.5 border border-cream/25 rotate-45"></span>
                                <span class="w-6 md:w-12 h-px bg-cream/20"></span>
                            </div>
                        </div>

                        <p class="max-w-[500px] text-center font-satoshi font-light text-sm md:text-lg lg:text-sm leading-[1.5] lg:leading-snug text-cream/70 animate-fade-up px-2" style="animation-delay: 400ms">
                            Secure your spot at Café Gervacios, where exceptional cuisine and a remarkable dining experience await.
                        </p>

                    </div>

                    <div class="flex w-full min-w-0 justify-center min-h-0">
                        <livewire:reservation-form />
                    </div>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>
