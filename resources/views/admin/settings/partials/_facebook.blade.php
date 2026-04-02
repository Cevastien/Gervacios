@php
    $field =
        'w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-panel-primary focus:outline-none focus:ring-2 focus:ring-panel-primary/15';
@endphp

<div class="grid grid-cols-1 gap-y-4">
    <div>
        <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="modal-fb-page">Facebook page ID</label>
        <input id="modal-fb-page" type="text" wire:model="fbPageId" data-modal-initial-focus class="{{ $field }}">
    </div>
    <div>
        <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="modal-fb-token">Access token</label>
        <input id="modal-fb-token" type="password" wire:input.debounce.500ms="updateSecret('fb_access_token', $event.target.value)" class="{{ $field }} font-mono">
    </div>
    <div>
        <button type="button" wire:click="syncNow"
            class="inline-flex w-full items-center justify-center rounded-lg border border-panel-primary bg-panel-primary px-4 py-2.5 text-xs font-semibold uppercase tracking-[0.1em] text-white shadow-sm transition hover:bg-panel-primary-hover focus:outline-none focus-visible:ring-2 focus-visible:ring-panel-primary focus-visible:ring-offset-2 sm:w-auto">
            <span wire:loading.remove wire:target="syncNow">Sync now</span>
            <span wire:loading wire:target="syncNow">Syncing…</span>
        </button>
    </div>
</div>
