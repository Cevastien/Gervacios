<x-app-layout>
    <x-slot:title>Menu — Café Gervacios</x-slot:title>

    @php
        $firstSlug = $categories->first()?->slug ?? '';
    @endphp

    <div class="min-h-screen lg:h-screen w-full p-3 md:p-4 lg:p-6 overflow-x-hidden lg:overflow-hidden" x-data="{
        activeTab: '{{ $firstSlug }}',
        switching: false,
        switchTab(tab) {
            if (this.activeTab === tab) return;
            this.switching = true;
            setTimeout(() => {
                this.activeTab = tab;
                this.switching = false;
                this.$nextTick(() => initScrollReveal());
            }, 250);
        }
    }">
        <div class="flex flex-col lg:flex-row gap-3 md:gap-4 lg:h-full">

            {{-- LEFT: Hero Image --}}
            <div class="relative h-[42vh] md:h-[45vh] lg:h-auto lg:w-1/2 shrink-0 overflow-hidden rounded-2xl bg-black">
                <img
                    src="{{ asset('images/menu/menu-hero.png') }}"
                    alt="Menu"
                    class="absolute inset-0 w-full h-full object-cover animate-ken-burns"
                />
                <x-navbar />
                <div class="absolute inset-x-0 bottom-0 h-[381px] bg-gradient-to-t from-black to-transparent opacity-60"></div>
                <h1 class="absolute z-10 font-forum text-[clamp(42px,8vw,112px)] leading-none tracking-[0.018em] uppercase text-cream animate-fade-up
                    inset-x-0 bottom-[15%] text-center
                    md:inset-x-auto md:text-left md:left-12 md:bottom-10
                    lg:left-[66px] lg:bottom-[56px]">
                    Menu
                </h1>
            </div>

            {{-- RIGHT: Content Panel --}}
            <div class="flex min-h-0 min-w-0 flex-1 flex-col overflow-x-hidden">
                <div class="border-0 md:border border-border-subtle rounded-2xl px-4 md:px-12 lg:px-24 pt-5 md:pt-8 pb-8 md:pb-20 flex flex-col gap-6 md:gap-16 overflow-y-auto overflow-x-hidden flex-1 min-h-0 min-w-0">

                    @if($categories->isEmpty())
                        <p class="text-center text-cream/40 py-20 font-satoshi text-lg">Menu coming soon.</p>
                    @else
                        {{-- Tabs: horizontal scroll on small screens; centered wrap from md up --}}
                        <div
                            class="-mx-4 flex flex-nowrap items-center gap-x-2.5 overflow-x-auto overflow-y-hidden px-4 pb-1 whitespace-nowrap [scrollbar-width:thin] md:mx-0 md:flex-wrap md:justify-center md:gap-x-4 md:gap-y-3 md:overflow-x-visible md:px-0 md:pb-0 md:whitespace-normal">
                            @foreach($categories as $cat)
                                <button
                                    @click="switchTab('{{ $cat->slug }}')"
                                    :class="activeTab === '{{ $cat->slug }}'
                                        ? 'border-cream/40 text-cream'
                                        : 'border-transparent text-cream/40 hover:text-cream/70'"
                                    class="shrink-0 px-3 py-1.5 md:px-5 md:py-2 rounded-lg border text-[10px] md:text-[13px] tracking-[0.08em] uppercase font-satoshi transition-all duration-300"
                                >
                                    {{ $cat->name }}
                                </button>
                            @endforeach
                        </div>

                        {{-- Tab Content --}}
                        @foreach($categories as $cat)
                            <div
                                x-show="activeTab === '{{ $cat->slug }}'"
                                x-transition:enter="transition ease-out duration-400"
                                x-transition:enter-start="opacity-0 translate-y-3"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-250"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="flex flex-col gap-6 md:gap-12"
                                :class="switching && activeTab === '{{ $cat->slug }}' ? 'opacity-0' : ''"
                            >
                                {{-- Section Header --}}
                                <div class="flex items-center justify-center gap-3 md:gap-4">
                                    <div class="flex items-center gap-2 md:gap-3">
                                        <span class="w-2 h-2 md:w-2.5 md:h-2.5 border border-cream/25 rotate-45"></span>
                                        <span class="w-6 md:w-12 h-px bg-cream/20"></span>
                                    </div>
                                    <h2 class="font-forum text-lg md:text-[32px] leading-[1.2] tracking-[0.03em] uppercase text-cream text-center">{{ $cat->name }}</h2>
                                    <div class="flex items-center gap-2 md:gap-3">
                                        <span class="w-6 md:w-12 h-px bg-cream/20"></span>
                                        <span class="w-2 h-2 md:w-2.5 md:h-2.5 border border-cream/25 rotate-45"></span>
                                    </div>
                                </div>

                                {{-- Items --}}
                                <div class="flex flex-col">
                                    @forelse($cat->items as $i => $item)
                                        <div
                                            class="group flex items-start gap-3 md:gap-5 py-3 md:py-4 {{ !$loop->last ? 'border-b border-white/[0.06]' : '' }}"
                                            data-scroll-reveal
                                            data-scroll-index="{{ $i }}"
                                        >
                                            {{-- Image: only show if uploaded, hide placeholder on mobile --}}
                                            @if($item->image)
                                                <div class="w-[50px] h-[38px] md:w-[80px] md:h-[60px] shrink-0 rounded-lg overflow-hidden bg-dark">
                                                    <img
                                                        src="{{ asset('storage/' . $item->image) }}"
                                                        alt="{{ $item->name }}"
                                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                                    />
                                                </div>
                                            @else
                                                <div class="hidden md:flex w-[80px] h-[60px] shrink-0 rounded-lg overflow-hidden bg-cream/5 items-center justify-center">
                                                    <svg class="w-6 h-6 text-cream/15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            @endif

                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-baseline gap-1">
                                                    <span class="font-forum text-[15px] md:text-lg leading-[1.2] tracking-[0.06em] uppercase text-cream transition-transform duration-300 group-hover:translate-x-0.5">{{ $item->name }}</span>
                                                    <span class="flex-1 border-b border-dotted border-cream/15 mx-1 md:mx-2 relative top-[-3px]"></span>
                                                    <span class="font-forum text-[15px] md:text-lg leading-[1.2] tracking-[0.06em] uppercase text-cream shrink-0">₱{{ number_format($item->price, 2) }}</span>
                                                </div>
                                                @if($item->description)
                                                    <p class="font-satoshi font-light text-[11px] md:text-xs leading-[1.5] text-cream/30 mt-0.5 md:mt-1 line-clamp-2">{{ $item->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center text-cream/30 py-8 font-satoshi text-sm">No items in this category yet.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
