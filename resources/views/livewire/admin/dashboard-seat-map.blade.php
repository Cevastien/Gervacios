<div class="flex h-full min-h-0 w-full flex-1 flex-col overflow-hidden bg-panel-canvas" data-dashboard-seat-map="1"
    data-seat-click-mode="{{ $seatClickMode }}">

    @push('scripts')
        @vite(['resources/js/seating-layout.js', 'resources/js/dashboard-seat-map-waitlist.js'])
    @endpush

    @include('admin.partials.seating-map-inner-styles')

    <style>
        [data-dashboard-seat-map] {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .dsm-root {
            display: flex;
            flex-direction: column;
            min-height: 0;
            flex: 1;
            overflow: hidden;
            background: #fff;
        }

        /* One left cluster (title + legend) + right actions — no empty flex gap in the middle */
        .dsm-toolbar-strip {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem 0.75rem;
            padding: 0.65rem 0.75rem 0.7rem;
            border-bottom: 1px solid #e2e8f0;
            background: #fff;
            flex-shrink: 0;
        }

        @media (min-width: 1024px) {
            .dsm-toolbar-strip {
                padding: 0.7rem 1rem 0.75rem;
                gap: 0.5rem 1rem;
            }
        }

        .dsm-toolbar-left {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem 0.85rem;
            min-width: 0;
            flex: 1 1 auto;
        }

        .dsm-title {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.15;
            flex-shrink: 0;
        }

        .dsm-legend-inline {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.35rem 0.45rem;
            padding-left: 0.65rem;
            border-left: 1px solid #e2e8f0;
        }

        .dsm-legend-inline .seating-legend-pill {
            padding: 2px 8px;
            font-size: 10px;
        }

        @media (max-width: 639px) {
            .dsm-legend-inline {
                border-left: none;
                padding-left: 0;
                width: 100%;
            }
        }

        .dsm-toolbar-actions {
            display: flex;
            flex-shrink: 0;
            align-items: center;
            gap: 0.35rem;
        }

        .dsm-info-popover {
            position: relative;
        }

        .dsm-info-popover-panel {
            position: absolute;
            right: 0;
            top: calc(100% + 6px);
            z-index: 60;
            width: min(22rem, calc(100vw - 2rem));
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background: #fff;
            box-shadow: 0 10px 25px -5px rgb(15 23 42 / 0.12);
            font-size: 12px;
            line-height: 1.5;
            color: #64748b;
        }

        .dsm-info-popover-panel kbd {
            display: inline-block;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 1px 5px;
            font-family: ui-monospace, monospace;
            font-size: 10px;
            color: #334155;
        }

        .dsm-info-popover-panel strong {
            color: #0f172a;
        }

        .dsm-quick-actions {
            flex-shrink: 0;
            padding: 0.45rem 0.75rem 0.55rem;
            border-bottom: 1px solid #f1f5f9;
            background: #fff;
        }

        @media (min-width: 1024px) {
            .dsm-quick-actions {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        /* Tight horizontal padding so the floor plan + scrollbar sit flush; less “dead” white on the right */
        .dsm-map-scroll {
            flex: 1 1 0%;
            min-height: 0;
            min-width: 0;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 0.35rem 0.35rem 0.75rem;
            background: #fff;
        }

        @media (min-width: 1024px) {
            .dsm-map-scroll {
                padding: 0.45rem 0.5rem 1rem;
            }
        }

        .seating-seat-dot.is-waitlist-seat-at::after {
            box-shadow:
                0 0 0 3px rgba(26, 34, 50, 0.8),
                0 2px 8px rgba(26, 34, 50, 0.2),
                0 0 0 1px rgba(26, 34, 50, 0.06);
            z-index: 2;
        }

        .seating-seat-dot.is-table-ops-selected::after {
            box-shadow:
                0 0 0 3px rgba(26, 34, 50, 0.9),
                0 2px 8px rgba(26, 34, 50, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.6) inset;
            z-index: 3;
        }
    </style>

    <div class="dsm-root">

        <div class="dsm-toolbar-strip">
            <div class="dsm-toolbar-left">
                <h3 class="dsm-title">Floor Map</h3>

                <div class="dsm-legend-inline" role="group" aria-label="Seat status legend">
                    <span class="text-[11px] font-semibold text-slate-500">Status</span>
                    <span class="seating-legend-pill seating-legend-pill--free">Free</span>
                    <span class="seating-legend-pill seating-legend-pill--reserved">Reserved</span>
                    <span class="seating-legend-pill seating-legend-pill--occupied">Occupied</span>
                </div>
            </div>

            <div class="dsm-toolbar-actions">
                @include('admin.partials.seat-focus-mode-button')
                <details class="dsm-info-popover">
                    <summary
                        class="inline-flex h-8 w-8 cursor-pointer list-none items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-colors hover:bg-slate-50 hover:border-slate-300 hover:text-slate-600 [&::-webkit-details-marker]:hidden"
                        aria-label="How to use"><i class="fa-solid fa-circle-info text-[13px]" aria-hidden="true"></i></summary>
                    <div class="dsm-info-popover-panel">
                        <p class="m-0 mb-2"><strong>Floor Map</strong> — Tap a dot to select a table; use the status bar below
                            to update status or furniture.</p>
                        <p class="m-0 mb-2 text-[11px] leading-snug text-slate-500">Dots are seat anchors on your floor
                            plan.</p>
                        <p class="m-0 mb-2"><kbd>Alt</kbd>+click a seat to assign <strong>waitlist</strong> seating to that
                            table.</p>
                        @if (auth()->user()->isAdmin())
                            <p class="m-0 text-[11px] text-slate-500">Upload, add seats, grouping, and layout tools are in
                                <strong>Edit Layout</strong>.</p>
                        @endif
                    </div>
                </details>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.seating-layout') }}"
                        class="inline-flex items-center gap-1.5 rounded-full bg-panel-primary px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition-colors hover:bg-panel-primary-hover">
                        Edit Layout
                        <i class="fa-solid fa-arrow-right text-[10px] opacity-90" aria-hidden="true"></i>
                    </a>
                @endif
            </div>
        </div>

        <div class="dsm-quick-actions">
            @livewire('admin.table-quick-actions')
        </div>

        <div class="dsm-map-scroll">
            @include('admin.partials.seating-map-inner', [
                'dashboardEmbed' => true,
                'waitlistTablePick' => true,
                'showToolbar' => false,
            ])
        </div>
    </div>
</div>
