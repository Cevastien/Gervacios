@php
    $field =
        'w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-panel-primary focus:outline-none focus:ring-2 focus:ring-panel-primary/15';
@endphp

<div class="grid grid-cols-1 gap-y-4">
    <p class="text-base leading-relaxed text-slate-600">
        “Table ready” SMS only during busy hours (auto or manual window).
    </p>
    <label class="flex cursor-pointer items-start gap-3 text-sm font-medium leading-snug text-slate-900">
        <input type="checkbox" wire:model.live="peakHoursLearnFromQueue" data-modal-initial-focus
            class="mt-0.5 h-4 w-4 shrink-0 rounded border-slate-300 text-panel-primary focus:ring-panel-primary/30">
        <span>Learn busy hours from the waitlist</span>
    </label>
    <details
        class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 [&_summary]:cursor-pointer [&_summary]:font-semibold [&_summary]:text-slate-900">
        <summary>How this works</summary>
        <p class="mt-2 border-l-2 border-slate-300 pl-3 text-sm leading-relaxed text-slate-600">
            Outside busy hours, “table ready” texts are not sent when a table opens.
        </p>
    </details>
    <div wire:key="peak-preview-{{ $settingsModal }}"
        class="max-h-56 overflow-y-auto rounded-lg border border-slate-200 bg-white p-3">
        @livewire('admin.peak-hours-status')
    </div>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 {{ $peakHoursLearnFromQueue ? 'opacity-60' : '' }}">
        <div>
            <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="modal-peak-start">Busy start
                (manual)</label>
            <input id="modal-peak-start" type="time" wire:model="peakHoursStart" @disabled($peakHoursLearnFromQueue)
                class="{{ $field }}">
        </div>
        <div>
            <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="modal-peak-end">Busy end</label>
            <input id="modal-peak-end" type="time" wire:model="peakHoursEnd" @disabled($peakHoursLearnFromQueue)
                class="{{ $field }}">
        </div>
    </div>
</div>
