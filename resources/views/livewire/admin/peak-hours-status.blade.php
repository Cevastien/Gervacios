<?php
$counts = $d['counts'] ?? array_fill(0, 24, 0);
$maxC = max($counts) ?: 1;
$peakSet = array_flip($d['peak_hours'] ?? []);
?>
<div class="rounded-xl border border-panel-stroke bg-panel-surface p-4 shadow-sm shadow-slate-900/5 sm:p-5" wire:poll.5s>
    {{-- Row 1: title + cache --}}
    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-4">
        <h3 class="text-[13px] font-semibold tracking-wide text-slate-500">Live status</h3>
        <span class="text-xs font-medium tabular-nums text-slate-400">cache {{ (int) ($d['cache_ttl_seconds'] ?? 90) }}s</span>
    </div>

    {{-- Row 2: clock + weekday --}}
    <div class="flex flex-wrap items-baseline gap-3 border-b border-slate-100 py-4">
        <span class="text-3xl font-semibold tabular-nums tracking-tight text-slate-900">{{ $d['now_label'] ?? '—' }}</span>
        <span class="text-base font-normal text-slate-500">{{ $d['now_day'] ?? '' }}</span>
    </div>

    {{-- Row 3: status pills --}}
    <div class="flex flex-wrap gap-2 border-b border-slate-100 py-4">
        @if (!empty($d['in_peak']))
            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800 ring-1 ring-emerald-200/80">In peak</span>
        @else
            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200/80">Off-peak</span>
        @endif

        @if (!empty($d['table_ready_sms_would_send']))
            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-800 ring-1 ring-emerald-200/80">Table-ready SMS on</span>
        @else
            <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-900 ring-1 ring-amber-200/80">Table-ready SMS paused</span>
        @endif
    </div>

    {{-- Row 4: busy window --}}
    <div class="border-b border-slate-100 py-4">
        <p class="text-sm leading-relaxed text-slate-600">
            <span class="font-semibold text-slate-700">Busy window</span>
            <span class="text-slate-600"> — {{ $d['approx_peak_label'] ?? '—' }}</span>
            <span class="text-slate-400"> ({{ $d['timezone'] ?? config('app.timezone') }})</span>
        </p>
    </div>

    @if (!empty($d['learn_enabled']))
        <div class="space-y-3 border-b border-slate-100 py-4 text-xs leading-relaxed text-slate-500">
            <p>
                <span class="font-semibold text-slate-700">Data</span>
                @switch($d['dataset'] ?? '')
                    @case('same_weekday')
                        — Same weekday as today (last {{ config('automation.peak_queue_lookback_days', 28) }} days)
                        @break
                    @case('all_days')
                        — All days combined (not enough same-weekday joins yet)
                        @break
                    @case('cold_start')
                        — Warming up — treating all hours as peak until enough joins
                        @break
                    @default
                        — {{ $d['dataset'] }}
                @endswitch
            </p>
            <p>
                <span class="font-semibold text-slate-700">Joins analyzed</span>
                {{ (int) ($d['total_joins'] ?? 0) }}
                @if (($d['threshold'] ?? 0) > 0)
                    <span class="text-slate-400"> · </span>
                    <span class="font-semibold text-slate-700">Peak bar threshold</span> ≥ {{ (int) $d['threshold'] }} joins/hour
                @endif
            </p>
            @if (!empty($d['computed_at']))
                <p class="text-slate-400">
                    Snapshot {{ \Illuminate\Support\Carbon::parse($d['computed_at'])->timezone($d['timezone'] ?? config('app.timezone'))->format('g:i:s A') }}
                </p>
            @endif
        </div>

        <p class="border-b border-slate-100 py-3 text-xs text-slate-500">
            Hour bars use whole hours. Current hour:
            <span class="font-semibold tabular-nums text-slate-900">{{ (int) ($d['current_hour'] ?? 0) }}</span>
        </p>

        <div class="pt-4">
            <p class="mb-2 text-[13px] font-semibold tracking-wide text-slate-500">Joins by hour</p>
            <div class="flex h-24 gap-1 overflow-x-auto pb-1 pt-1" role="img" aria-label="Joins by hour">
                @foreach (range(0, 23) as $h)
                    @php
                        $cnt = (int) ($counts[$h] ?? 0);
                        $pct = min(100, max(0, round($cnt / $maxC * 100)));
                        $barPct = $cnt > 0 ? max(12, $pct) : 4;
                        $isPeak = isset($peakSet[$h]);
                        $isNow = (int) ($d['current_hour'] ?? -1) === $h;
                    @endphp
                    <div class="flex h-full min-w-[18px] max-w-[28px] flex-1 flex-col items-center justify-end"
                        title="{{ $h }}:00 — {{ $cnt }} joins{{ $isPeak ? ' (peak)' : '' }}">
                        <div
                            class="w-full rounded-t-md transition-all duration-500 {{ $isPeak ? 'bg-panel-primary' : 'bg-slate-200' }} {{ $isNow ? 'ring-2 ring-panel-primary ring-offset-2 ring-offset-white' : '' }}"
                            style="height: {{ $barPct }}%">
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-1 flex justify-between px-0.5 text-[10px] font-medium text-slate-400">
                <span>0h</span>
                <span>12h</span>
                <span>23h</span>
            </div>
        </div>
    @else
        <p class="pt-4 text-xs leading-relaxed text-slate-500">
            Learning is off — fixed hour band only. Turn on
            <strong class="font-semibold text-slate-800">Learn peak hours from waitlist joins</strong>
            to fill the chart from real traffic.
        </p>
    @endif
</div>
