<x-app-layout>
    <x-slot:title>Payment Failed — Café Gervacios</x-slot:title>

    <div class="min-h-screen w-full overflow-x-hidden p-3 md:p-4 lg:h-screen lg:overflow-hidden lg:p-6">
        <div class="flex h-full flex-col gap-3 md:gap-4 lg:flex-row lg:h-full">

            {{-- LEFT: Hero — amber / warning wash (failure-only; distinct from success emerald) --}}
            <div
                class="relative h-[35vh] shrink-0 overflow-hidden rounded-2xl bg-neutral-950 ring-1 ring-amber-500/40 md:h-[40vh] lg:h-auto lg:flex-1">
                <img src="{{ asset('images/reservation/reservation-hero.png') }}" alt="Café interior"
                    class="absolute inset-0 h-full w-full object-cover opacity-80 grayscale-[0.15] animate-ken-burns" />
                <div
                    class="pointer-events-none absolute inset-0 bg-gradient-to-br from-amber-950/70 via-amber-950/35 to-black/60">
                </div>
                <div
                    class="pointer-events-none absolute inset-x-0 bottom-0 h-[320px] bg-gradient-to-t from-amber-950/90 via-black/50 to-transparent">
                </div>
                <x-navbar />
                <div
                    class="absolute left-1/2 top-8 z-10 flex -translate-x-1/2 items-center gap-2 rounded-lg border border-amber-500/40 bg-amber-950/70 px-3 py-2 font-satoshi text-[10px] font-bold uppercase tracking-[0.18em] text-amber-100 shadow-[0_0_28px_rgba(245,158,11,0.2)] backdrop-blur-sm md:left-10 md:translate-x-0 lg:top-10 lg:left-14">
                    <span class="inline-flex h-1.5 w-1.5 rounded-full bg-amber-400 shadow-[0_0_8px_rgba(251,191,36,0.9)]"
                        aria-hidden="true"></span>
                    Action needed
                </div>
                <h1
                    class="animate-fade-up absolute inset-x-0 bottom-[15%] z-10 text-center font-forum text-[clamp(36px,7vw,80px)] uppercase leading-[1em] tracking-[0.018em] text-amber-50 drop-shadow-[0_4px_28px_rgba(180,83,9,0.55)] md:inset-x-auto md:bottom-10 md:left-10 md:text-left lg:bottom-14 lg:left-14">
                    Payment<br />Failed
                </h1>
            </div>

            {{-- RIGHT: Failure panel — amber border, alert strip, strong retry CTA --}}
            <div class="flex min-h-0 flex-1 flex-col">
                <div
                    class="flex flex-1 flex-col items-stretch justify-center rounded-2xl border-0 md:border md:border-amber-500/30 md:bg-[radial-gradient(ellipse_100%_70%_at_50%_0%,rgba(245,158,11,0.12),transparent_50%)] md:shadow-[0_0_0_1px_rgba(245,158,11,0.12),0_28px_80px_-28px_rgba(180,83,9,0.35)]">
                    <div
                        class="border-b border-amber-500/25 bg-amber-500/10 px-4 py-3 text-center md:rounded-t-2xl md:px-8">
                        <p class="font-satoshi text-sm font-semibold text-amber-100">Payment not completed</p>
                        <p class="mt-0.5 font-satoshi text-xs text-amber-200/70">No charge was finalized — your booking is
                            not confirmed yet.</p>
                    </div>

                    <div
                        class="flex flex-col items-center gap-6 px-4 py-8 md:gap-8 md:px-12 md:py-12 lg:px-20 lg:py-16">
                        <div
                            class="animate-fade-up flex h-16 w-16 items-center justify-center rounded-full border-2 border-amber-500/55 bg-amber-500/10 shadow-[0_8px_28px_rgba(0,0,0,0.35)] ring-4 ring-amber-600/20 md:h-20 md:w-20">
                            <svg class="h-8 w-8 text-amber-300 md:h-10 md:w-10" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2.25" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>

                        <h2 class="animate-fade-up text-center font-forum text-[clamp(24px,4vw,36px)] uppercase leading-[1.2] tracking-[0.025em] text-amber-100"
                            style="animation-delay: 80ms">
                            Payment Unsuccessful
                        </h2>

                        <p class="animate-fade-up max-w-md text-center font-satoshi text-sm leading-relaxed text-amber-100/75"
                            style="animation-delay: 140ms">
                            Your payment could not be processed. Your reservation has not been confirmed. Please try
                            again, or contact us if the problem continues.
                        </p>

                        @if (request('ref'))
                            <div class="animate-fade-up w-full max-w-md rounded-xl border border-amber-500/25 bg-amber-950/25 px-6 py-4 text-center shadow-inner md:px-8"
                                style="animation-delay: 200ms">
                                <p class="mb-1 font-satoshi text-xs uppercase tracking-wider text-amber-200/55">Reference
                                </p>
                                <p class="break-all font-forum text-xl tracking-wider text-amber-50 md:text-2xl">
                                    {{ request('ref') }}</p>
                            </div>
                        @endif

                        <div class="animate-fade-up flex w-full max-w-md flex-col gap-3 sm:flex-row sm:gap-4"
                            style="animation-delay: 260ms">
                            <a href="/reservation" wire:navigate
                                class="inline-flex flex-1 items-center justify-center rounded-xl bg-amber-500 px-6 py-3.5 text-center font-satoshi text-xs font-semibold uppercase tracking-[0.1em] text-neutral-950 shadow-lg shadow-amber-900/40 transition-colors hover:bg-amber-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-400 focus-visible:ring-offset-2 focus-visible:ring-offset-[#0d0b09]">
                                Try again
                            </a>
                            <a href="/" wire:navigate
                                class="inline-flex flex-1 items-center justify-center rounded-xl border border-amber-500/35 bg-transparent px-6 py-3.5 text-center font-satoshi text-xs font-semibold uppercase tracking-[0.1em] text-amber-100/90 transition-colors hover:border-amber-400/50 hover:bg-amber-500/10">
                                Back to home
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
