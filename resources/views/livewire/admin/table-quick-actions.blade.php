<div>
    @if ($table)
        @php
            $statusBadge = match ($table->status) {
                'available' => ['label' => 'Free', 'class' => 'bg-emerald-100 text-emerald-900 ring-emerald-200'],
                'reserved' => ['label' => 'Reserved', 'class' => 'bg-amber-100 text-amber-900 ring-amber-200'],
                'occupied' => ['label' => 'Occupied', 'class' => 'bg-rose-100 text-rose-900 ring-rose-200'],
                'cleaning' => ['label' => 'Cleaning', 'class' => 'bg-blue-100 text-blue-900 ring-blue-200'],
                default => ['label' => ucfirst($table->status), 'class' => 'bg-slate-100 text-slate-800 ring-slate-200'],
            };
        @endphp

        <div wire:key="table-toolbar-{{ $table->id }}-v{{ $tablesSyncVersion }}" wire:poll.5s="pollTableModal"
            class="fixed z-[60] w-[min(18rem,calc(100vw-2rem))] rounded-xl border border-slate-200 bg-white p-3 shadow-lg shadow-slate-900/10 ring-1 ring-slate-900/5"
            style="left: {{ $popoverLeft }}px; top: {{ $popoverTop }}px; transform: translate(-50%, calc(-100% - 12px));">
            {{-- Header --}}
            <div class="mb-3 flex items-start justify-between gap-2 border-b border-slate-100 pb-2">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-semibold text-slate-900">{{ $table->label }}</span>
                        <span
                            class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold ring-1 {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                    </div>
                    <p class="mt-1.5 text-xs text-slate-600">
                        <span class="font-medium text-slate-500">Party:</span>
                        {{ $partyDisplay ?? '—' }}
                    </p>
                    @if ($table->status === 'reserved')
                        <p class="mt-1 text-xs text-slate-600">
                            <span class="font-medium text-slate-500">Arriving:</span>
                            {{ $arrivalDisplay ?? '--' }}
                        </p>
                    @elseif ($table->status === 'occupied' && $seatedDisplay)
                        <p class="mt-1 text-xs text-slate-600">
                            <span class="font-medium text-slate-500">Seated:</span>
                            {{ $seatedDisplay }}
                        </p>
                    @endif
                </div>
                <button type="button" wire:click="clearSelection"
                    class="shrink-0 rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-semibold text-slate-800 transition hover:bg-slate-100">
                    Done
                </button>
            </div>

            @can('update', $table)
                <div class="flex flex-wrap gap-2">
                    @if ($table->status === 'available')
                        <button type="button" wire:click="applyStatusFromSelect({{ $table->id }}, 'reserved')"
                            wire:loading.attr="disabled"
                            wire:target="applyStatusFromSelect"
                            class="rounded-lg border border-amber-200 bg-amber-50 px-2.5 py-1.5 text-xs font-semibold text-amber-950 hover:bg-amber-100">
                            Reserve
                        </button>
                        <button type="button" wire:click="applyStatusFromSelect({{ $table->id }}, 'occupied')"
                            wire:loading.attr="disabled"
                            wire:target="applyStatusFromSelect"
                            class="rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs font-semibold text-rose-950 hover:bg-rose-100">
                            Seat Guests
                        </button>
                        <button type="button" wire:click="applyStatusFromSelect({{ $table->id }}, 'cleaning')"
                            wire:loading.attr="disabled"
                            wire:target="applyStatusFromSelect"
                            class="rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1.5 text-xs font-semibold text-blue-950 hover:bg-blue-100">
                            Set Cleaning
                        </button>
                    @elseif ($table->status === 'reserved')
                        <button type="button" wire:click="applyStatusFromSelect({{ $table->id }}, 'occupied')"
                            wire:loading.attr="disabled"
                            wire:target="applyStatusFromSelect"
                            class="rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs font-semibold text-rose-950 hover:bg-rose-100">
                            Seat Guests
                        </button>
                        <button type="button" wire:click="applyStatusFromSelect({{ $table->id }}, 'available')"
                            wire:loading.attr="disabled"
                            wire:target="applyStatusFromSelect"
                            class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                            Free Table
                        </button>
                    @elseif ($table->status === 'occupied')
                        <div class="w-full min-w-0" x-data="{ confirmCheckout: false }">
                            <button type="button" x-show="!confirmCheckout" @click="confirmCheckout = true"
                                class="w-full rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1.5 text-left text-xs font-semibold text-blue-950 hover:bg-blue-100">
                                Guest Checked Out → Set Cleaning
                            </button>
                            <div x-show="confirmCheckout" class="space-y-2 rounded-lg border border-slate-200 bg-slate-50 p-2.5">
                                <p class="text-xs leading-snug text-slate-700">
                                    Mark guests as checked out and set table to cleaning?
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" wire:click="applyStatusFromSelect({{ $table->id }}, 'cleaning')"
                                        @click="confirmCheckout = false"
                                        wire:loading.attr="disabled"
                                        wire:target="applyStatusFromSelect"
                                        class="rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1.5 text-xs font-semibold text-blue-950 hover:bg-blue-100">
                                        Yes, check out
                                    </button>
                                    <button type="button" @click="confirmCheckout = false"
                                        class="rounded-lg border border-slate-200 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-800 hover:bg-slate-50">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    @elseif ($table->status === 'cleaning')
                        <button type="button" wire:click="markReadyAfterCleaning({{ $table->id }})"
                            class="rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1.5 text-xs font-semibold text-emerald-950 hover:bg-emerald-100">
                            Mark Free
                        </button>
                    @endif
                </div>
            @else
                <p class="text-xs text-amber-900">You cannot change this table.</p>
            @endcan
        </div>
    @endif
</div>