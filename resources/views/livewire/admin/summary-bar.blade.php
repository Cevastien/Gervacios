@php
    $hasPriority = $priorityWaiting > 0;
    /** Primary dark + muted — data viz on light cards (no generic dashboard blue) */
    $accent = '#0f172a';
    $accentDark = '#1e293b';
@endphp

{{-- shrink-0: prevents this block from collapsing in the parent flex column --}}
<div wire:poll.5s class="flex shrink-0 flex-col gap-4">

    <style>
        /* Dashboard — white cards on soft canvas; charts use primary dark ink */
        .dash-saas-card,
        .dash-channel-card,
        .dash-chart-card {
            font-family: inherit;
        }

        .dash-kpi-value,
        .dash-chart-title {
            font-family: inherit;
        }

        .tc-dash-card {
            transition: box-shadow 0.28s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.22s ease,
                transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .tc-dash-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 24px rgba(15, 23, 42, 0.08), 0 1px 2px rgba(15, 23, 42, 0.04);
            transform: translateY(-2px);
        }

        @media (prefers-reduced-motion: reduce) {
            .tc-dash-card {
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .tc-dash-card:hover {
                transform: none;
            }

            .bar-col-bar {
                transition: opacity 0.15s ease;
            }
        }

        .dash-saas-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 4px 12px rgba(15, 23, 42, 0.06);
            padding: 18px 20px;
        }

        .dash-kpi-label {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.02em;
            color: #64748b;
            margin: 0 0 8px 0;
        }

        .dash-kpi-value {
            font-size: 2rem;
            font-weight: 600;
            line-height: 1.1;
            letter-spacing: -0.03em;
            color: #0f172a;
            font-variant-numeric: tabular-nums;
            margin: 0;
        }

        .dash-kpi-hint {
            font-size: 12px;
            color: #94a3b8;
            margin: 8px 0 0 0;
        }

        .dash-kpi-icon-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: rgba(15, 23, 42, 0.06);
            color: #0f172a;
            flex-shrink: 0;
        }

        .dash-priority-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            background: #fffbeb;
            color: #a16207;
            border: 1px solid #fde68a;
        }

        .dash-channel-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 4px 12px rgba(15, 23, 42, 0.06);
            padding: 18px 20px;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .dash-channel-head {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 20px;
        }

        .dash-channel-title {
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.02em;
            color: #64748b;
            margin: 0;
        }

        .dash-channel-updated {
            font-size: 12px;
            color: #94a3b8;
            margin: 0;
        }

        .dash-channel-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        @media (max-width: 639px) {
            .dash-channel-grid {
                grid-template-columns: 1fr;
            }
        }

        .dash-channel-cell {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #fafafa;
            padding: 16px 12px;
            text-align: center;
        }

        .dash-channel-cell-label {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.02em;
            color: #64748b;
            margin: 0;
        }

        .dash-channel-cell-bookings {
            font-size: 1.25rem;
            font-weight: 600;
            color: #0f172a;
            font-variant-numeric: tabular-nums;
            margin: 8px 0 0 0;
            line-height: 1.2;
        }

        .dash-channel-cell-bookings span {
            font-size: 11px;
            font-weight: 500;
            color: #94a3b8;
            margin-left: 4px;
        }

        .dash-channel-cell-queue {
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            font-variant-numeric: tabular-nums;
            margin: 6px 0 0 0;
        }

        .dash-channel-cell-queue span {
            font-size: 11px;
            font-weight: 500;
            color: #94a3b8;
            margin-left: 4px;
        }

        .dash-channel-footer {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .dash-channel-footer-label {
            font-size: 12px;
            font-weight: 500;
            color: #64748b;
            margin: 0;
        }

        .dash-channel-footer-value {
            font-size: 14px;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
            color: #0f172a;
            margin: 0;
        }

        .dash-chart-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 4px 12px rgba(15, 23, 42, 0.06);
            padding: 24px;
        }

        .dash-chart-title {
            font-size: 15px;
            font-weight: 600;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.02em;
        }

        .dash-chart-title span {
            font-weight: 500;
            color: #94a3b8;
        }

        .dash-chart-sub {
            font-size: 12px;
            color: #64748b;
            margin: 6px 0 0 0;
        }

        .dash-chart-body {
            margin-top: 20px;
            padding-top: 8px;
        }

        .bar-chart-wrap {
            height: 9.5rem;
            max-height: 9.5rem;
            display: flex;
            align-items: flex-end;
            gap: 8px;
            padding: 0 4px 4px;
        }

        .bar-col {
            display: flex;
            flex: 1;
            min-width: 0;
            min-height: 0;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
        }

        .bar-col-count {
            font-size: 10px;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
            color: #64748b;
        }

        .bar-col-bar {
            width: 100%;
            max-width: 2.75rem;
            border-radius: 6px 6px 0 0;
            min-height: 4px;
            transition: height 0.45s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.15s ease, background-color 0.3s ease;
        }

        .bar-col-bar:hover {
            opacity: 0.88;
        }

        .bar-col-label {
            font-size: 10px;
            line-height: 1.2;
            color: #94a3b8;
            text-align: center;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .line-chart-wrap {
            margin-top: 0;
            height: 9.5rem;
            max-height: 9.5rem;
            display: flex;
            flex-direction: column;
            min-height: 0;
            padding: 0 4px;
        }

        .line-chart-labels {
            display: flex;
            justify-content: space-between;
            gap: 4px;
            margin-top: 8px;
        }

        .line-chart-label {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            text-align: center;
            font-size: 10px;
            font-weight: 500;
            color: #94a3b8;
        }

        /* Same treatment as Seating Analytics .sa-timestamp — last refresh, bottom-right */
        .dash-panel-timestamp {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 11px;
            font-weight: 500;
            color: #64748b;
            text-align: right;
            margin: 12px 0 0;
        }
    </style>

    {{-- KPI row --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

        <div class="dash-saas-card tc-dash-card">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <p class="dash-kpi-label">Bookings today</p>
                    <p class="dash-kpi-value">{{ $bookingsTodayTotal }}</p>
                    <p class="dash-kpi-hint">Web &amp; staff</p>
                </div>
                <div class="dash-kpi-icon-wrap" aria-hidden="true">
                    <i class="fa-solid fa-calendar-check text-[15px]"></i>
                </div>
            </div>
        </div>

        <div class="dash-saas-card tc-dash-card">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <p class="dash-kpi-label">Waiting now</p>
                    <p class="dash-kpi-value">{{ $partiesWaiting }}</p>
                    @if ($hasPriority)
                        <div class="dash-priority-pill">
                            <i class="fa-solid fa-star text-[10px]"></i>
                            {{ $priorityWaiting }} priority
                        </div>
                    @else
                        <p class="dash-kpi-hint">In queue</p>
                    @endif
                </div>
                <div class="dash-kpi-icon-wrap" aria-hidden="true">
                    <i class="fa-solid fa-clock text-[15px]"></i>
                </div>
            </div>
        </div>

        <div class="dash-saas-card tc-dash-card">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <p class="dash-kpi-label">Free tables</p>
                    <p class="dash-kpi-value">{{ $tablesFree }}</p>
                    <p class="dash-kpi-hint">Available now</p>
                </div>
                <div class="dash-kpi-icon-wrap" aria-hidden="true">
                    <i class="fa-solid fa-table text-[15px]"></i>
                </div>
            </div>
        </div>

        <div class="dash-saas-card tc-dash-card">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <p class="dash-kpi-label">Occupied</p>
                    <p class="dash-kpi-value">{{ $tablesOccupied }}</p>
                    <p class="dash-kpi-hint">Guests seated</p>
                </div>
                <div class="dash-kpi-icon-wrap" aria-hidden="true">
                    <i class="fa-solid fa-users text-[15px]"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- By channel --}}
    <div class="dash-channel-card tc-dash-card tc-dash-card--wide">
        <div class="dash-channel-head">
            <p class="dash-channel-title">Today by channel</p>
        </div>
        <div class="dash-channel-grid">
            @foreach (['website' => 'Web', 'staff' => 'Staff'] as $key => $label)
                <div class="dash-channel-cell">
                    <p class="dash-channel-cell-label">{{ $label }}</p>
                    <p class="dash-channel-cell-bookings">
                        {{ $bookingsBySource[$key] ?? 0 }}
                        <span>book</span>
                    </p>
                    <p class="dash-channel-cell-queue">
                        {{ $queueBySource[$key] ?? 0 }}
                        <span>queue</span>
                    </p>
                </div>
            @endforeach
        </div>
        <div class="dash-channel-footer">
            <p class="dash-channel-footer-label">Walk-ins recorded today</p>
            <p class="dash-channel-footer-value">{{ $walkInsTodayTotal }}</p>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

        <div class="dash-chart-card tc-dash-card tc-dash-card--chart">
            <h3 class="dash-chart-title">Bookings <span>last 7 days</span></h3>
            <p class="dash-chart-sub">New bookings per day</p>
            <div class="dash-chart-body">
                <div class="bar-chart-wrap">
                    @foreach ($bookingsLast7 as $row)
                        @php
                            $barPx = $maxBookings7 > 0 ? max(4, round(($row['count'] / $maxBookings7) * 100)) : 4;
                            $isMax = $maxBookings7 > 0 && $row['count'] === $maxBookings7;
                        @endphp
                        <div class="bar-col">
                            <span class="bar-col-count">{{ $row['count'] }}</span>
                            <div class="bar-col-bar"
                                style="height: {{ $barPx }}px; background: {{ $isMax ? $accentDark : $accent }};"
                                title="{{ $row['label'] }}: {{ $row['count'] }}">
                            </div>
                            <span class="bar-col-label">{{ $row['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="dash-chart-card tc-dash-card tc-dash-card--chart">
            <h3 class="dash-chart-title">Queue joins <span>last 7 days</span></h3>
            <p class="dash-chart-sub">Walk-in queue entries per day</p>
            @php
                $points = [];
                $n = count($queueLast7);
                foreach ($queueLast7 as $idx => $row) {
                    $x = $n <= 1 ? 50 : ($idx / ($n - 1)) * 100;
                    $q = $row['count'];
                    $y = 100 - ($maxQueue7 > 0 ? ($q / $maxQueue7) * 85 : 0);
                    $y = max(8, min(92, $y));
                    $points[] = round($x, 2) . ',' . round($y, 2);
                }
                $linePts = implode(' ', $points);
                $areaPts = '0,100 ' . $linePts . ' 100,100';
            @endphp
            <div class="dash-chart-body">
                <div class="line-chart-wrap">
                    <svg class="h-full min-h-0 w-full shrink-0" viewBox="0 0 100 100" preserveAspectRatio="none"
                        aria-hidden="true">
                        <defs>
                            <linearGradient id="queueAreaGradDash" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#0f172a" stop-opacity="0.12" />
                                <stop offset="100%" stop-color="#0f172a" stop-opacity="0.02" />
                            </linearGradient>
                        </defs>
                        <polygon points="{{ $areaPts }}" fill="url(#queueAreaGradDash)" />
                        <polyline points="{{ $linePts }}" fill="none" stroke="#0f172a" stroke-width="1.25"
                            vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" />
                        @foreach ($queueLast7 as $idx => $row)
                            @php
                                $dx = $n <= 1 ? 50 : ($idx / ($n - 1)) * 100;
                                $dq = $row['count'];
                                $dy = 100 - ($maxQueue7 > 0 ? ($dq / $maxQueue7) * 85 : 0);
                                $dy = max(8, min(92, $dy));
                            @endphp
                            <circle cx="{{ round($dx, 2) }}" cy="{{ round($dy, 2) }}" r="1.75" fill="#fff" stroke="#0f172a"
                                stroke-width="1.25" vector-effect="non-scaling-stroke" />
                        @endforeach
                    </svg>
                    <div class="line-chart-labels">
                        @foreach ($queueLast7 as $row)
                            <span class="line-chart-label">{{ $row['label'] }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <p class="dash-panel-timestamp">Updated {{ $lastUpdated }}</p>
</div>