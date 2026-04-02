<x-app-layout>
    <x-slot:title>{{ $post->title }} — Café Gervacios</x-slot:title>

    <div class="min-h-screen lg:h-screen w-full p-3 md:p-4 lg:p-6 overflow-x-hidden lg:overflow-hidden">
        <div class="flex flex-col lg:flex-row gap-3 md:gap-4 lg:h-full">

            {{-- ── LEFT: Hero Image Panel ──────────────────────── --}}
            <div class="relative h-[40vh] md:h-[45vh] lg:h-auto lg:flex-1 overflow-hidden rounded-2xl bg-black shrink-0">
                @if($post->image_url)
                    <img
                        src="{{ $post->image_url }}"
                        alt="{{ $post->title }}"
                        class="absolute inset-0 w-full h-full object-cover animate-ken-burns"
                    />
                @else
                    <img
                        src="{{ asset('images/blog/blog-hero.png') }}"
                        alt="{{ $post->title }}"
                        class="absolute inset-0 w-full h-full object-cover animate-ken-burns"
                    />
                @endif

                <div class="absolute inset-x-0 bottom-0 h-[381px] bg-gradient-to-t from-black to-transparent opacity-60"></div>

                <x-navbar />
            </div>

            {{-- ── RIGHT: Article ─────────────────────── --}}
            <div class="flex-1 flex flex-col gap-3 md:gap-4 min-h-0">

                <div class="border-0 md:border border-border-subtle rounded-2xl flex-1 overflow-y-auto px-4 py-8 md:px-12 md:py-14 lg:px-24 lg:py-16">
                    <article class="max-w-[600px] mx-auto flex flex-col gap-6 md:gap-10">

                        <span class="font-satoshi text-xs tracking-[0.08em] uppercase text-[rgba(245,242,234,0.5)] animate-fade-in">
                            {{ $post->published_at?->format('M d, Y') ?? $post->created_at->format('M d, Y') }}
                        </span>

                        <h1 class="font-forum text-[clamp(28px,5vw,40px)] leading-[1.15] tracking-[0.025em] uppercase text-cream animate-fade-up" style="animation-delay: 200ms">
                            {{ $post->title }}
                        </h1>

                        <div class="flex items-center justify-center gap-3 animate-fade-in" style="animation-delay: 400ms">
                            <span class="w-12 h-px bg-cream/20"></span>
                            <span class="w-2.5 h-2.5 border border-cream/25 rotate-45"></span>
                            <span class="w-12 h-px bg-cream/20"></span>
                        </div>

                        @if($post->content)
                            @foreach(preg_split('/\n{2,}/', trim($post->content)) as $pi => $paragraph)
                                @if(trim($paragraph))
                                    <p
                                        class="font-satoshi font-light text-sm md:text-base leading-[1.7] text-[rgba(245,242,234,0.8)]"
                                        data-scroll-reveal
                                        data-scroll-index="{{ $pi }}"
                                    >
                                        {!! nl2br(e(trim($paragraph))) !!}
                                    </p>
                                @endif
                            @endforeach
                        @endif

                        @if($post->fb_url)
                            <a
                                href="{{ $post->fb_url }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 font-satoshi text-xs tracking-[0.08em] uppercase text-cream/40 hover:text-cream/70 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                View on Facebook
                            </a>
                        @endif

                        <a
                            href="{{ route('blog') }}"
                            wire:navigate
                            class="inline-flex items-center gap-2 font-satoshi text-xs tracking-[0.08em] uppercase text-cream/60 hover:text-cream transition-colors mt-4 animate-fade-in"
                            style="animation-delay: 800ms"
                        >
                            <svg width="16" height="16" viewBox="0 0 32 32" fill="none" class="rotate-180">
                                <path d="M21.7625 16.2625L17.2625 20.7625C17.228 20.797 17.1871 20.8243 17.1421 20.843C17.097 20.8616 17.0488 20.8712 17 20.8712C16.9512 20.8712 16.903 20.8616 16.8579 20.843C16.8129 20.8243 16.772 20.797 16.7375 20.7625C16.703 20.728 16.6757 20.6871 16.657 20.6421C16.6384 20.597 16.6288 20.5488 16.6288 20.5C16.6288 20.4513 16.6384 20.403 16.657 20.358C16.6757 20.3129 16.703 20.272 16.7375 20.2375L20.5938 16.375H10.5C10.4005 16.375 10.3052 16.3355 10.2348 16.2652C10.1645 16.1949 10.125 16.0995 10.125 16C10.125 15.9006 10.1645 15.8052 10.2348 15.7349C10.3052 15.6645 10.4005 15.625 10.5 15.625H20.5938L16.7375 11.7625C16.6679 11.6929 16.6288 11.5985 16.6288 11.5C16.6288 11.4016 16.6679 11.3071 16.7375 11.2375C16.8071 11.1679 16.9015 11.1288 17 11.1288C17.0985 11.1288 17.1929 11.1679 17.2625 11.2375L21.7625 15.7375C21.798 15.7714 21.8264 15.8121 21.8457 15.8572C21.865 15.9023 21.875 15.9509 21.875 16C21.875 16.0491 21.865 16.0977 21.8457 16.1428C21.8264 16.1879 21.798 16.2287 21.7625 16.2625Z" fill="currentColor"/>
                            </svg>
                            Back to Blog
                        </a>

                    </article>
                </div>

            </div>

        </div>
    </div>

</x-app-layout>
