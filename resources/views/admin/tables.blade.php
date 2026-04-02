@extends('layouts.admin')

@section('page_title', 'Floor Map')

@section('panel_heading')
    <x-admin-panel-heading title="Floor Map" />
@endsection

@section('content')
    @php
        $showFloorMapQueueBar = auth()->user()->role === 'staff';
    @endphp

    @livewire('admin.staff-table-assignment-notifications')

    @if (auth()->user()->role !== 'staff')
        <details id="how-does-this-work"
            class="group mb-3 rounded-xl border border-slate-200 bg-white shadow-sm shadow-slate-900/[0.04] [-webkit-tap-highlight-color:transparent]">
            <summary
                class="flex cursor-pointer list-none items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-slate-800 outline-none marker:content-none [&::-webkit-details-marker]:hidden hover:bg-slate-50/80 rounded-xl">
                <span
                    class="inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full border border-slate-200 bg-slate-100 text-xs font-bold text-slate-600"
                    aria-hidden="true">?</span>
                <span>How does this work?</span>
                <i class="fa-solid fa-chevron-down ml-auto shrink-0 text-[10px] text-slate-400 transition-transform duration-200 group-open:rotate-180"
                    aria-hidden="true"></i>
            </summary>
            <div class="border-t border-slate-100 px-4 pb-4 pt-1">
                <div class="space-y-5 pt-3 text-sm leading-relaxed text-slate-600">
                    <section>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Reservations</h3>
                        <ul class="mt-2 list-disc space-y-1.5 pl-5 text-slate-600">
                            <li>Confirmed bookings are auto-assigned a table when payment is approved.</li>
                            <li>That table shows as <span class="font-medium text-slate-700">Reserved</span> on the floor map.</li>
                            <li>When the guest arrives, staff mark them as seated → <span class="font-medium text-slate-700">Occupied</span>.</li>
                            <li>After dining, mark as clean → the table returns to <span class="font-medium text-slate-700">Free</span>.</li>
                        </ul>
                    </section>
                    <section>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Walk-in queue</h3>
                        <ul class="mt-2 list-disc space-y-1.5 pl-5 text-slate-600">
                            <li>Walk-in guests join the queue via kiosk or staff registers them.</li>
                            <li>When a table is free, the system reserves it and texts the guest (when SMS is on).</li>
                            <li>When the guest arrives, staff click <span class="font-medium text-slate-700">Seat</span> on the waitlist.</li>
                            <li>The table moves from <span class="font-medium text-slate-700">Reserved</span> → <span class="font-medium text-slate-700">Occupied</span>.</li>
                        </ul>
                    </section>
                </div>
            </div>
        </details>
    @endif

    {{-- Waitlist collapsed rail: same flex stack as Seating layout “Tools” rail (needs display:flex for flex-col) --}}
    <style>
        #tc-waitlist-sidebar[data-collapsed='true'] .tc-wl-sidebar__head {
            justify-content: center;
            border-bottom: 1px solid #e8edf3;
            padding-left: 0.35rem;
            padding-right: 0.35rem;
        }

        #tc-waitlist-sidebar[data-collapsed='true'] .tc-wl-sidebar__rail {
            display: flex;
            flex: 1 1 0%;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            min-height: 0;
            padding: 0.5rem 0.15rem 0.75rem;
            overflow: hidden;
        }

        html.tc-seat-focus-mode #how-does-this-work {
            display: none !important;
        }
    </style>
    {{-- Cancel layout content pt-5; flex fills viewport; map scrolls only inside dsm-map-scroll --}}
    <div
        class="auto-table-shell -mt-1 flex min-h-0 flex-1 flex-col overflow-hidden pt-2 md:-mt-0 md:flex-row md:pt-3 -mx-5 -mb-6 md:-mx-6 md:-mb-8">
        {{-- Main: seat map (left); inset from nav sidebar so toolbar/canvas aren’t flush against it --}}
        <div
            class="flex min-h-0 min-w-0 flex-1 flex-col overflow-hidden bg-panel-canvas pl-4 md:pl-5 lg:pl-6">
            @if ($showFloorMapQueueBar)
                <div x-data="{ addQueueOpen: false }" x-on:keydown.escape.window="addQueueOpen = false"
                    class="relative flex min-h-0 min-w-0 flex-1 flex-col overflow-hidden">
                    <div class="flex items-center gap-2 flex-nowrap justify-end pr-1 md:pr-2">
                        <button type="button"
                            class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-2.5 py-1.5 text-[11px] font-semibold text-slate-800 shadow-sm transition hover:bg-slate-50"
                            x-on:click="addQueueOpen = true">
                            + Add to Queue
                        </button>
                    </div>

                    @livewire('admin.dashboard-seat-map')

                    {{-- Slide-over: Staff walk-in queue (same Livewire as Priority page) --}}
                    <div
                        x-show="addQueueOpen"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 z-[100] flex justify-end"
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="tc-floor-map-add-queue-title">
                        <div
                            class="absolute inset-0 bg-slate-900/55"
                            x-on:click="addQueueOpen = false"
                            aria-hidden="true"></div>
                        <div
                            x-transition:enter="transition transform ease-out duration-200"
                            x-transition:enter-start="translate-x-full"
                            x-transition:enter-end="translate-x-0"
                            x-transition:leave="transition transform ease-in duration-150"
                            x-transition:leave-start="translate-x-0"
                            x-transition:leave-end="translate-x-full"
                            class="relative z-10 flex h-full w-full max-w-md flex-col border-l border-slate-200 bg-panel-canvas shadow-2xl"
                            x-on:click.stop>
                            <div
                                class="flex shrink-0 items-center justify-between gap-3 border-b border-slate-200 bg-[#0f172a] px-4 py-3 text-white">
                                <h2 id="tc-floor-map-add-queue-title" class="text-lg font-semibold tracking-tight">Add to queue</h2>
                                <button
                                    type="button"
                                    class="inline-flex min-h-[44px] min-w-[44px] items-center justify-center rounded-lg border border-white/20 bg-white/10 text-white transition hover:bg-white/20"
                                    x-on:click="addQueueOpen = false"
                                    aria-label="Close">
                                    <i class="fa-solid fa-xmark text-lg" aria-hidden="true"></i>
                                </button>
                            </div>
                            <div class="min-h-0 flex-1 overflow-y-auto">
                                @livewire('staff-walk-in-queue')
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @livewire('admin.dashboard-seat-map')
            @endif
        </div>

        {{-- Right: collapsible waitlist --}}
        <aside id="tc-waitlist-sidebar"
            x-data="{ waitlistOpen: true }"
            x-init="waitlistOpen = document.getElementById('tc-waitlist-sidebar').getAttribute('data-collapsed') !== 'true'; const t = document.getElementById('tc-wl-collapse-toggle'); if (t) t.addEventListener('click', () => { setTimeout(() => { waitlistOpen = document.getElementById('tc-waitlist-sidebar').getAttribute('data-collapsed') !== 'true' }, 0); });"
            class="tc-wl-sidebar flex min-h-0 shrink-0 flex-col overflow-hidden border-b border-panel-stroke bg-panel-canvas transition-all duration-300 ease-out md:border-b-0 md:border-l"
            style="width: 320px" data-collapsed="false" aria-label="Waitlist sidebar">
            <div
                class="tc-wl-sidebar__head flex shrink-0 items-center justify-between gap-2 border-b border-panel-stroke bg-panel-canvas px-3 py-2 md:px-4">
                <span class="tc-wl-sidebar__title text-[13px] font-bold text-slate-900">Waitlist</span>
                <button type="button" id="tc-wl-collapse-toggle"
                    class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:bg-slate-50"
                    title="Collapse sidebar" aria-expanded="true" aria-controls="tc-wl-sidebar-body">
                    {{-- Panel sits on the right: chevron points right to collapse toward the edge --}}
                    <i class="fa-solid fa-angles-right text-xs" aria-hidden="true"></i>
                </button>
            </div>
            <div id="tc-wl-sidebar-body" class="tc-wl-sidebar__body min-h-0 flex-1 overflow-y-auto overflow-x-hidden">
                @livewire('admin.waitlist-panel')
            </div>
            <div id="tc-wl-sidebar-rail"
                class="tc-wl-sidebar__rail hidden min-h-0 min-w-0 flex-1 flex-col items-center justify-center gap-4 overflow-hidden px-0.5 py-2 md:px-1 md:py-3">
                <button type="button"
                    class="p-2 text-gray-500 hover:text-gray-800"
                    @click="document.getElementById('tc-wl-collapse-toggle').click(); setTimeout(() => { waitlistOpen = document.getElementById('tc-waitlist-sidebar').getAttribute('data-collapsed') !== 'true' }, 0)">
                    <span x-show="!waitlistOpen">»</span>
                    <span x-show="waitlistOpen">«</span>
                </button>
                <span id="tc-wl-queue-badge"
                    class="tc-wl-sidebar__rail-badge inline-flex min-h-7 min-w-7 shrink-0 items-center justify-center rounded-full bg-panel-primary px-1.5 text-[11px] font-bold leading-none text-white tabular-nums shadow-sm"
                    title="Guests waiting in queue">0</span>
            </div>
        </aside>
    </div>

    <script>
        (function () {
            var KEY = 'tc_auto_table_wl_sidebar_collapsed';
            var sidebar = document.getElementById('tc-waitlist-sidebar');
            var toggle = document.getElementById('tc-wl-collapse-toggle');
            var body = document.getElementById('tc-wl-sidebar-body');
            var rail = document.getElementById('tc-wl-sidebar-rail');
            var head = sidebar ? sidebar.querySelector('.tc-wl-sidebar__head') : null;
            var titleEl = sidebar ? sidebar.querySelector('.tc-wl-sidebar__title') : null;
            if (!sidebar || !toggle || !body || !rail) return;

            function syncQueueBadge() {
                var root = document.querySelector('[data-waitlist-root]');
                var badge = document.getElementById('tc-wl-queue-badge');
                if (root && badge) {
                    var n = root.getAttribute('data-queue-total');
                    badge.textContent = n != null && n !== '' ? String(n) : '0';
                }
            }
            function applyCollapsed(collapsed) {
                sidebar.setAttribute('data-collapsed', collapsed ? 'true' : 'false');
                sidebar.style.width = collapsed ? '64px' : '320px';
                toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                if (head) {
                    head.classList.toggle('justify-center', collapsed);
                    head.classList.toggle('justify-between', !collapsed);
                }
                body.classList.toggle('hidden', collapsed);
                rail.classList.toggle('hidden', !collapsed);
                if (titleEl) titleEl.style.display = collapsed ? 'none' : '';
                var icon = toggle.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-angles-right', !collapsed);
                    icon.classList.toggle('fa-angles-left', collapsed);
                }
                toggle.setAttribute('title', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
                try {
                    localStorage.setItem(KEY, collapsed ? '1' : '0');
                } catch (e) { }
                syncQueueBadge();
                requestAnimationFrame(function () {
                    window.dispatchEvent(new Event('resize'));
                });
            }

            toggle.addEventListener('click', function () {
                applyCollapsed(sidebar.getAttribute('data-collapsed') !== 'true');
            });

            try {
                if (localStorage.getItem(KEY) === '1') {
                    applyCollapsed(true);
                } else {
                    applyCollapsed(false);
                }
            } catch (e2) {
                applyCollapsed(false);
            }

            document.addEventListener('DOMContentLoaded', syncQueueBadge);
            document.addEventListener('livewire:init', function () {
                if (typeof Livewire === 'undefined' || !Livewire.hook) return;
                Livewire.hook('morph.updated', function () {
                    syncQueueBadge();
                });
            });
        })();
    </script>

    @include('admin.partials.seat-focus-mode-script')
@endsection
