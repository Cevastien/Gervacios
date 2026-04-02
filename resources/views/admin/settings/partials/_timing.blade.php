@php
    $field =
        'w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-panel-primary focus:outline-none focus:ring-2 focus:ring-panel-primary/15';
@endphp

<div class="grid grid-cols-1 gap-y-4">
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div>
            <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="modal-timing-hold">Hold (min)</label>
            <input id="modal-timing-hold" type="number" wire:model="automationQueueHoldMinutes" min="1" max="120"
                data-modal-initial-focus
                class="{{ $field }} tabular-nums">
        </div>
        <div>
            <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="modal-timing-noshow">No-show
                (min)</label>
            <input id="modal-timing-noshow" type="number" wire:model="automationNoShowMinutes" min="5" max="240"
                class="{{ $field }} tabular-nums">
        </div>
        <div>
            <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="modal-timing-clean">Cleaning
                (min)</label>
            <input id="modal-timing-clean" type="number" wire:model="tableCleaningMinutes" min="0" max="240"
                class="{{ $field }} tabular-nums">
        </div>
    </div>
</div>
