<x-app-layout>
    <x-slot:title>Café Gervacios</x-slot:title>

    <div class="min-h-screen lg:h-screen w-full px-0 pt-3 pb-0 overflow-x-hidden lg:overflow-hidden lg:p-6">
        <div class="flex flex-col lg:flex-row h-auto lg:h-full min-h-0 gap-0 lg:gap-4">

            {{-- LEFT: Hero Image Section — lg:h-full so absolute children have a real box (lg:h-auto + only absolute kids = broken layout) --}}
            <div class="relative isolate mx-3 h-[55vh] shrink-0 min-h-0 overflow-hidden rounded-2xl bg-black lg:mx-0 lg:h-full lg:min-h-0 lg:flex-1">
                <img
                    src="{{ asset('images/home/hero.png') }}"
                    alt="Café Gervacios"
                    class="absolute inset-0 z-0 h-full w-full object-cover animate-ken-burns"
                />

                <x-navbar />

                {{-- Desktop QR + socials: inset clears rounded-2xl + overflow-hidden clip at bottom-right --}}
                <div class="absolute bottom-6 right-5 z-20 flex flex-col items-center gap-3 lg:bottom-14 lg:right-12">
                    <div class="hidden flex-col items-center gap-2 pointer-events-none select-none lg:flex">
                        <x-qr-mobile-card class="shrink-0" />
                        <p class="max-w-[12rem] text-center font-satoshi text-[10px] leading-snug tracking-[0.02em] text-cream/70 sm:text-xs sm:leading-relaxed">
                            Scan to view our menu on your phone.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                    <a href="https://www.instagram.com/cafegervacios/" target="_blank" rel="noopener noreferrer" aria-label="Follow us on Instagram" class="flex items-center justify-center w-10 h-10 rounded-full border border-cream/30 hover:border-cream/70 transition-colors">
                        <svg width="18" height="18" viewBox="0 0 256 256" fill="#EFE7D2">
                            <path d="M128,80a48,48,0,1,0,48,48A48.05,48.05,0,0,0,128,80Zm0,80a32,32,0,1,1,32-32A32,32,0,0,1,128,160ZM176,24H80A56.06,56.06,0,0,0,24,80v96a56.06,56.06,0,0,0,56,56h96a56.06,56.06,0,0,0,56-56V80A56.06,56.06,0,0,0,176,24Zm40,152a40,40,0,0,1-40,40H80a40,40,0,0,1-40-40V80A40,40,0,0,1,80,40h96a40,40,0,0,1,40,40ZM192,76a12,12,0,1,1-12-12A12,12,0,0,1,192,76Z"/>
                        </svg>
                    </a>
                    <a href="https://web.facebook.com/cafegervacios/" target="_blank" rel="noopener noreferrer" aria-label="Follow us on Facebook" class="flex items-center justify-center w-10 h-10 rounded-full border border-cream/30 hover:border-cream/70 transition-colors">
                        <svg width="18" height="18" viewBox="0 0 256 256" fill="#EFE7D2">
                            <path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm8,191.63V152h24a8,8,0,0,0,0-16H136V112a16,16,0,0,1,16-16h16a8,8,0,0,0,0-16H152a32,32,0,0,0-32,32v24H96a8,8,0,0,0,0,16h24v63.63a88,88,0,1,1,16,0Z"/>
                        </svg>
                    </a>
                    <a href="https://www.tripadvisor.com.ph/Restaurant_Review-g298459-d27939235-Reviews-Cafe_Gervacios-Davao_City_Davao_del_Sur_Province_Mindanao.html" target="_blank" rel="noopener noreferrer" aria-label="View us on TripAdvisor" class="flex items-center justify-center w-10 h-10 rounded-full border border-cream/30 hover:border-cream/70 transition-colors">
                        <svg width="20" height="20" viewBox="0 0 32 32" fill="#EFE7D2">
                            <path d="M16 4.8c-3.2 0-6.1.9-8.4 2.4H5.2l2.6 2.2A7.2 7.2 0 0 0 3.2 15.2a7.2 7.2 0 0 0 7.2 7.2c1.6 0 3-.5 4.2-1.3L16 23.2l1.4-2.1a7.15 7.15 0 0 0 4.2 1.3 7.2 7.2 0 0 0 7.2-7.2 7.2 7.2 0 0 0-4.6-5.8l2.6-2.2h-2.4A14.7 14.7 0 0 0 16 4.8Zm-5.6 6a5.2 5.2 0 1 1 0 10.4 5.2 5.2 0 0 1 0-10.4Zm11.2 0a5.2 5.2 0 1 1 0 10.4 5.2 5.2 0 0 1 0-10.4Zm-11.2 2a3.2 3.2 0 1 0 0 6.4 3.2 3.2 0 0 0 0-6.4Zm11.2 0a3.2 3.2 0 1 0 0 6.4 3.2 3.2 0 0 0 0-6.4Zm-11.2 1.6a1.6 1.6 0 1 1 0 3.2 1.6 1.6 0 0 1 0-3.2Zm11.2 0a1.6 1.6 0 1 1 0 3.2 1.6 1.6 0 0 1 0-3.2Z"/>
                        </svg>
                    </a>
                    </div>
                </div>

                <div class="pointer-events-none absolute inset-x-0 bottom-0 z-10 h-1/2 bg-gradient-to-t from-black to-transparent opacity-60"></div>

                {{-- Title: mobile bottom-left + clamp; desktop unchanged --}}
                <h1 class="absolute z-20 font-forum leading-none tracking-[0.02em] uppercase text-cream animate-fade-up [text-shadow:0_2px_24px_rgba(0,0,0,0.75)]
                    left-5 bottom-6 text-left text-[clamp(44px,12vw,80px)]
                    lg:text-[clamp(60px,7.3vw,140px)] lg:left-[77px] lg:bottom-[52px]">
                    A Taste<br>of Davao
                </h1>
            </div>

            {{-- Side cards: mobile = full width stack 50vh, no gap; desktop unchanged --}}
            <div class="flex w-full min-h-0 flex-col gap-0 lg:h-full lg:w-[420px] lg:shrink-0 lg:gap-[15px] lg:overflow-visible">

                @foreach([
                    ['href' => '/menu', 'img' => 'grid-menu.png', 'alt' => 'Menu', 'label' => 'Menu'],
                    ['href' => '/reservation', 'img' => 'grid-reservation.png', 'alt' => 'Book a Table', 'label' => 'Book a Table'],
                    ['href' => '/about', 'img' => 'grid-restaurant.png', 'alt' => 'About', 'label' => 'About'],
                ] as $card)
                    <a
                        href="{{ $card['href'] }}"
                        wire:navigate
                        class="group relative block max-lg:h-[50vh] w-full shrink-0 overflow-hidden lg:h-auto lg:min-h-0 lg:flex-1 lg:rounded-tl-2xl lg:rounded-tr-2xl lg:rounded-bl-2xl lg:rounded-br-none card-hover-zoom"
                    >
                        <img
                            src="{{ asset('images/home/' . $card['img']) }}"
                            alt="{{ $card['alt'] }}"
                            class="card-bg absolute inset-0 h-full w-full object-cover transition-[filter,transform,opacity] duration-300 ease-out active:brightness-110 lg:opacity-70 lg:duration-700"
                        />

                        {{-- Mobile: darken overlay rgba(0,0,0,0.25) --}}
                        <div class="pointer-events-none absolute inset-0 z-[1] bg-black/25 lg:hidden" aria-hidden="true"></div>

                        {{-- Desktop: bottom gradient --}}
                        <div class="absolute inset-x-0 bottom-0 z-[1] hidden h-1/2 bg-gradient-to-t from-black/50 to-transparent lg:block" aria-hidden="true"></div>

                        {{-- Label: corner design — desktop unchanged; mobile gets balanced padding + centering --}}
                        <div class="absolute bottom-0 right-0 z-10">
                            <div class="relative rounded-tl-3xl bg-dark pt-3 pl-6 max-md:px-5 max-md:py-[10px]">
                                <svg class="absolute -left-6 bottom-0" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M24 24V0C24 13.2548 13.2548 24 0 24H24Z" fill="#0A0B0A"/></svg>
                                <svg class="absolute right-0 -top-6" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M24 24V0C24 13.2548 13.2548 24 0 24H24Z" fill="#0A0B0A"/></svg>
                                <div class="card-label-group flex items-center justify-end gap-3 max-md:justify-center max-md:gap-2.5">
                                    <span class="font-forum text-base tracking-[0.06em] uppercase text-cream whitespace-nowrap">{{ $card['label'] }}</span>
                                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border border-border-subtle bg-muted-bg transition-colors group-hover:bg-cream/10">
                                        <svg width="16" height="16" viewBox="0 0 32 32" fill="none">
                                            <path d="M21.7625 16.2625L17.2625 20.7625C17.228 20.797 17.1871 20.8243 17.1421 20.843C17.097 20.8616 17.0488 20.8712 17 20.8712C16.9512 20.8712 16.903 20.8616 16.8579 20.843C16.8129 20.8243 16.772 20.797 16.7375 20.7625C16.703 20.728 16.6757 20.6871 16.657 20.6421C16.6384 20.597 16.6288 20.5488 16.6288 20.5C16.6288 20.4513 16.6384 20.403 16.657 20.358C16.6757 20.3129 16.703 20.272 16.7375 20.2375L20.5938 16.375H10.5C10.4005 16.375 10.3052 16.3355 10.2348 16.2652C10.1645 16.1949 10.125 16.0995 10.125 16C10.125 15.9006 10.1645 15.8052 10.2348 15.7349C10.3052 15.6645 10.4005 15.625 10.5 15.625H20.5938L16.7375 11.7625C16.6679 11.6929 16.6288 11.5985 16.6288 11.5C16.6288 11.4016 16.6679 11.3071 16.7375 11.2375C16.8071 11.1679 16.9015 11.1288 17 11.1288C17.0985 11.1288 17.1929 11.1679 17.2625 11.2375L21.7625 15.7375C21.798 15.7714 21.8264 15.8121 21.8457 15.8572C21.865 15.9023 21.875 15.9509 21.875 16C21.875 16.0491 21.865 16.0977 21.8457 16.1428C21.8264 16.1879 21.798 16.2287 21.7625 16.2625Z" fill="#EFE7D2"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach

            </div>
        </div>
    </div>
</x-app-layout>
