@php
    $field =
        'w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-base text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-panel-primary focus:outline-none focus:ring-2 focus:ring-panel-primary/15';
@endphp

<div class="grid grid-cols-1 gap-y-4">
    <div>
        <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="modal-pm-pk">Public Key</label>
        <input id="modal-pm-pk" type="password" wire:input.debounce.500ms="updateSecret('paymongo_public_key', $event.target.value)" autocomplete="off" data-modal-initial-focus
            class="{{ $field }} font-mono" placeholder="pk_…">
    </div>
    <div>
        <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="modal-pm-sk">Secret Key</label>
        <input id="modal-pm-sk" type="password" wire:input.debounce.500ms="updateSecret('paymongo_secret_key', $event.target.value)" autocomplete="off"
            class="{{ $field }} font-mono" placeholder="sk_…">
    </div>
    <div>
        <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="modal-pm-wh">Webhook Secret</label>
        <input id="modal-pm-wh" type="password" wire:input.debounce.500ms="updateSecret('paymongo_webhook_secret', $event.target.value)" autocomplete="off"
            class="{{ $field }} font-mono" placeholder="whsk_…">
        <p class="mt-2 text-xs leading-relaxed text-slate-600">
            Webhook:
            <code class="rounded-md border border-slate-200 bg-slate-100 px-1.5 py-0.5 font-mono text-xs text-slate-800">{{ url('/webhook/paymongo') }}</code>
        </p>
    </div>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
        <div>
            <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="modal-pm-dep">Deposit per guest
                (₱)</label>
            <input id="modal-pm-dep" type="number" wire:model="depositPerGuest" min="0" max="100000"
                class="{{ $field }} tabular-nums">
        </div>
        <div>
            <label class="mb-1.5 block text-xs font-bold uppercase tracking-[0.12em] text-black" for="modal-pm-fee">Booking fee (₱)</label>
            <input id="modal-pm-fee" type="number" wire:model="reservationFee" min="0" max="100000"
                class="{{ $field }} tabular-nums">
        </div>
    </div>
</div>
