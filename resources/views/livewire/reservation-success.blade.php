<div
    @if ($shouldPoll)
        wire:poll.5s="checkPaymentStatus"
    @endif
    class="flex flex-1 flex-col items-center justify-center gap-6 rounded-2xl border-0 px-4 py-8 md:gap-8 md:border md:border-emerald-500/25 md:bg-[radial-gradient(ellipse_120%_80%_at_50%_0%,rgba(16,185,129,0.14),transparent_55%)] md:px-12 md:py-16 md:shadow-[0_0_0_1px_rgba(16,185,129,0.08),0_24px_80px_-24px_rgba(16,185,129,0.2)] lg:px-24 lg:py-20">

    @if ($timedOut)
        <p class="max-w-md text-center font-satoshi text-base leading-relaxed text-emerald-100/90">
            Payment is taking longer than expected. Please check your email or contact us.
        </p>
        @if ($bookingRef)
            <p class="font-forum text-sm tracking-wider text-emerald-200/70">Ref: {{ $bookingRef }}</p>
        @endif
        <a href="/" wire:navigate
            class="mt-4 font-satoshi text-xs uppercase tracking-[0.08em] text-emerald-200/60 transition-colors hover:text-emerald-100">
            ← Back to Home
        </a>
    @elseif ($booking && $this->isPaymongoPending($booking))
        <div class="flex h-16 w-16 items-center justify-center rounded-full border-2 border-emerald-400/50 bg-emerald-500/15 shadow-[0_8px_32px_rgba(16,185,129,0.25)] ring-4 ring-emerald-500/10 md:h-20 md:w-20">
            <svg class="h-9 w-9 animate-spin text-emerald-300 md:h-11 md:w-11" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </div>
        <h2 class="text-center font-forum text-[clamp(24px,4vw,36px)] uppercase leading-[1.2] tracking-[0.025em] text-emerald-100">
            Processing your payment...
        </h2>
        <p class="max-w-md text-center font-satoshi text-sm text-emerald-100/70">
            Please wait while we confirm your PayMongo payment. This page will update automatically.
        </p>
        @if ($bookingRef)
            <p class="font-satoshi text-xs uppercase tracking-wider text-emerald-200/50">Booking reference</p>
            <p class="font-forum text-lg tracking-wider text-emerald-50">{{ $bookingRef }}</p>
        @endif
        <a href="/" wire:navigate
            class="mt-6 font-satoshi text-xs uppercase tracking-[0.08em] text-emerald-200/60 transition-colors hover:text-emerald-100">
            ← Back to Home
        </a>
    @else
        @php
            $successBooking = $booking;
            $successRef = $bookingRef;
            $successIcsHref = '';
            if ($successBooking && $successBooking->booked_at) {
                $dtStart = $successBooking->booked_at->copy()->timezone(config('app.timezone'));
                $dtEnd = $dtStart->copy()->addMinutes((int) config('operations.occupancy_duration_minutes', 90));
                $host = parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost';
                $icsBody = implode("\r\n", [
                    'BEGIN:VCALENDAR',
                    'VERSION:2.0',
                    'PRODID:-//Cafe Gervacios//Reservation//EN',
                    'CALSCALE:GREGORIAN',
                    'BEGIN:VEVENT',
                    'UID:' . $successBooking->booking_ref . '@' . $host,
                    'DTSTAMP:' . now()->utc()->format('Ymd\THis\Z'),
                    'DTSTART:' . $dtStart->utc()->format('Ymd\THis\Z'),
                    'DTEND:' . $dtEnd->utc()->format('Ymd\THis\Z'),
                    'SUMMARY:Reservation — ' . config('app.venue_name', config('app.name')),
                    'DESCRIPTION:Booking ref ' . $successBooking->booking_ref,
                    'LOCATION:' . config('app.venue_name', config('app.name')),
                    'END:VEVENT',
                    'END:VCALENDAR',
                ]);
                $successIcsHref = 'data:text/calendar;charset=utf-8,' . rawurlencode($icsBody);
            }
            $tablePendingAssignment = $successBooking !== null && $successBooking->table_id === null;
        @endphp

        <div
            class="animate-fade-up flex h-16 w-16 items-center justify-center rounded-full border-2 border-emerald-400/50 bg-emerald-500/15 shadow-[0_8px_32px_rgba(16,185,129,0.25)] ring-4 ring-emerald-500/10 md:h-20 md:w-20">
            <svg class="h-8 w-8 text-emerald-300 md:h-10 md:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2.25">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h2 class="animate-fade-up text-center font-forum text-[clamp(24px,4vw,36px)] uppercase leading-[1.2] tracking-[0.025em] text-emerald-100"
            style="animation-delay: 100ms">
            Reservation Confirmed
        </h2>

        <div class="animate-fade-up max-w-md space-y-3 text-center font-satoshi text-sm leading-relaxed text-emerald-100/75"
            style="animation-delay: 200ms">
            @if ($tablePendingAssignment)
                <p>
                    Your deposit has been received. Your booking is confirmed. You may receive an SMS confirmation if enabled.
                </p>
                <p class="font-medium text-emerald-100/90">
                    Our team will assign your table and confirm the details before your arrival.
                </p>
            @elseif ($successBooking)
                <p>
                    Your deposit has been received and your table is secured. Your booking is confirmed. You may receive an SMS confirmation if enabled.
                </p>
            @else
                <p>
                    Your deposit has been received. Your booking is confirmed. You may receive an SMS confirmation if enabled.
                </p>
            @endif
        </div>

        @if ($successRef)
            <div class="animate-fade-up rounded-xl border border-emerald-500/25 bg-emerald-950/30 px-6 py-4 text-center shadow-inner md:px-8"
                style="animation-delay: 300ms">
                <p class="mb-1 font-satoshi text-xs uppercase tracking-wider text-emerald-200/60">Booking Reference</p>
                <p class="font-forum text-xl tracking-wider text-emerald-50 md:text-2xl">{{ $successRef }}</p>
            </div>
        @endif

        <div class="animate-fade-up flex items-center gap-2 text-emerald-300/90" style="animation-delay: 400ms">
            <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span class="font-satoshi text-xs font-medium">Paid via PayMongo</span>
        </div>

        @if ($successRef)
            <div class="w-full max-w-lg flex flex-col gap-6 md:gap-8 text-left animate-fade-up" style="animation-delay: 450ms">
                <div class="w-full space-y-2 text-center">
                    <p class="font-satoshi text-xs uppercase tracking-wider text-emerald-200/50">Unique reservation ID</p>
                    <p class="break-all font-forum text-3xl tracking-wider text-emerald-50 md:text-4xl">{{ $successRef }}</p>
                </div>

                <div class="flex flex-wrap justify-center gap-2">
                    <button type="button"
                        class="rounded-lg border border-emerald-500/35 px-4 py-2 font-satoshi text-xs uppercase tracking-[0.08em] text-emerald-100 transition-colors hover:bg-emerald-950/40"
                        onclick="(function(){var r=@json($successRef);var t='Reservation '+r;var n={{ json_encode(config('app.venue_name', config('app.name'))) }};if(navigator.share){navigator.share({title:t,text:'Your reservation at '+n,url:window.location.href}).catch(function(){});}else if(navigator.clipboard){navigator.clipboard.writeText(window.location.href).then(function(){alert('Link copied to clipboard');}).catch(function(){});}else{prompt('Copy link:',window.location.href);}})()">
                        Share
                    </button>
                    <a href="mailto:{{ config('mail.from.address', 'hello@example.com') }}?subject={{ rawurlencode('Cancel reservation '.$successRef) }}&body={{ rawurlencode('Please cancel my reservation. Booking reference: '.$successRef) }}"
                        class="inline-flex items-center rounded-lg border border-emerald-500/35 px-4 py-2 font-satoshi text-xs uppercase tracking-[0.08em] text-emerald-100 transition-colors hover:bg-emerald-950/40">
                        Cancel
                    </a>
                    @if ($successIcsHref !== '')
                        <a href="{{ $successIcsHref }}" download="reservation-{{ $successRef }}.ics"
                            class="inline-flex items-center rounded-lg border border-emerald-500/35 px-4 py-2 font-satoshi text-xs uppercase tracking-[0.08em] text-emerald-100 transition-colors hover:bg-emerald-950/40">
                            Add to Calendar
                        </a>
                    @endif
                </div>

                @if ($successBooking)
                    @php
                        $tz = config('app.timezone');
                        $bookedLocal = $successBooking->booked_at?->timezone($tz);
                    @endphp
                    <div class="w-full space-y-3 rounded-xl border border-emerald-500/15 bg-emerald-950/20 p-4 md:p-6">
                        <p class="text-center font-forum text-lg uppercase tracking-wide text-emerald-100">Your reservation</p>
                        <div class="flex justify-between gap-2 font-satoshi text-sm">
                            <span class="text-emerald-200/65">Guest name</span>
                            <span class="text-right font-medium text-emerald-50">{{ $successBooking->customer_name }}</span>
                        </div>
                        <div class="flex justify-between gap-2 font-satoshi text-sm">
                            <span class="text-emerald-200/65">Date</span>
                            <span class="text-emerald-50">{{ $bookedLocal?->format('M j, Y') ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between gap-2 font-satoshi text-sm">
                            <span class="text-emerald-200/65">Time</span>
                            <span class="text-emerald-50">{{ $bookedLocal?->format('g:i A') ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between gap-2 font-satoshi text-sm">
                            <span class="text-emerald-200/65">Party size</span>
                            <span class="text-emerald-50">{{ $successBooking->party_size }} {{ \Illuminate\Support\Str::plural('guest', (int) $successBooking->party_size) }}</span>
                        </div>
                        <div class="flex justify-between gap-2 font-satoshi text-sm">
                            <span class="text-emerald-200/65">Deposit paid</span>
                            <span class="text-right font-medium text-emerald-50">
                                @if ((int) $successBooking->deposit_amount > 0)
                                    ₱{{ number_format((int) $successBooking->deposit_amount) }}
                                @else
                                    <span class="font-normal text-emerald-200/80">Included with your online payment</span>
                                @endif
                            </span>
                        </div>
                        @if ($successBooking->special_requests !== null && trim((string) $successBooking->special_requests) !== '')
                            <div class="border-t border-emerald-500/15 pt-3 font-satoshi text-sm">
                                <span class="mb-1 block text-emerald-200/65">Notes you left us</span>
                                <span class="whitespace-pre-wrap text-emerald-50/95">{{ $successBooking->special_requests }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="w-full space-y-3 rounded-xl border border-emerald-500/15 bg-emerald-950/10 p-4 md:p-6">
                    <p class="text-center font-forum text-lg uppercase tracking-wide text-emerald-100">Venue &amp; contact</p>
                    <p class="text-center font-satoshi text-sm font-semibold text-emerald-50">
                        {{ config('app.venue_name', config('app.name')) }}</p>
                    <p class="text-center font-satoshi text-sm leading-relaxed text-emerald-100/80">
                        Café Gervacios<br>
                        123 Sample Street, Makati City, Metro Manila, Philippines
                    </p>
                    <p class="text-center font-satoshi text-sm text-emerald-100">
                        <a href="tel:+639171234567" class="underline decoration-emerald-500/50 underline-offset-2 hover:text-emerald-50">+63 917 123 4567</a>
                    </p>
                    @if (config('mail.from.address'))
                        <p class="text-center font-satoshi text-xs text-emerald-200/60">
                            <a href="mailto:{{ config('mail.from.address') }}"
                                class="underline decoration-emerald-500/40 underline-offset-2 hover:text-emerald-100">{{ config('mail.from.address') }}</a>
                        </p>
                    @endif
                    <div class="aspect-video w-full overflow-hidden rounded-xl border border-emerald-500/20 bg-muted-bg">
                        <iframe
                            title="Restaurant location"
                            src="https://www.google.com/maps?q=14.5547%2C121.0244&z=15&output=embed"
                            class="w-full h-full min-h-[200px] border-0"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            allowfullscreen
                        ></iframe>
                    </div>
                    <div class="flex flex-wrap justify-center gap-4 pt-2">
                        <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" class="font-satoshi text-sm text-emerald-200/80 underline decoration-emerald-500/40 underline-offset-2 hover:text-emerald-100">Instagram</a>
                        <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" class="font-satoshi text-sm text-emerald-200/80 underline decoration-emerald-500/40 underline-offset-2 hover:text-emerald-100">Facebook</a>
                        <a href="https://www.tiktok.com/" target="_blank" rel="noopener noreferrer" class="font-satoshi text-sm text-emerald-200/80 underline decoration-emerald-500/40 underline-offset-2 hover:text-emerald-100">TikTok</a>
                    </div>
                </div>
            </div>
        @endif

        <a href="/" wire:navigate
            class="animate-fade-up mt-4 font-satoshi text-xs uppercase tracking-[0.08em] text-emerald-200/60 transition-colors hover:text-emerald-100"
            style="animation-delay: 500ms">
            ← Back to Home
        </a>
    @endif
</div>
