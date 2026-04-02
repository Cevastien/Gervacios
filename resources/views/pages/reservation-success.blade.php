<x-app-layout>
    <x-slot:title>Reservation Confirmed — Café Gervacios</x-slot:title>

    <div class="min-h-screen lg:h-screen w-full p-3 md:p-4 lg:p-6 overflow-x-hidden lg:overflow-hidden">
        <div class="flex flex-col lg:flex-row gap-3 md:gap-4 lg:h-full">

            {{-- LEFT: Hero — celebratory emerald wash (success-only) --}}
            <div
                class="relative h-[35vh] shrink-0 overflow-hidden rounded-2xl bg-black ring-1 ring-emerald-500/35 md:h-[40vh] lg:h-auto lg:flex-1">
                <img
                    src="{{ asset('images/reservation/reservation-hero.png') }}"
                    alt="Reservation Confirmed"
                    class="absolute inset-0 h-full w-full object-cover animate-ken-burns"
                />
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-emerald-900/45 via-emerald-950/20 to-black/50">
                </div>
                <div
                    class="pointer-events-none absolute inset-x-0 bottom-0 h-[381px] bg-gradient-to-t from-emerald-950/80 via-black/40 to-transparent opacity-90">
                </div>
                <x-navbar />
                <p
                    class="absolute left-1/2 top-8 z-10 -translate-x-1/2 rounded-full border border-emerald-400/35 bg-emerald-950/60 px-4 py-1.5 font-satoshi text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-200/95 shadow-[0_0_24px_rgba(16,185,129,0.25)] backdrop-blur-sm md:left-12 md:translate-x-0 lg:top-10 lg:left-16">
                    You’re in
                </p>
                <h1
                    class="animate-fade-up absolute inset-x-0 bottom-[15%] z-10 text-center font-forum text-[clamp(36px,7vw,80px)] uppercase leading-[1em] tracking-[0.018em] text-emerald-50 drop-shadow-[0_4px_32px_rgba(16,185,129,0.45)] md:inset-x-auto md:bottom-10 md:left-12 md:text-left lg:bottom-14 lg:left-16">
                    Thank<br>You
                </h1>
            </div>

            {{-- RIGHT: Confirmation — emerald panel & accents (Livewire: pending / paid / timeout) --}}
            <div class="flex min-h-0 flex-1 flex-col">
                <livewire:reservation-success :booking-ref="request('ref')" wire:key="reservation-success-{{ request('ref') ?: 'none' }}" />
            </div>

        </div>
    </div>
</x-app-layout>
