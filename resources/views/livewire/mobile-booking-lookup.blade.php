<div class="space-y-6">
    <h1 class="font-forum text-3xl text-cream text-center">Reservation status</h1>
    <form wire:submit.prevent="lookup" class="space-y-4">
        <div>
            <label class="block text-xs uppercase tracking-wider text-cream/60 mb-2">Booking reference</label>
            <input type="text" wire:model.blur="booking_ref" class="w-full min-h-12 rounded-xl bg-muted-bg border border-border-subtle px-4 text-cream uppercase font-mono" placeholder="GRV-XXXXXXXX">
            @error('booking_ref') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs uppercase tracking-wider text-cream/60 mb-2">Mobile on file</label>
            <input type="tel" wire:model.blur="customer_phone" class="w-full min-h-12 rounded-xl bg-muted-bg border border-border-subtle px-4 text-cream" placeholder="09XXXXXXXXX">
            @error('customer_phone') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        @if($message)
            <p class="text-amber-400 text-sm">{{ $message }}</p>
        @endif
        <button type="submit" class="w-full min-h-14 rounded-xl bg-cream text-dark font-medium uppercase tracking-wider text-sm">Look up</button>
    </form>

    @if($booking)
        <x-booking-confirmation :booking="$booking" />
    @endif
</div>
