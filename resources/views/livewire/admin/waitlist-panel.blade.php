<div data-waitlist-root data-queue-total="{{ $priorityQueue->count() + $regularQueue->count() }}"
    class="flex h-full min-h-0 w-full flex-col overflow-y-auto px-4 py-4 sm:px-5 sm:py-5 tc-scrollbar" wire:poll.12s>

    {{-- Controls: Auto-SMS + busy hours + override --}}
    <div class="mb-3 flex flex-wrap items-center gap-2">
        @if (auth()->user()->isAdmin())
            <button type="button" wire:click="toggleAutoSms"
                class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold shadow-sm ring-1 transition {{ $autoSmsOn ? 'bg-emerald-50 text-emerald-900 ring-emerald-200' : 'bg-amber-50 text-amber-950 ring-amber-200' }}">
                <span class="h-2 w-2 rounded-full {{ $autoSmsOn ? 'bg-emerald-500' : 'bg-amber-500' }}" aria-hidden="true"></span>
                Auto-SMS: {{ $autoSmsOn ? 'Active' : 'Paused' }}
            </button>
            <button type="button" wire:click="openBusyHoursModal"
                class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-800 shadow-sm hover:bg-slate-50">
                <i class="fa-regular fa-clock text-xs"></i>
                Busy hours
            </button>
        @endif
        <button type="button" wire:click="togglePeakOverride"
            class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold shadow-sm ring-1 transition {{ $peakOverrideOn ? 'bg-sky-50 text-sky-950 ring-sky-200' : 'border border-slate-200 bg-white text-slate-700 ring-transparent' }}">
            Override {{ $peakOverrideOn ? 'ON' : 'off' }}
        </button>
        <span class="text-xs text-slate-500" title="Approximate busy window">Peak: {{ $systemStatus['diagnostics']['approx_peak_label'] ?? '—' }}</span>
    </div>

    {{-- System status (replaces static warning box) --}}
    @php
        $tone = $systemStatus['tone'] ?? 'ok';
        $bar =
            $tone === 'danger'
                ? 'border-rose-200 bg-rose-50 text-rose-950'
                : ($tone === 'warn'
                    ? 'border-amber-200 bg-amber-50 text-amber-950'
                    : 'border-emerald-200 bg-emerald-50 text-emerald-950');
    @endphp
    <div class="mb-3 rounded-xl border px-3 py-2.5 text-xs leading-snug {{ $bar }}">
        <div class="flex flex-wrap items-start justify-between gap-2 gap-y-2">
            <div class="min-w-0 flex-1">
                <p class="m-0 font-semibold">{{ $systemStatus['headline'] ?? 'Status' }}</p>
                @if (count($systemStatus['hints'] ?? []) > 1)
                    <ul class="mt-1.5 list-disc space-y-0.5 pl-4 opacity-95">
                        @foreach (array_slice($systemStatus['hints'], 1) as $h)
                            <li>{{ $h }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            @if (auth()->user()->isAdmin() && ($systemStatus['resume_auto_sms_available'] ?? false))
                <button type="button" wire:click="resumeAutoSms" wire:loading.attr="disabled"
                    class="inline-flex shrink-0 items-center justify-center rounded-lg bg-panel-primary px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-panel-primary-hover disabled:cursor-not-allowed disabled:opacity-60">
                    <span wire:loading.remove wire:target="resumeAutoSms">Resume SMS</span>
                    <span wire:loading wire:target="resumeAutoSms" class="inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-spinner fa-spin text-xs" aria-hidden="true"></i>
                        Resuming…
                    </span>
                </button>
            @endif
        </div>
    </div>

    <div class="mb-3 flex items-start gap-2">
        <h3 class="text-base font-bold text-slate-900">Waitlist</h3>
        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">Queue # = join order</span>
    </div>

    @error('seatCustomer')
        <p class="mb-2 flex items-center gap-2 text-xs font-medium text-red-600">
            <i class="fa-solid fa-circle-exclamation"></i>{{ $message }}
        </p>
    @enderror

    @if ($priorityQueue->count() > 0)
        <div class="mb-4">
            <h4 class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-900">
                <i class="fa-solid fa-star-of-life text-xs text-amber-500"></i> Priority
            </h4>
            <div class="space-y-2">
                @foreach ($priorityQueue as $entry)
                    <div
                        class="relative flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-200/90 bg-white p-4 shadow-sm transition hover:border-slate-300 hover:shadow">
                        <div class="flex min-w-0 flex-1 flex-wrap items-center gap-2">
                            <span
                                class="rounded-full bg-panel-primary px-1.5 py-0.5 font-mono text-xs font-bold text-white">#{{ $entry->queue_display_number }}</span>
                            @if ($entry->priority_type === 'pwd')
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-violet-50 px-2 py-0.5 text-xs font-bold text-violet-900 ring-1 ring-violet-200"><i
                                        class="fa-solid fa-wheelchair"></i> PWD</span>
                            @elseif($entry->priority_type === 'pregnant')
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2 py-0.5 text-xs font-bold text-rose-900 ring-1 ring-rose-200"><i
                                        class="fa-solid fa-baby"></i> PREG</span>
                            @elseif($entry->priority_type === 'senior')
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-sky-50 px-2 py-0.5 text-xs font-bold text-sky-900 ring-1 ring-sky-200"><i
                                        class="fa-solid fa-person-cane"></i> SC</span>
                            @endif
                            <span class="truncate text-sm font-semibold text-slate-900">{{ $entry->customer_name }}</span>
                            <span class="text-xs font-medium text-slate-500">{{ $entry->party_size }}p</span>
                            <span
                                class="rounded-md border border-slate-200 bg-slate-50 px-1.5 py-0.5 text-xs font-semibold uppercase text-slate-600">{{ $entry->source ?? 'web' }}</span>
                        </div>
                        <div
                            class="flex w-full flex-wrap items-center justify-end gap-2 sm:w-auto">
                            @include('livewire.admin.partials.waitlist-seat-controls', [
                                'entry' => $entry,
                                'availableTables' => $availableTables,
                                'selectedTableId' => $selectedTableId,
                                'showHoldActions' => false,
                            ])
                            <button type="button" wire:click="sendSmsManually({{ $entry->id }})"
                                wire:loading.attr="disabled"
                                wire:target="sendSmsManually"
                                class="rounded-lg min-h-[44px] bg-panel-primary px-2.5 py-2.5 text-xs font-semibold text-white hover:bg-panel-primary-hover">
                                SMS
                            </button>
                            @if (auth()->user()->isAdmin())
                                <button type="button" wire:click="cancelEntry({{ $entry->id }})"
                                    wire:confirm="Remove this guest from the queue?"
                                    class="rounded-lg min-h-[44px] bg-rose-600 px-2.5 py-2.5 text-xs font-semibold text-white hover:bg-rose-700">
                                    Remove
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($regularQueue->count() > 0)
        <div class="mb-4">
            <h4 class="mb-2 text-sm font-semibold text-slate-900">Regular queue</h4>
            <div class="space-y-2">
                @foreach ($regularQueue as $entry)
                    <div
                        class="relative flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-200/90 bg-white p-4 shadow-sm transition hover:border-slate-300 hover:shadow">
                        <div class="flex flex-wrap items-center gap-2">
                            <span
                                class="rounded-full bg-slate-100 px-1.5 py-0.5 font-mono text-xs font-bold text-slate-800">#{{ $entry->queue_display_number }}</span>
                            <span class="text-sm font-medium text-slate-900">{{ $entry->customer_name }}</span>
                            <span class="text-xs text-slate-500">{{ $entry->party_size }}p</span>
                            <span
                                class="rounded-md border border-slate-200 bg-slate-50 px-1.5 py-0.5 text-xs font-semibold uppercase text-slate-600">{{ $entry->source ?? 'web' }}</span>
                        </div>
                        <div
                            class="flex w-full flex-wrap items-center justify-end gap-2 sm:w-auto">
                            @include('livewire.admin.partials.waitlist-seat-controls', [
                                'entry' => $entry,
                                'availableTables' => $availableTables,
                                'selectedTableId' => $selectedTableId,
                                'showHoldActions' => false,
                            ])
                            <button type="button" wire:click="sendSmsManually({{ $entry->id }})"
                                wire:loading.attr="disabled"
                                wire:target="sendSmsManually"
                                class="rounded-lg min-h-[44px] bg-panel-primary px-2.5 py-2.5 text-xs font-semibold text-white hover:bg-panel-primary-hover">
                                SMS
                            </button>
                            @if (auth()->user()->isAdmin())
                                <button type="button" wire:click="cancelEntry({{ $entry->id }})"
                                    wire:confirm="Remove this guest from the queue?"
                                    class="rounded-lg min-h-[44px] bg-rose-600 px-2.5 py-2.5 text-xs font-semibold text-white hover:bg-rose-700">
                                    Remove
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($priorityQueue->count() === 0 && $regularQueue->count() === 0)
        <p class="py-6 text-center text-sm text-slate-500">
            <i class="fa-solid fa-mug-hot mb-2 block text-2xl text-slate-300"></i>
            No walk-ins waiting
        </p>
    @endif

    @if (isset($notifiedHold) && $notifiedHold->count() > 0)
        <div class="mt-4 border-t border-slate-200 pt-4">
            <h4 class="mb-2 flex items-center gap-2 text-sm font-bold text-slate-900">
                <i class="fa-solid fa-mobile-screen text-slate-600"></i> Texted, on hold
            </h4>
            <p class="mb-3 text-xs text-slate-600">Hold countdown — please seat or extend before it expires.</p>
            <div class="space-y-2">
                @foreach ($notifiedHold as $entry)
                    <div
                        class="relative flex flex-wrap items-center justify-between gap-2 rounded-xl border border-slate-200 bg-amber-50/40 p-4 text-sm shadow-sm">
                        <div class="flex flex-wrap items-center gap-2">
                            @if ($entry->isPriority())
                                <span
                                    class="rounded-md bg-panel-primary px-1.5 py-0.5 text-xs font-bold uppercase text-white">Priority</span>
                            @endif
                            <span class="font-semibold text-slate-900">{{ $entry->customer_name }}</span>
                            <span class="text-xs text-slate-600">{{ $entry->party_size }}p</span>
                            @if ($entry->hold_expires_at)
                                <span class="text-xs font-medium text-slate-700">Until
                                    {{ $entry->hold_expires_at->format('g:i A') }}</span>
                                <span class="inline-flex items-center gap-1 rounded-full bg-white px-2 py-0.5 text-xs font-bold text-amber-900 ring-1 ring-amber-200"
                                    data-hold-expires="{{ $entry->hold_expires_at->toIso8601String() }}">
                                    <i class="fa-regular fa-hourglass-half text-xs"></i>
                                    <span class="tc-hold-remaining">—</span>
                                </span>
                            @endif
                        </div>
                        <div
                            class="flex w-full flex-wrap items-center justify-end gap-2 sm:w-auto">
                            @include('livewire.admin.partials.waitlist-seat-controls', [
                                'entry' => $entry,
                                'availableTables' => $availableTables,
                                'selectedTableId' => $selectedTableId,
                                'showHoldActions' => true,
                            ])
                            <button type="button" wire:click="sendSmsManually({{ $entry->id }})"
                                wire:loading.attr="disabled"
                                wire:target="sendSmsManually"
                                class="rounded-lg min-h-[44px] bg-panel-primary px-2.5 py-2.5 text-xs font-semibold text-white hover:bg-panel-primary-hover">
                                SMS
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-6 border-t border-slate-200 pt-4">
        <h4 class="mb-2 text-sm font-semibold text-slate-900">Reservations (awaiting check-in)</h4>
        @error('markBookingNoShow')
            <p class="mb-2 text-xs text-red-600">{{ $message }}</p>
        @enderror
        @if ($noShowBookings->count() > 0)
            <div class="space-y-2">
                @foreach ($noShowBookings as $booking)
                    <div class="flex flex-wrap items-center justify-between gap-2 rounded-xl border border-amber-200 bg-amber-50/80 p-3">
                        <div class="text-sm text-slate-900">
                            <span class="font-semibold">{{ $booking->customer_name }}</span>
                            <span class="ml-2 font-mono text-xs text-slate-600">{{ $booking->booking_ref }}</span>
                            <span class="ml-2 text-xs text-slate-600">{{ $booking->party_size }}p</span>
                            <span
                                class="block text-xs text-slate-600 sm:ml-2 sm:inline">{{ $booking->booked_at?->format('M j, g:i A') }}</span>
                        </div>
                        <div>
                            @if ($booking->payment_status === 'pending' && $booking->status === 'pending')
                                <span class="text-xs text-slate-600">Awaiting payment</span>
                            @else
                                <button type="button" wire:click="markBookingNoShow({{ $booking->id }})"
                                    wire:confirm="Mark this reservation as no-show? The customer will receive the no-show SMS and any assigned table will be released."
                                    class="rounded-xl bg-panel-primary px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-panel-primary-hover">
                                    Mark as No-show
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-slate-500">None</p>
        @endif
    </div>

    {{-- Busy hours modal --}}
    @if ($showBusyHoursModal)
        <div class="fixed inset-0 z-[120] flex items-center justify-center bg-black/40 p-4" wire:click.self="closeBusyHoursModal">
            <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-5 shadow-xl" @click.stop>
                <h3 class="text-base font-bold text-slate-900">Busy hours (auto table-ready SMS)</h3>
                <p class="mt-1 text-xs text-slate-600">When “learn from waitlist” is on, peak hours are estimated from traffic;
                    otherwise fixed window applies.</p>
                <div class="mt-4 space-y-3">
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-800">
                        <input type="checkbox" wire:model.live="busyLearnFromQueue" class="rounded border-slate-300" />
                        Learn busy hours from waitlist joins
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600">Start</label>
                            <input type="time" wire:model="busyPeakStart"
                                class="mt-1 w-full rounded-lg border border-slate-200 px-2 py-2 text-sm" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600">End</label>
                            <input type="time" wire:model="busyPeakEnd"
                                class="mt-1 w-full rounded-lg border border-slate-200 px-2 py-2 text-sm" />
                        </div>
                    </div>
                </div>
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" wire:click="closeBusyHoursModal"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                    <button type="button" wire:click="saveBusyHours"
                        class="rounded-lg bg-panel-primary px-4 py-2 text-sm font-semibold text-white hover:opacity-90">Save</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Quick seat overlay --}}
    @if ($seatQuickPickEntryId && $quickPickEntry)
        <div class="fixed inset-0 z-[130] flex items-end justify-center bg-black/45 p-4 sm:items-center" wire:click.self="closeSeatQuickPick">
            <div class="max-h-[85vh] w-full max-w-lg overflow-y-auto rounded-2xl border border-slate-200 bg-white p-4 shadow-2xl"
                @click.stop>
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Seat {{ $quickPickEntry->customer_name }}</h3>
                        <p class="text-xs text-slate-600">{{ $quickPickEntry->party_size }} guests — best fit first</p>
                    </div>
                    <button type="button" wire:click="closeSeatQuickPick" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100"
                        aria-label="Close">&times;</button>
                </div>
                <div class="mt-4 grid gap-2">
                    @forelse ($sortedQuickTables as $t)
                        <button type="button" wire:click="seatFromQuickPick({{ $quickPickEntry->id }}, {{ $t->id }})"
                            class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-left text-sm font-semibold text-slate-900 transition hover:border-sky-300 hover:bg-sky-50">
                            <span>{{ $t->capacity }} seats ({{ $t->label }})</span>
                            <span class="text-xs font-medium text-slate-600">{{ $t->capacity }}p cap ·
                                @if ($t->capacity == $quickPickEntry->party_size)
                                    <span class="text-emerald-700">exact fit</span>
                                @else
                                    +{{ $t->capacity - $quickPickEntry->party_size }} spare
                                @endif
                            </span>
                        </button>
                    @empty
                        <p class="text-sm text-amber-800">No free table fits this party. Free a larger table or adjust capacity.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <script>
        (function () {
            function fmt(ms) {
                if (ms <= 0) return '0:00';
                var s = Math.floor(ms / 1000);
                var m = Math.floor(s / 60);
                s = s % 60;
                return m + ':' + (s < 10 ? '0' : '') + s;
            }

            function tick() {
                document.querySelectorAll('[data-hold-expires]').forEach(function (el) {
                    var iso = el.getAttribute('data-hold-expires');
                    if (!iso) return;
                    var end = Date.parse(iso);
                    var span = el.querySelector('.tc-hold-remaining');
                    if (!span) return;
                    var left = end - Date.now();
                    span.textContent = left > 0 ? fmt(left) + ' left' : 'Expired';
                });
            }

            tick();
            setInterval(tick, 1000);
            document.addEventListener('livewire:navigated', tick);
            document.addEventListener('livewire:init', function () {
                if (typeof Livewire === 'undefined' || !Livewire.hook) return;
                Livewire.hook('morph.updated', function () {
                    tick();
                });
            });
        })();
    </script>
</div>
