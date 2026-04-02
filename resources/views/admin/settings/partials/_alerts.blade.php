@php
    $field =
        'w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-panel-primary focus:outline-none focus:ring-2 focus:ring-panel-primary/15';
@endphp

<div class="grid grid-cols-1 gap-y-4">
    <div>
        <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="modal-alert-phone">Alert phone
            (staff)</label>
        <input id="modal-alert-phone" type="text" wire:model="adminAlertPhone" inputmode="tel" autocomplete="tel"
            data-modal-initial-focus class="{{ $field }} font-mono" placeholder="+63…">
    </div>
    <div>
        <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="modal-blocked-ips">Blocked addresses (one
            per line)</label>
        <textarea id="modal-blocked-ips" wire:model="blockedIpsText" rows="5"
            class="{{ $field }} min-h-[7rem] font-mono leading-relaxed"></textarea>
        <p class="mt-1.5 text-xs leading-relaxed text-slate-600">Localhost cannot be blocked.</p>
    </div>
    @if ($this->blockedIpsDirty)
        <div class="space-y-2 rounded-lg border border-amber-600/40 bg-amber-50 px-4 py-3">
            <label class="block text-xs font-bold uppercase tracking-[0.12em] text-amber-950" for="modal-settings-pw">Confirm with your
                password</label>
            <input id="modal-settings-pw" type="password" wire:model="settingsPasswordConfirm"
                autocomplete="current-password" class="{{ $field }} border-amber-700/50" placeholder="Account password">
            @error('settingsPasswordConfirm')
                <p class="text-sm font-medium text-red-700">{{ $message }}</p>
            @enderror
        </div>
    @endif
</div>
