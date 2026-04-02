@props(['booking'])
@php
    /** @var \App\Models\Booking $booking */
@endphp
<div {{ $attributes->merge(['class' => 'rounded-2xl border border-border-subtle bg-muted-bg p-6 space-y-3 text-left']) }}>
    <div class="flex justify-between gap-2 text-xs uppercase tracking-wider text-cream/50">
        <span>Reference</span>
        <span class="text-cream font-mono">{{ $booking->booking_ref }}</span>
    </div>
    <div class="flex justify-between gap-2 text-sm">
        <span class="text-cream/70">Guest</span>
        <span class="text-cream font-medium">{{ $booking->customer_name }}</span>
    </div>
    <div class="flex justify-between gap-2 text-sm">
        <span class="text-cream/70">Party</span>
        <span class="text-cream">{{ $booking->party_size }}</span>
    </div>
    <div class="flex justify-between gap-2 text-sm">
        <span class="text-cream/70">When</span>
        <span class="text-cream">{{ $booking->booked_at?->timezone(config('app.timezone'))->format('M j, g:i A') }}</span>
    </div>
    <div class="flex justify-between gap-2 text-sm">
        <span class="text-cream/70">Source</span>
        <span class="text-cream capitalize">{{ $booking->source ?? 'website' }} · {{ $booking->device_type ?? '—' }}</span>
    </div>
    <div class="flex justify-between gap-2 text-sm">
        <span class="text-cream/70">Status</span>
        <span class="text-cream capitalize">{{ $booking->status }} / {{ $booking->payment_status }}</span>
    </div>
    @if($booking->checked_in_at)
        <p class="text-xs text-green-400/90 pt-2">Checked in {{ $booking->checked_in_at->diffForHumans() }}</p>
    @endif
</div>
