<x-app-layout>
    <x-slot:title>Contact — Café Gervacios</x-slot:title>

    <style>
        .contact-page .contact-interactive {
            transition: transform 0.22s cubic-bezier(0.33, 1, 0.68, 1);
        }

        /* CTA: animate inner block only — transform/box-shadow on the <a> breaks corner SVG positioning */
        .contact-page .contact-interactive--cta {
            animation: contact-pulse-cta 2.6s ease-in-out infinite;
        }

        @keyframes contact-pulse-cta {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.88;
            }
        }

        .contact-page .contact-interactive--cta:hover {
            animation-play-state: paused;
            transform: translate(-2px, -2px);
        }

        .contact-page .contact-interactive--text {
            display: inline-block;
            max-width: 100%;
            animation: contact-pulse-text 2.85s ease-in-out infinite;
        }

        @keyframes contact-pulse-text {
            0%, 100% {
                opacity: 1;
                text-shadow: none;
            }
            50% {
                opacity: 0.9;
                text-shadow: 0 0 12px rgba(239, 231, 210, 0.12);
            }
        }

        .contact-page .contact-interactive--text:hover {
            animation-play-state: paused;
            transform: translateX(4px);
        }

        .contact-page .contact-interactive--tile {
            animation: contact-pulse-tile 3s ease-in-out infinite;
        }

        @keyframes contact-pulse-tile {
            0%, 100% {
                filter: brightness(1);
            }
            50% {
                filter: brightness(1.07);
            }
        }

        .contact-page .contact-interactive--tile:hover {
            animation-play-state: paused;
            transform: translateY(-4px);
        }

        @media (max-width: 639px) {
            .contact-page .contact-interactive--cta:hover,
            .contact-page .contact-interactive--text:hover,
            .contact-page .contact-interactive--tile:hover {
                transform: none;
            }
        }
    </style>

    <div class="contact-page min-h-screen lg:h-screen w-full p-3 md:p-4 lg:p-6 overflow-x-hidden lg:overflow-hidden">
        <div class="flex flex-col lg:flex-row gap-3 md:gap-4 lg:h-full lg:min-h-0">

            {{-- ── LEFT: Hero Image Panel ──────────────────────── --}}
            <div class="relative h-[40vh] md:h-[45vh] lg:h-full lg:min-h-0 lg:flex-1 overflow-hidden rounded-2xl bg-black shrink-0">
                <img
                    src="{{ asset('images/contact/contact-hero.png') }}"
                    alt="Contact"
                    class="absolute inset-0 w-full h-full object-cover animate-ken-burns"
                />

                <div class="absolute inset-x-0 bottom-0 h-[381px] bg-gradient-to-t from-black to-transparent opacity-60"></div>

                <x-navbar />

                <h1 class="absolute z-10 font-forum text-[clamp(42px,8vw,112px)] leading-[1em] tracking-[0.018em] uppercase text-cream animate-fade-up
                    inset-x-0 bottom-[15%] text-center
                    md:inset-x-auto md:text-left md:bottom-10 md:left-12
                    lg:bottom-11 lg:left-[67px]">
                    Contact
                </h1>
            </div>

            {{-- ── RIGHT: Content ─────────────────────── --}}
            <div class="flex min-h-0 min-w-0 flex-1 flex-col gap-3 overflow-x-hidden md:gap-4 lg:min-h-0 lg:overflow-hidden">

                <div class="flex min-h-0 min-w-0 flex-1 flex-col gap-3 overflow-x-hidden md:gap-4 lg:min-h-0 lg:overflow-hidden">

                    {{-- ── Row 1: Opening Hours + Instagram Grid ── --}}
                    <div class="flex-1 flex flex-col lg:flex-row gap-3 md:gap-4 lg:min-h-0 lg:flex-1 lg:basis-0">

                        {{-- Opening Hours Card --}}
                        <div class="flex-1 min-h-0 border border-border-subtle rounded-2xl pt-8 md:pt-[54px] px-6 md:px-12 pb-8 md:pb-12 lg:pt-5 lg:pb-5 lg:px-6 flex flex-col justify-start gap-5 md:gap-6 lg:gap-4 lg:overflow-hidden">

                            {{-- Symmetric header: equal flex arms so title + ornaments stay visually centered --}}
                            <div class="flex w-full shrink-0 items-center justify-center gap-2 md:gap-3 animate-fade-up" style="animation-delay: 100ms">
                                <div class="flex min-w-0 flex-1 items-center justify-end gap-2 md:gap-3">
                                    <span class="h-px w-full max-w-10 bg-cream/20 md:max-w-14"></span>
                                    <span class="h-2 w-2 shrink-0 border border-cream/25 rotate-45 md:h-2.5 md:w-2.5"></span>
                                </div>
                                <h4 class="shrink-0 text-center font-forum text-xl md:text-2xl lg:text-xl leading-[1.15] tracking-[0.042em] uppercase text-cream">
                                    Opening<br />Hours
                                </h4>
                                <div class="flex min-w-0 flex-1 items-center justify-start gap-2 md:gap-3">
                                    <span class="h-2 w-2 shrink-0 border border-cream/25 rotate-45 md:h-2.5 md:w-2.5"></span>
                                    <span class="h-px w-full max-w-10 bg-cream/20 md:max-w-14"></span>
                                </div>
                            </div>

                            {{-- Schedule: fixed grid so times share a column; dotted leaders align --}}
                            <div class="mx-auto grid w-full max-w-sm grid-cols-[max-content_1fr_max-content] gap-x-3 gap-y-2.5 md:gap-y-3">
                                @foreach([
                                    ['day' => 'Monday to Thursday',  'time' => '9AM - 10PM'],
                                    ['day' => 'Friday to Saturday',  'time' => '9AM - 11PM'],
                                    ['day' => 'Sunday',             'time' => '7AM - 10PM'],
                                ] as $i => $row)
                                    <span
                                        class="col-start-1 self-end font-satoshi font-light text-sm md:text-base text-cream animate-fade-up"
                                        style="animation-delay: {{ 200 + ($i * 80) }}ms"
                                    >{{ $row['day'] }}</span>
                                    <div
                                        class="col-start-2 self-end mb-1 min-w-[0.75rem] border-b border-dotted border-cream/20 animate-fade-up"
                                        style="animation-delay: {{ 200 + ($i * 80) }}ms"
                                        aria-hidden="true"
                                    ></div>
                                    <span
                                        class="col-start-3 self-end text-right font-satoshi font-light text-sm md:text-base tabular-nums text-cream animate-fade-up"
                                        style="animation-delay: {{ 200 + ($i * 80) }}ms"
                                    >{{ $row['time'] }}</span>
                                @endforeach
                            </div>

                        </div>

                        {{-- Instagram Grid (2×2) --}}
                        <div class="grid min-h-[200px] min-w-0 flex-1 grid-cols-2 gap-3 md:gap-4 lg:min-h-0">
                            <a
                                href="https://www.instagram.com/cafegervacios/"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="contact-interactive contact-interactive--tile relative block h-full min-h-[140px] min-w-0 overflow-hidden rounded-2xl bg-black animate-fade-in"
                                style="animation-delay: 300ms"
                            >
                                <img
                                    src="{{ asset('images/contact/contact-hero.png') }}"
                                    alt="Instagram"
                                    class="absolute inset-0 w-full h-full object-cover opacity-30"
                                />
                                <svg class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-cream" width="30" height="30" viewBox="0 0 256 256" fill="currentColor">
                                    <path d="M128,80a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160ZM176,24H80A56.06,56.06,0,0,0,24,80v96a56.06,56.06,0,0,0,56,56h96a56.06,56.06,0,0,0,56-56V80A56.06,56.06,0,0,0,176,24Zm40,152a40,40,0,0,1-40,40H80a40,40,0,0,1-40-40V80A40,40,0,0,1,80,40h96a40,40,0,0,1,40,40ZM192,76a12,12,0,1,1-12-12A12,12,0,0,1,192,76Z"/>
                                </svg>
                            </a>

                            <a
                                href="https://www.instagram.com/cafegervacios/"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="contact-interactive contact-interactive--tile block h-full min-h-[140px] min-w-0 overflow-hidden rounded-2xl bg-black animate-fade-in"
                                style="animation-delay: 450ms"
                            >
                                <img
                                    src="{{ asset('images/contact/insta-2.png') }}"
                                    alt="Instagram"
                                    class="h-full w-full min-h-[140px] object-cover"
                                />
                            </a>

                            <a
                                href="https://www.instagram.com/cafegervacios/"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="contact-interactive contact-interactive--tile block h-full min-h-[140px] min-w-0 overflow-hidden rounded-2xl bg-black animate-fade-in"
                                style="animation-delay: 600ms"
                            >
                                <img
                                    src="{{ asset('images/contact/insta-3.png') }}"
                                    alt="Instagram"
                                    class="h-full w-full min-h-[140px] object-cover"
                                />
                            </a>

                            <a
                                href="https://www.instagram.com/cafegervacios/"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="contact-interactive contact-interactive--tile block h-full min-h-[140px] min-w-0 overflow-hidden rounded-2xl bg-black animate-fade-in"
                                style="animation-delay: 750ms"
                            >
                                <img
                                    src="{{ asset('images/contact/insta-4.png') }}"
                                    alt="Instagram"
                                    class="h-full w-full min-h-[140px] object-cover"
                                />
                            </a>
                        </div>

                    </div>

                    {{-- ── Row 2: Map + Get in Touch ──────────────── --}}
                    <div class="flex-1 flex flex-col lg:flex-row gap-3 md:gap-4 lg:min-h-0 lg:flex-1 lg:basis-0">

                        <div class="relative min-h-[200px] w-full min-w-0 flex-1 overflow-hidden rounded-2xl bg-black animate-fade-in aspect-[4/3] sm:aspect-auto lg:min-h-0" style="animation-delay: 700ms">
                            <iframe
                                title="Café Gervacios on Google Maps"
                                src="https://maps.google.com/maps?q=7.0730599539499%2C125.613&amp;z=15&amp;hl=en&amp;output=embed"
                                class="absolute inset-0 h-full w-full max-w-full border-0"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                allowfullscreen
                            ></iframe>

                            <div class="absolute bottom-0 right-0 z-10 pointer-events-auto">
                                <a
                                    href="https://www.google.com/maps/dir/?api=1&amp;destination=7.0730599539499,125.613"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="relative block bg-dark rounded-tl-3xl pt-3 pl-6"
                                >
                                    <svg class="pointer-events-none absolute -left-6 bottom-0" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M24 24V0C24 13.2548 13.2548 24 0 24H24Z" fill="#0A0B0A"/></svg>
                                    <svg class="pointer-events-none absolute right-0 -top-6" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M24 24V0C24 13.2548 13.2548 24 0 24H24Z" fill="#0A0B0A"/></svg>

                                    <div class="contact-interactive contact-interactive--cta flex items-center gap-3 pr-4 pb-3">
                                        <span class="font-forum text-sm md:text-base leading-[1em] tracking-[0.063em] uppercase text-cream">Show Route</span>
                                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-muted-bg border border-border-subtle">
                                            <svg width="16" height="16" viewBox="0 0 32 32" fill="none">
                                                <path d="M21.7625 16.2625L17.2625 20.7625C17.228 20.797 17.1871 20.8243 17.1421 20.843C17.097 20.8616 17.0488 20.8712 17 20.8712C16.9512 20.8712 16.903 20.8616 16.8579 20.843C16.8129 20.8243 16.772 20.797 16.7375 20.7625C16.703 20.728 16.6757 20.6871 16.657 20.6421C16.6384 20.597 16.6288 20.5488 16.6288 20.5C16.6288 20.4513 16.6384 20.403 16.657 20.358C16.6757 20.3129 16.703 20.272 16.7375 20.2375L20.5938 16.375H10.5C10.4005 16.375 10.3052 16.3355 10.2348 16.2652C10.1645 16.1949 10.125 16.0995 10.125 16C10.125 15.9006 10.1645 15.8052 10.2348 15.7349C10.3052 15.6645 10.4005 15.625 10.5 15.625H20.5938L16.7375 11.7625C16.6679 11.6929 16.6288 11.5985 16.6288 11.5C16.6288 11.4016 16.6679 11.3071 16.7375 11.2375C16.8071 11.1679 16.9015 11.1288 17 11.1288C17.0985 11.1288 17.1929 11.1679 17.2625 11.2375L21.7625 15.7375C21.798 15.7714 21.8264 15.8121 21.8457 15.8572C21.865 15.9023 21.875 15.9509 21.875 16C21.875 16.0491 21.865 16.0977 21.8457 16.1428C21.8264 16.1879 21.798 16.2287 21.7625 16.2625Z" fill="#EFE7D2"/>
                                            </svg>
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>

                        {{-- Get in Touch Card --}}
                        <div class="flex-1 min-h-0 border border-border-subtle rounded-2xl pt-8 md:pt-[54px] px-6 md:px-12 pb-8 md:pb-12 lg:pt-5 lg:pb-5 lg:px-6 flex flex-col justify-start gap-5 md:gap-6 lg:gap-3 lg:overflow-hidden">

                            <div class="flex w-full shrink-0 items-center justify-center gap-2 md:gap-3 animate-fade-up" style="animation-delay: 800ms">
                                <div class="flex min-w-0 flex-1 items-center justify-end gap-2 md:gap-3">
                                    <span class="h-px w-full max-w-10 bg-cream/20 md:max-w-14"></span>
                                    <span class="h-2 w-2 shrink-0 border border-cream/25 rotate-45 md:h-2.5 md:w-2.5"></span>
                                </div>
                                <h4 class="shrink-0 text-center font-forum text-xl md:text-2xl lg:text-xl leading-[1.15] tracking-[0.042em] uppercase text-cream">
                                    Get in touch
                                </h4>
                                <div class="flex min-w-0 flex-1 items-center justify-start gap-2 md:gap-3">
                                    <span class="h-2 w-2 shrink-0 border border-cream/25 rotate-45 md:h-2.5 md:w-2.5"></span>
                                    <span class="h-px w-full max-w-10 bg-cream/20 md:max-w-14"></span>
                                </div>
                            </div>

                            {{-- Same max width as header block; no flex-1 stretch = no dead space below --}}
                            <div class="mx-auto w-full max-w-sm space-y-4 md:space-y-4 lg:space-y-3">
                                <div class="grid animate-fade-up grid-cols-[4.5rem_1fr] gap-x-4 gap-y-1 md:grid-cols-[5.5rem_1fr] md:gap-x-5" style="animation-delay: 900ms">
                                    <span class="font-satoshi text-xs tracking-[0.08em] uppercase text-cream pt-0.5">Address</span>
                                    <a
                                        href="https://www.google.com/maps/search/?api=1&amp;query={{ rawurlencode('Ground Floor, State Investment Building, C.M. Recto, Poblacion District, Davao City 8000') }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="contact-interactive contact-interactive--text font-satoshi font-light text-sm md:text-base lg:text-sm text-cream leading-[1.5] lg:leading-snug hover:text-white transition-colors"
                                    >Ground Floor, State Investment Building, C.M. Recto, Poblacion District, Davao City 8000</a>
                                </div>

                                <div class="grid animate-fade-up grid-cols-[4.5rem_1fr] items-center gap-x-4 md:grid-cols-[5.5rem_1fr] md:gap-x-5" style="animation-delay: 1000ms">
                                    <span class="font-satoshi text-xs tracking-[0.08em] uppercase text-cream">Email</span>
                                    <a href="mailto:cafegervacios@gmail.com" class="contact-interactive contact-interactive--text font-satoshi font-light text-sm md:text-base lg:text-sm text-cream break-all hover:text-white transition-colors">cafegervacios@gmail.com</a>
                                </div>
                            </div>

                            <div class="mx-auto w-full max-w-sm animate-fade-up pt-2 md:pt-3" style="animation-delay: 1100ms">
                                <livewire:contact-form />
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>
