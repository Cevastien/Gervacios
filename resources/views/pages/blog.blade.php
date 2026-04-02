<x-app-layout>
    <x-slot:title>Blog — Café Gervacios</x-slot:title>

    <div class="min-h-screen lg:h-screen w-full p-3 md:p-4 lg:p-6 overflow-x-hidden lg:overflow-hidden">
        <div class="flex flex-col lg:flex-row gap-3 md:gap-4 lg:h-full">

            {{-- ── LEFT: Hero Image Panel ──────────────────────── --}}
            <div class="relative h-[40vh] md:h-[45vh] lg:h-auto lg:flex-1 overflow-hidden rounded-2xl bg-black shrink-0">
                @if($posts->isNotEmpty() && $posts->first()->image_url)
                    <img
                        src="{{ $posts->first()->image_url }}"
                        alt="Blog"
                        class="absolute inset-0 w-full h-full object-cover animate-ken-burns"
                    />
                @else
                    <img
                        src="{{ asset('images/blog/blog-hero.png') }}"
                        alt="Blog"
                        class="absolute inset-0 w-full h-full object-cover animate-ken-burns"
                    />
                @endif

                <div class="absolute inset-x-0 bottom-0 h-[381px] bg-gradient-to-t from-black to-transparent opacity-60"></div>

                <x-navbar />

                <h1 class="absolute z-10 font-forum text-[clamp(42px,8vw,112px)] leading-[1em] tracking-[0.018em] uppercase text-cream animate-fade-up
                    inset-x-0 bottom-[15%] text-center
                    md:inset-x-auto md:text-left md:bottom-10 md:left-12
                    lg:bottom-11 lg:left-[67px]">
                    Blog
                </h1>
            </div>

            {{-- ── RIGHT: Content ─────────────────────── --}}
            <div class="flex-1 flex flex-col gap-3 md:gap-4 min-h-0">

                <div class="border-0 md:border border-border-subtle rounded-2xl flex-1 flex flex-col px-4 pt-6 pb-8 md:px-12 md:pt-10 md:pb-14 lg:px-24 lg:pt-12 lg:pb-16 gap-6 md:gap-12 overflow-y-auto">

                    @php
                        $fbPageId = trim((string) (\App\Models\Setting::get('facebook_page_id') ?: \App\Models\Setting::get('fb_page_id', '')));
                        $facebookConnected = $fbPageId !== '';
                        $facebookPageUrl = $facebookConnected
                            ? 'https://www.facebook.com/' . ltrim($fbPageId, '/')
                            : (filled(config('services.facebook.page_id'))
                                ? 'https://www.facebook.com/' . ltrim((string) config('services.facebook.page_id'), '/')
                                : 'https://www.facebook.com/');
                    @endphp

                    <div class="flex items-center justify-center gap-3 md:gap-4 shrink-0 animate-fade-up" style="animation-delay: 200ms">
                        <div class="flex items-center gap-2 md:gap-3">
                            <span class="w-6 md:w-12 h-px bg-cream/20"></span>
                            <span class="w-2 h-2 md:w-2.5 md:h-2.5 border border-cream/25 rotate-45"></span>
                        </div>
                        <h2 class="font-forum text-[clamp(28px,5vw,40px)] leading-[1.2] tracking-[0.025em] uppercase text-cream">
                            Latest News
                        </h2>
                        <div class="flex items-center gap-2 md:gap-3">
                            <span class="w-2 h-2 md:w-2.5 md:h-2.5 border border-cream/25 rotate-45"></span>
                            <span class="w-6 md:w-12 h-px bg-cream/20"></span>
                        </div>
                    </div>

                    @if($posts->isEmpty())
                        <div class="flex-1 flex flex-col items-center justify-center py-10 md:py-14">
                            <div class="w-full max-w-lg rounded-2xl border border-cream/10 bg-muted-bg/60 px-6 py-10 md:px-10 md:py-12 text-center shadow-[0_8px_40px_rgba(0,0,0,0.35)]">
                                <div class="mx-auto mb-6 flex h-14 w-14 items-center justify-center rounded-full border border-cream/15 bg-cream/5">
                                    <svg class="h-7 w-7 text-cream/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.25" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </div>
                                @if (! $facebookConnected)
                                    <p class="font-satoshi text-base md:text-lg font-light leading-relaxed text-cream/85">
                                        Follow us on Facebook for the latest news.
                                    </p>
                                    <a
                                        href="{{ $facebookPageUrl }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="btn-slide-fill mt-8 inline-flex w-full items-center justify-center gap-2 rounded-lg border border-cream/30 px-6 py-4 font-satoshi text-xs tracking-[0.08em] uppercase text-cream transition-colors hover:border-cream/50 sm:w-auto sm:min-w-[240px]"
                                    >
                                        <svg class="h-4 w-4 shrink-0 text-cream/90" viewBox="0 0 256 256" fill="currentColor" aria-hidden="true">
                                            <path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm8,191.63V152h24a8,8,0,0,0,0-16H136V112a16,16,0,0,1,16-16h16a8,8,0,0,0,0-16H152a32,32,0,0,0-32,32v24H96a8,8,0,0,0,0,16h24v63.63a88,88,0,1,1,16,0Z"/>
                                        </svg>
                                        Visit our Facebook Page
                                    </a>
                                @else
                                    <p class="font-satoshi text-base md:text-lg font-light leading-relaxed text-cream/85">
                                        No posts yet — check back soon!
                                    </p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col">
                            @foreach($posts as $i => $post)
                                <a
                                    href="{{ route('blog.show', $post->id) }}"
                                    wire:navigate
                                    class="group flex flex-col sm:flex-row items-start sm:items-center gap-4 md:gap-8 py-6 md:py-8 {{ !$loop->last ? 'border-b border-border-subtle' : '' }} animate-fade-up"
                                    style="animation-delay: {{ 350 + ($i * 120) }}ms"
                                >
                                    <div class="w-full sm:w-[200px] h-[160px] sm:h-[140px] shrink-0 rounded-2xl overflow-hidden bg-dark">
                                        @if($post->image_url)
                                            <img
                                                src="{{ $post->image_url }}"
                                                alt="{{ $post->title }}"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                            />
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-cream/5">
                                                <svg class="w-10 h-10 text-cream/15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 flex flex-col justify-center gap-2 md:gap-3 min-w-0">
                                        <span class="font-satoshi text-xs tracking-[0.08em] uppercase text-[rgba(245,242,234,0.5)]">
                                            {{ $post->published_at?->format('M d, Y') ?? $post->created_at->format('M d, Y') }}
                                        </span>

                                        <h4 class="font-forum text-xl md:text-2xl leading-[1.2] tracking-[0.042em] uppercase text-cream transition-transform duration-300 group-hover:translate-x-1">
                                            {{ $post->title }}
                                        </h4>

                                        @if($post->excerpt)
                                            <p class="font-satoshi font-light text-sm leading-[1.6] text-[rgba(245,242,234,0.7)] line-clamp-2">
                                                {{ $post->excerpt }}
                                            </p>
                                        @endif
                                    </div>

                                    <span class="hidden sm:flex items-center justify-center w-10 h-10 rounded-full bg-muted-bg border border-border-subtle shrink-0 group-hover:bg-cream/10 transition-colors">
                                        <svg width="16" height="16" viewBox="0 0 32 32" fill="none">
                                            <path d="M21.7625 16.2625L17.2625 20.7625C17.228 20.797 17.1871 20.8243 17.1421 20.843C17.097 20.8616 17.0488 20.8712 17 20.8712C16.9512 20.8712 16.903 20.8616 16.8579 20.843C16.8129 20.8243 16.772 20.797 16.7375 20.7625C16.703 20.728 16.6757 20.6871 16.657 20.6421C16.6384 20.597 16.6288 20.5488 16.6288 20.5C16.6288 20.4513 16.6384 20.403 16.657 20.358C16.6757 20.3129 16.703 20.272 16.7375 20.2375L20.5938 16.375H10.5C10.4005 16.375 10.3052 16.3355 10.2348 16.2652C10.1645 16.1949 10.125 16.0995 10.125 16C10.125 15.9006 10.1645 15.8052 10.2348 15.7349C10.3052 15.6645 10.4005 15.625 10.5 15.625H20.5938L16.7375 11.7625C16.6679 11.6929 16.6288 11.5985 16.6288 11.5C16.6288 11.4016 16.6679 11.3071 16.7375 11.2375C16.8071 11.1679 16.9015 11.1288 17 11.1288C17.0985 11.1288 17.1929 11.1679 17.2625 11.2375L21.7625 15.7375C21.798 15.7714 21.8264 15.8121 21.8457 15.8572C21.865 15.9023 21.875 15.9509 21.875 16C21.875 16.0491 21.865 16.0977 21.8457 16.1428C21.8264 16.1879 21.798 16.2287 21.7625 16.2625Z" fill="#EFE7D2"/>
                                        </svg>
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>

</x-app-layout>
