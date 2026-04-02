<div wire:poll.30s>

    @include('admin.partials.sa-panel-styles')

    {{-- ── KPI row ─────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4 lg:gap-3">

        <div class="sa-stat-card">
            <div class="sa-stat-card-head">
                <p class="sa-stat-label">Bookings today</p>
                <div class="sa-stat-icon" aria-hidden="true"><i class="fa-solid fa-calendar-check"></i></div>
            </div>
            <p class="sa-stat-value">{{ $this->totalBookingsToday }}</p>
            <p class="sa-stat-sub">Reservations</p>
        </div>

        <div class="sa-stat-card">
            <div class="sa-stat-card-head">
                <p class="sa-stat-label">Checked in</p>
                <div class="sa-stat-icon" aria-hidden="true"><i class="fa-solid fa-circle-check"></i></div>
            </div>
            <p class="sa-stat-value">{{ $this->totalCheckedInToday }}</p>
            <p class="sa-stat-sub">Today</p>
        </div>

        <div class="sa-stat-card">
            <div class="sa-stat-card-head">
                <p class="sa-stat-label">Seated from queue</p>
                <div class="sa-stat-icon" aria-hidden="true"><i class="fa-solid fa-users-line"></i></div>
            </div>
            <p class="sa-stat-value">{{ $this->totalSeatedFromQueue }}</p>
            <p class="sa-stat-sub">Walk-ins</p>
        </div>

        <div class="sa-stat-card">
            <div class="sa-stat-card-head">
                <p class="sa-stat-label">Tables</p>
                <div class="sa-stat-icon" aria-hidden="true"><i class="fa-solid fa-table-cells"></i></div>
            </div>
            <p class="sa-stat-value sa-stat-value-split">
                <span class="sa-stat-num-primary">{{ $this->tablesFreeNow }}</span>
                <span class="sa-stat-num-sep">/</span>
                <span class="sa-stat-num-secondary">{{ $this->tablesOccupiedNow }}</span>
            </p>
            <p class="sa-stat-sub">Free / occupied</p>
        </div>
    </div>

    {{-- ── Charts ───────────────────────────────────────────────────────── --}}
    <div class="mt-3 grid grid-cols-1 gap-3 xl:grid-cols-2">

        <div class="sa-chart-card">
            <p class="sa-chart-title">Bookings by hour <span>— last 7 days</span></p>
            <p class="sa-chart-sub">Reservations grouped by hour of booking time</p>
            <div class="sa-chart-wrap">
                <canvas id="seating-peak-chart"></canvas>
            </div>
        </div>

        <div class="sa-chart-card">
            <p class="sa-chart-title">Top 5 tables <span>— last 30 days</span></p>
            <p class="sa-chart-sub">Most-used tables by reservation count</p>
            <div class="sa-chart-wrap">
                <canvas id="seating-top-chart"></canvas>
            </div>
        </div>
    </div>

    {{-- ── Timestamp ─────────────────────────────────────────────────────── --}}
    <p class="sa-timestamp mt-3">Updated {{ now()->format('g:i:s A') }}</p>

    {{-- ── Data + Chart init ────────────────────────────────────────────── --}}
    <script type="application/json" id="seating-analytics-json">@json($chartPayload)</script>

    <script>
        (function () {
            var BAR_FILL = '#1e293b';
            var BAR_BORDER = '#0f172a';
            var GRID = '#f1f5f9';
            var TICK = '#64748b';
            var FONT = { size: 11, weight: '500', family: "'Inter', system-ui, sans-serif" };

            var GRID_AXIS = {
                grid: { color: GRID, drawBorder: false },
                border: { display: false },
                ticks: { color: TICK, font: FONT },
            };

            var barRadiusV = { topLeft: 4, topRight: 4, bottomLeft: 0, bottomRight: 0 };
            var barRadiusH = { topLeft: 0, bottomLeft: 0, topRight: 4, bottomRight: 4 };

            function buildCharts() {
                if (typeof Chart === 'undefined') return;

                var el = document.getElementById('seating-analytics-json');
                if (!el) return;

                var payload;
                try { payload = JSON.parse(el.textContent); } catch (e) { return; }

                var peakCanvas = document.getElementById('seating-peak-chart');
                var topCanvas = document.getElementById('seating-top-chart');
                if (!peakCanvas || !topCanvas) return;

                if (window.__seatingPeakChart) { window.__seatingPeakChart.destroy(); window.__seatingPeakChart = null; }
                if (window.__seatingTopChart) { window.__seatingTopChart.destroy(); window.__seatingTopChart = null; }

                /* Peak-hour bar chart */
                var peakLabels = payload.peakLabels || [];
                var peak = payload.peak || {};
                var peakValues = [];
                for (var h = 0; h < 24; h++) {
                    peakValues.push(typeof peak[h] === 'number' ? peak[h] : 0);
                }

                window.__seatingPeakChart = new Chart(peakCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: peakLabels,
                        datasets: [{
                            label: 'Bookings',
                            data: peakValues,
                            backgroundColor: BAR_FILL,
                            borderColor: BAR_BORDER,
                            borderWidth: 1,
                            borderRadius: barRadiusV,
                            borderSkipped: 'bottom',
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                ...GRID_AXIS,
                                ticks: {
                                    ...GRID_AXIS.ticks,
                                    maxRotation: 0,
                                    minRotation: 0,
                                    autoSkip: false,
                                    callback: function (val, index) {
                                        if (index % 4 !== 0) return '';
                                        return peakLabels[index] || '';
                                    },
                                },
                            },
                            y: {
                                ...GRID_AXIS,
                                beginAtZero: true,
                                ticks: { ...GRID_AXIS.ticks, stepSize: 1 },
                            },
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                titleFont: { size: 11, weight: '700', family: FONT.family },
                                bodyFont: { size: 11, family: FONT.family },
                                padding: 10,
                                cornerRadius: 8,
                            },
                        },
                    },
                });

                /* Top tables horizontal bar chart */
                var topRows = payload.top || [];
                var topLabels = topRows.map(function (r) { return r.label; });
                var topCounts = topRows.map(function (r) { return r.count; });

                window.__seatingTopChart = new Chart(topCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: topLabels,
                        datasets: [{
                            label: 'Bookings',
                            data: topCounts,
                            backgroundColor: BAR_FILL,
                            borderColor: BAR_BORDER,
                            borderWidth: 1,
                            borderRadius: barRadiusH,
                            borderSkipped: 'left',
                        }],
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                ...GRID_AXIS,
                                beginAtZero: true,
                                ticks: { ...GRID_AXIS.ticks, stepSize: 1 },
                            },
                            y: { ...GRID_AXIS },
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                titleFont: { size: 11, weight: '700', family: FONT.family },
                                bodyFont: { size: 11, family: FONT.family },
                                padding: 10,
                                cornerRadius: 8,
                            },
                        },
                    },
                });
            }

            function tryInit() {
                if (typeof Chart === 'undefined') { setTimeout(tryInit, 50); return; }
                buildCharts();
            }

            document.addEventListener('DOMContentLoaded', tryInit);
            document.addEventListener('livewire:init', function () {
                Livewire.hook('morph.updated', function () {
                    requestAnimationFrame(function () {
                        if (document.getElementById('seating-analytics-json')) tryInit();
                    });
                });
            });
        })();
    </script>
</div>