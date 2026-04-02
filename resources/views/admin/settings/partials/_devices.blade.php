@php
    $field =
        'w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-panel-primary focus:outline-none focus:ring-2 focus:ring-panel-primary/15';
@endphp

<div class="grid grid-cols-1 gap-y-4">
    <div class="grid grid-cols-1 gap-y-3">
        <label class="flex cursor-pointer items-start gap-3 text-sm font-medium leading-snug text-slate-900">
            <input type="checkbox" wire:model.live="automationMasterEnabled"
                class="mt-0.5 h-4 w-4 shrink-0 rounded border-slate-300 text-panel-primary focus:ring-panel-primary/30">
            <span>Turn automation on</span>
        </label>
        <label class="flex cursor-pointer items-start gap-3 text-sm font-medium leading-snug text-slate-900">
            <input type="checkbox" wire:model.live="queuePwdRequiresAccessibleTable"
                class="mt-0.5 h-4 w-4 shrink-0 rounded border-slate-300 text-panel-primary focus:ring-panel-primary/30">
            <span>PWD line uses accessible tables only</span>
        </label>
    </div>
    <p class="border-t border-slate-200 pt-3 text-xs leading-relaxed text-slate-600">
        Priority only changes queue order; PWD limits seating to accessible tables.
    </p>
</div>
