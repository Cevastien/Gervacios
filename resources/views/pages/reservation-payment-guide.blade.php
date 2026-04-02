@php
    $reservationFee = (int) \App\Models\Setting::get('reservation_fee', 150);
    $paymentQrPath = (string) \App\Models\Setting::get('qr_image_path', '');
    $paymentQrConfigured = $paymentQrPath !== '' && file_exists(public_path($paymentQrPath));
@endphp
<x-app-layout>
    <x-slot:title>Payment Instructions — Café Gervacios</x-slot:title>

    <div class="min-h-screen w-full px-4 py-10 md:py-16 max-w-3xl mx-auto">
        <h1 class="font-forum text-3xl md:text-4xl text-cream uppercase tracking-wide text-center mb-10">
            Payment Instructions
        </h1>

        <div class="space-y-10 text-cream/90 font-satoshi text-sm md:text-base leading-relaxed">
            <section class="space-y-3">
                <p>
                    <span class="font-semibold text-cream">Step 1:</span>
                    Scan the QR code using your preferred payment platform.
                </p>
                <div class="flex justify-center py-4">
                    <div class="inline-block p-4 bg-white rounded-xl border border-cream/10">
                        @if ($paymentQrConfigured)
                            <img
                                src="{{ asset($paymentQrPath) }}?v={{ \App\Models\Setting::get('qr_updated_at') }}"
                                alt="Payment QR code"
                                width="200"
                                height="200"
                                class="mx-auto h-[200px] w-[200px] object-contain"
                            />
                        @else
                            <div
                                class="flex h-[200px] w-[200px] max-w-full flex-col items-center justify-center gap-2 rounded-lg border border-dashed border-zinc-300 bg-zinc-50 px-4 text-center font-satoshi text-sm leading-snug text-zinc-600"
                                role="status"
                            >
                                <span>QR code not yet configured.</span>
                                <span>Please contact the restaurant directly.</span>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <section class="space-y-2">
                <p>
                    <span class="font-semibold text-cream">Step 2:</span>
                    Pay the total reservation fee based on group size:
                </p>
                <ul class="list-disc list-inside space-y-1 text-cream/80 pl-1">
                    <li>1 guest = ₱{{ number_format($reservationFee) }} | 2 guests = ₱{{ number_format($reservationFee * 2) }} | 3 guests = ₱{{ number_format($reservationFee * 3) }} | 4 guests = ₱{{ number_format($reservationFee * 4) }} | Children 4 and below (high chair) = free</li>
                </ul>
            </section>

            <section>
                <p>
                    <span class="font-semibold text-cream">Step 3:</span>
                    Take a screenshot or save your proof of payment.
                </p>
            </section>

            <section class="space-y-2">
                <p>
                    <span class="font-semibold text-cream">Step 4:</span>
                    Find your <span class="text-cream font-semibold">payment reference number</span> in your e-wallet or bank receipt (the number you will type into the booking form):
                </p>
                <ul class="list-disc list-inside space-y-1 text-cream/80 pl-1">
                    <li>GCash → QRPH invoice number</li>
                    <li>Maya → Payment ID</li>
                    <li>Other apps → reference or transaction number shown on your receipt</li>
                </ul>
            </section>

            <section class="space-y-2">
                <p>
                    <span class="font-semibold text-cream">Step 5:</span>
                    Return to <a href="{{ route('reservation') }}" class="text-cream underline underline-offset-2 hover:text-cream/90">Book a Table</a>, go through guest details, then on the <span class="text-cream font-semibold">Pay Your Deposit</span> step enter that number in the field labeled <span class="text-cream font-semibold">Reference No. (10+ digits)</span>.
                </p>
                <p class="text-cream/80 text-sm">
                    Enter <span class="text-cream font-medium">digits only</span> (at least 10). Do not add spaces or dashes. The recipient account name and number are shown next to the QR on the form for your reference — you do not type them into a separate field.
                </p>
                <p class="text-cream/80 text-sm">
                    Then confirm the reservation policy, add any optional special requests, and use the main button at the bottom (<span class="text-cream font-medium">Reserve</span> or <span class="text-cream font-medium">Continue to Payment</span>, depending on your venue).
                </p>
            </section>

            <section class="space-y-3 pt-4 border-t border-cream/10">
                <h2 class="font-forum text-xl text-cream uppercase tracking-wide">How to Redeem on Arrival</h2>
                <ol class="list-decimal list-inside space-y-2 text-cream/80">
                    <li>Show confirmation (email or screenshot) to receptionist</li>
                    <li>Tell cashier about reservation fee before ordering at kiosk</li>
                    <li>Cashier credits fee to your total bill</li>
                </ol>
            </section>

            <section class="space-y-3 pt-4 border-t border-cream/10">
                <h2 class="font-forum text-xl text-cream uppercase tracking-wide">Reservation Policy</h2>
                <ul class="list-disc list-inside space-y-2 text-cream/80">
                    <li>Fee is non-refundable and non-transferable</li>
                    <li>No-show or beyond 10-min grace = reservation and fee forfeited</li>
                    <li>Unpaid reservations may be cancelled without notice</li>
                    <li>Fewer guests than reserved = fee for absent guests forfeited</li>
                    <li>Team may contact guest to verify payment details</li>
                </ul>
            </section>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('reservation') }}" class="font-satoshi text-xs tracking-[0.08em] uppercase text-cream/60 hover:text-cream">
                ← Back to Book a Table
            </a>
        </div>
    </div>
</x-app-layout>
