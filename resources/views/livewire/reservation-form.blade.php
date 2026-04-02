<div class="w-full min-w-0 max-w-[600px] min-h-0" x-data="{
        submitted: @entangle('success'),
        step: @entangle('step'),
        subStep: @entangle('subStep'),
        policiesExpanded: false,
        displayIdx: 0,
        stepLabels: ['Policy', 'Your Details', 'Payment', 'Confirm'],
    }" x-effect="
        displayIdx = submitted ? 3 : (step === 2 ? 2 : (step === 1 ? (subStep === 2 ? 2 : 1) : 0));
    ">

    {{-- Progress: 0 Policy → 1 Your Details → 2 Payment → 3 Confirm (time modal is not a step) --}}
    <nav class="mb-6 w-full min-w-0 border-b border-cream/10 pb-5" aria-label="Reservation steps">
        <ol class="grid grid-cols-4 gap-0.5 px-0.5 sm:gap-2 sm:px-0">
            <template x-for="(label, i) in stepLabels" :key="i">
                <li class="flex min-w-0 flex-col items-center gap-1 text-center sm:gap-1.5"
                    :aria-current="!submitted && displayIdx === i ? 'step' : null">
                    <span
                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full border text-[10px] font-semibold font-satoshi transition-colors sm:h-9 sm:w-9 sm:text-xs"
                        :class="(submitted || displayIdx > i)
                            ? 'bg-emerald-500/20 border-emerald-400 text-emerald-300'
                            : (displayIdx === i
                                ? 'border-cream bg-cream/15 text-cream shadow-[0_0_0_1px_rgba(239,231,210,0.25)]'
                                : 'border-cream/15 text-cream/35')"
                        x-text="(submitted || displayIdx > i) ? '✓' : (i + 1)"></span>
                    <span
                        class="w-full min-w-0 break-words px-0.5 text-[9px] font-satoshi uppercase leading-[1.15] tracking-[0.06em] sm:px-0 sm:text-xs sm:leading-tight sm:tracking-[0.1em] sm:break-normal"
                        :class="!submitted && displayIdx === i ? 'font-semibold text-cream' : ((submitted || displayIdx > i) ? 'text-cream/65' : 'text-cream/40')"
                        x-text="label"></span>
                </li>
            </template>
        </ol>
    </nav>

    {{-- ── Step 0: Policy ─────────────────────────────────────── --}}
    <div x-show="!submitted && step === 0" x-transition:enter="transition ease-out duration-400"
        x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        class="flex flex-col gap-4 lg:gap-3">
        <div
            class="border border-cream/10 rounded-xl p-6 lg:p-4 space-y-3 text-cream/80 text-sm font-satoshi leading-relaxed">
            <ul class="list-disc list-inside space-y-2">
                <li>Reservation fee: ₱150 per guest, deducted from bill upon dining</li>
                <li>Reservations confirmed only after fee is settled</li>
                <li>Children 4 and below: no reservation fee, exclude from guest count</li>
            </ul>
            <button type="button"
                class="text-cream/70 text-xs font-satoshi underline underline-offset-2 hover:text-cream mt-2"
                @click="policiesExpanded = !policiesExpanded">
                <span x-show="!policiesExpanded">Show all policies ▾</span>
                <span x-show="policiesExpanded">Show less ▴</span>
            </button>
            <ul class="list-disc list-inside space-y-2" x-show="policiesExpanded" x-transition>
                <li>Strict 10-minute grace period — late arrivals lose their table</li>
                <li>Cashless only: credit/debit cards or e-wallets</li>
                <li>Max 4 guests per booking; bigger groups book separately and may be seated apart</li>
                <li>Walk-in slots are always reserved even when fully booked</li>
                <li>Operating hours: Mon–Thu 11AM–11PM, Fri–Sun 10AM–11PM</li>
            </ul>
        </div>
        <button type="button" wire:click="confirmPolicy" wire:loading.attr="disabled"
            class="animate-fade-up btn-slide-fill w-full border border-cream/30 rounded-lg py-4 px-6 lg:py-2.5 lg:px-4 font-satoshi text-xs tracking-[0.08em] uppercase text-cream mt-2 lg:mt-1 disabled:opacity-50">
            <span wire:loading.remove wire:target="confirmPolicy">Confirm and continue</span>
            <span wire:loading wire:target="confirmPolicy" class="inline-flex items-center gap-2">
                <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Processing…
            </span>
        </button>
    </div>

    {{-- ── Step 1: Form ────────────────────────────────────────── --}}
    <div x-show="!submitted && step === 1" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <form wire:submit="{{ $this->formSubmitHandler }}"
            class="flex w-full min-w-0 flex-col gap-4 lg:gap-3">
            {{-- Honeypot — hidden from humans, bots fill it --}}
            <div aria-hidden="true"
                style="position:absolute;left:-9999px;top:-9999px;opacity:0;height:0;width:0;overflow:hidden;">
                <input type="text" wire:model="website" tabindex="-1" autocomplete="off" />
            </div>

            <div x-show="subStep === 1" class="flex w-full min-w-0 max-w-[600px] flex-col gap-4 lg:gap-3">
                {{-- Name --}}
                <div class="animate-fade-up" style="animation-delay: 600ms">
                    <label for="res-name" class="sr-only">Full Name</label>
                    <input id="res-name" wire:model.blur="name" type="text" placeholder="Name"
                        class="input-glow w-full bg-muted-bg rounded-[10px] py-4 px-6 lg:py-3 lg:px-4 text-cream text-sm font-satoshi placeholder:text-cream/40 focus:outline-none" />
                    @error('name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Phone Number --}}
                <div class="animate-fade-up" style="animation-delay: 700ms">
                    <label for="res-phone" class="sr-only">Phone Number</label>
                    <input id="res-phone" wire:model.blur="phone" type="tel" placeholder="Phone Number"
                        class="input-glow w-full bg-muted-bg rounded-[10px] py-4 px-6 lg:py-3 lg:px-4 text-cream text-sm font-satoshi placeholder:text-cream/40 focus:outline-none" />
                    @error('phone') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Email --}}
                <div class="animate-fade-up" style="animation-delay: 800ms">
                    <label for="res-email" class="sr-only">Email Address</label>
                    <input id="res-email" wire:model.blur="email" type="email" placeholder="Email"
                        class="input-glow w-full bg-muted-bg rounded-[10px] py-4 px-6 lg:py-3 lg:px-4 text-cream text-sm font-satoshi placeholder:text-cream/40 focus:outline-none" />
                    @error('email') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Guests / Date / Time --}}
                <div class="flex flex-col md:flex-row gap-4 lg:gap-3 animate-fade-up" style="animation-delay: 900ms">
                    <div class="flex-1 min-w-0">
                        <input id="reservation-guests" wire:model.blur="guests" type="number" inputmode="numeric"
                            min="1" max="{{ $maxGuests }}" step="1" placeholder="Guests"
                            aria-describedby="guests-help"
                            class="input-glow w-full bg-muted-bg rounded-[10px] py-4 px-6 lg:py-3 lg:px-3 text-cream text-sm font-satoshi placeholder:text-cream/40 focus:outline-none" />
                        <p id="guests-help" class="mt-1.5 text-xs font-satoshi leading-relaxed text-cream/55">
                            Maximum <span class="font-semibold text-cream/75">{{ $maxGuests }} guests</span> per booking
                            (matches our largest table). Larger groups must book separately — one reservation per party.
                        </p>
                        @error('guests') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex-1 min-w-0">
                        <label for="res-date" class="sr-only">Preferred Date</label>
                        <input id="res-date" wire:model.blur="date" type="date" min="{{ date('Y-m-d') }}"
                            class="input-glow w-full bg-muted-bg rounded-[10px] py-4 px-6 lg:py-3 lg:px-3 text-cream text-sm font-satoshi focus:outline-none" />
                        @error('date') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex-1 min-w-0">
                        <button type="button" wire:click="openSlotModal"
                            class="input-glow w-full bg-muted-bg rounded-[10px] py-4 px-6 lg:py-3 lg:px-3 text-cream text-sm font-satoshi text-left focus:outline-none">
                            {{ $time !== '' ? \Carbon\Carbon::parse($time)->format('g:i A') : 'Select time' }}
                        </button>
                        @error('time') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if($showSlotModal)
                    <div class="fixed inset-0 z-[100] flex items-end justify-center bg-black/70 p-2 pb-4 sm:items-center sm:p-4"
                        wire:click.self="closeSlotModal">
                        <div class="max-h-[min(85dvh,100dvh-2rem)] w-full min-w-0 max-w-lg overflow-y-auto overscroll-contain rounded-2xl border border-cream/10 bg-muted-bg p-3 sm:max-h-[85vh] sm:p-4 lg:p-6"
                            wire:click.stop>
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-cream font-satoshi text-sm font-semibold">Choose a time</span>
                                <button type="button" wire:click="closeSlotModal"
                                    class="text-cream/60 text-sm font-satoshi">Close</button>
                            </div>
                            @if ($slotModalNoAvailabilityAll)
                                <p class="text-cream/70 text-sm font-satoshi text-center py-4 leading-relaxed">
                                    No available time slots for this date. Please choose another date or check back later.
                                </p>
                            @else
                                @php
                                    $dinnerEnd12 = '10:00 PM';
                                    if (!empty($date)) {
                                        $dinnerDay = \Carbon\Carbon::parse($date);
                                        if ($dinnerDay->dayOfWeek === \Carbon\Carbon::SATURDAY) {
                                            $dinnerEnd12 = '11:00 PM';
                                        }
                                    }
                                    $categoryBoundaries12 = [
                                        'Breakfast' => '7:00 AM – 10:45 AM',
                                        'Lunch' => '11:00 AM – 2:45 PM',
                                        'Tea Time' => '3:00 PM – 5:45 PM',
                                        'Dinner' => '6:00 PM – ' . $dinnerEnd12,
                                    ];
                                @endphp
                                @foreach (['Breakfast', 'Lunch', 'Tea Time', 'Dinner'] as $catKey)
                                    @php
                                        $block = $slotsByCategory[$catKey] ?? ['inactive' => true, 'slots' => []];
                                    @endphp
                                    <div class="mb-4 last:mb-0 {{ ($block['inactive'] ?? false) ? 'opacity-50' : '' }}">
                                        <p class="text-cream font-forum text-lg uppercase tracking-wide mb-2">{{ $catKey }}</p>
                                        <p class="text-xs font-satoshi text-cream/50 mb-2">
                                            {{ $categoryBoundaries12[$catKey] ?? '' }}</p>
                                        @if ($block['inactive'] ?? false)
                                            <p class="text-xs font-satoshi text-cream/50 mb-2">Not available today</p>
                                        @endif
                                        @if (!empty($block['slots']))
                                            <div class="flex min-w-0 flex-wrap gap-2">
                                                @foreach ($block['slots'] as $slot)
                                                    @if ($slot['available'])
                                                        <button type="button" wire:click="selectSlot('{{ $slot['time'] }}')"
                                                            class="rounded-lg px-3 py-2 text-sm font-satoshi min-w-[4.5rem] text-center {{ $time === $slot['time'] ? 'border border-cream/50 text-cream bg-cream/10' : 'border border-cream/20 text-cream/90' }}">
                                                            {{ \Carbon\Carbon::parse($slot['time'])->format('g:i A') }}
                                                        </button>
                                                    @else
                                                        @php
                                                            $slotLabel = \Carbon\Carbon::parse($slot['time'])->format('g:i A');
                                                        @endphp
                                                        <span
                                                            class="inline-flex min-w-[4.5rem] cursor-not-allowed flex-col items-center justify-center gap-0.5 rounded-lg border border-cream/10 bg-black/25 px-3 py-2 text-center opacity-70 select-none"
                                                            aria-disabled="true" aria-label="{{ $slotLabel }}, full">
                                                            <span class="text-sm font-satoshi text-cream/40">{{ $slotLabel }}</span>
                                                            <span
                                                                class="text-[9px] font-satoshi font-semibold uppercase tracking-[0.14em] text-cream/30">Full</span>
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif

                <button type="button"
                    class="animate-fade-up btn-slide-fill w-full border border-cream/30 rounded-lg py-4 px-6 lg:py-2.5 lg:px-4 font-satoshi text-xs tracking-[0.08em] uppercase text-cream mt-2 lg:mt-1"
                    wire:click="validateStepOneA"
                    wire:loading.attr="disabled"
                    wire:target="validateStepOneA">
                    Next →
                </button>
            </div>

            <div x-show="subStep === 2" class="flex w-full min-w-0 max-w-[600px] flex-col gap-6 lg:gap-5">
                <button type="button"
                    class="self-start rounded-lg border border-cream/20 py-2 px-4 font-satoshi text-xs uppercase tracking-[0.08em] text-cream/60 hover:text-cream"
                    wire:click="goBackToGuestDetails">
                    ← Back
                </button>

                @if ($this->paymongoEnabled)
                    {{-- Payment method choice (only when PayMongo is configured) --}}
                    <section class="rounded-xl border border-cream/15 bg-black/20 p-5 shadow-[0_1px_0_0_rgba(255,255,255,0.04)_inset] lg:p-4"
                        aria-labelledby="heading-payment-method">
                        <h3 id="heading-payment-method" class="font-forum text-lg uppercase tracking-[0.06em] text-cream sm:text-xl">
                            How do you want to pay?</h3>
                        <p class="mt-1 text-xs font-satoshi text-cream/50">Choose one option before you continue.</p>
                        <div class="mt-4 flex flex-col gap-3 sm:grid sm:grid-cols-2 sm:gap-3" role="radiogroup" aria-label="Reservation payment method">
                            <label
                                class="flex w-full min-w-0 cursor-pointer flex-col gap-2 rounded-xl border p-4 transition-colors focus-within:ring-2 focus-within:ring-cream/30 sm:flex-1 {{ $reservationPaymentMode === 'online' ? 'border-cream/45 bg-cream/10 ring-1 ring-cream/25' : 'border-cream/15 hover:border-cream/25' }}">
                                <span class="flex items-start gap-3">
                                    <input type="radio" wire:model.live="reservationPaymentMode" value="online"
                                        class="mt-1 border-cream/40 text-cream focus:ring-cream/40" />
                                    <span>
                                        <span class="block font-satoshi text-sm font-semibold text-cream">Pay online</span>
                                        <span class="mt-0.5 block text-xs font-satoshi leading-snug text-cream/55">Card or GCash via PayMongo — secure checkout on the next step</span>
                                    </span>
                                </span>
                            </label>
                            <label
                                class="flex w-full min-w-0 cursor-pointer flex-col gap-2 rounded-xl border p-4 transition-colors focus-within:ring-2 focus-within:ring-cream/30 sm:flex-1 {{ $reservationPaymentMode === 'manual' ? 'border-cream/45 bg-cream/10 ring-1 ring-cream/25' : 'border-cream/15 hover:border-cream/25' }}">
                                <span class="flex items-start gap-3">
                                    <input type="radio" wire:model.live="reservationPaymentMode" value="manual"
                                        class="mt-1 border-cream/40 text-cream focus:ring-cream/40" />
                                    <span>
                                        <span class="block font-satoshi text-sm font-semibold text-cream leading-snug">Pay via GCash / QR — scan and enter your reference number</span>
                                        <span class="mt-0.5 block text-xs font-satoshi leading-snug text-cream/55">We'll show you our QR code below. Scan it, pay your deposit, then enter your reference number here for staff verification.</span>
                                    </span>
                                </span>
                            </label>
                        </div>
                    </section>
                @endif

                {{-- (1) Deposit: PayMongo online = summary only; manual or no PayMongo = QR + reference --}}
                @if ($this->paymongoEnabled && $reservationPaymentMode === 'online')
                    <section
                        class="rounded-xl border border-cream/15 bg-black/20 p-5 shadow-[0_1px_0_0_rgba(255,255,255,0.04)_inset] lg:p-4"
                        aria-labelledby="heading-pay-online">
                        <h3 id="heading-pay-online" class="font-forum text-lg uppercase tracking-[0.06em] text-cream sm:text-xl">
                            Pay online</h3>
                        <p class="mt-2 text-sm font-satoshi leading-relaxed text-cream/85">
                            You will review your deposit total on the next screen, then complete payment through PayMongo using a card, GCash, Maya, or other supported methods.
                        </p>
                        <p class="mt-3 text-xs font-satoshi text-cream/50">
                            No reference number is needed here — payment is confirmed automatically after checkout.
                        </p>
                    </section>
                @else
                    {{-- Manual QR: deposit totals first (matches PayMongo step 2 summary lines) --}}
                    <section
                        class="rounded-xl border border-cream/15 bg-black/20 p-5 shadow-[0_1px_0_0_rgba(255,255,255,0.04)_inset] lg:p-4"
                        aria-labelledby="heading-deposit-summary">
                        <h3 id="heading-deposit-summary" class="font-forum text-lg uppercase tracking-[0.06em] text-cream sm:text-xl">
                            Deposit summary</h3>
                        <div class="mt-4 space-y-2 border-t border-cream/10 pt-4 lg:space-y-1 lg:pt-2">
                            <div class="flex justify-between text-sm font-satoshi">
                                <span class="text-cream/50">Guests</span>
                                <span class="text-cream">{{ $guests }}</span>
                            </div>
                            <div class="flex justify-between text-sm font-satoshi">
                                <span class="text-cream/50">Deposit per guest</span>
                                <span class="text-cream">₱{{ number_format($this->depositPerGuest) }}</span>
                            </div>
                            <div class="flex justify-between text-sm font-satoshi">
                                <span class="text-cream/50">× {{ $guests }} guest(s)</span>
                                <span class="text-cream font-medium">&nbsp;</span>
                            </div>
                            <div class="flex justify-between border-t border-cream/10 pt-3 font-satoshi text-base font-medium">
                                <span class="text-cream">Total deposit</span>
                                <span class="text-cream text-lg">₱{{ number_format($this->totalDeposit) }}</span>
                            </div>
                        </div>
                    </section>

                    <section
                        class="rounded-xl border border-cream/15 bg-black/20 p-5 shadow-[0_1px_0_0_rgba(255,255,255,0.04)_inset] lg:p-4"
                        aria-labelledby="heading-pay-deposit">
                        <h3 id="heading-pay-deposit" class="font-forum text-lg uppercase tracking-[0.06em] text-cream sm:text-xl">
                            Pay Your Deposit</h3>
                        <p class="mt-1 text-xs font-satoshi text-cream/50">Scan the QR code, then enter your payment reference
                            number.</p>
                        <div class="mt-4 flex justify-center">
                            <img src="{{ asset(\App\Models\Setting::get('qr_image_path', 'images/qrcode.png')) }}?v={{ \App\Models\Setting::get('qr_updated_at') }}"
                                alt="Scan to pay deposit" class="mx-auto h-40 w-40 rounded-xl object-contain" />
                        </div>
                        <div class="mt-4 space-y-1 text-center">
                            <p class="text-xs font-satoshi text-cream/60">Send to</p>
                            <p class="text-sm font-semibold font-satoshi text-cream">
                                {{ \App\Models\Setting::get('qr_account_name', 'Gervacios') }}</p>
                            <p class="text-xs font-satoshi text-cream/60">
                                {{ \App\Models\Setting::get('qr_account_number', '+63 9•• ••• 1099') }}</p>
                            <p class="text-xs font-satoshi text-cream/40">
                                {{ \App\Models\Setting::get('qr_payment_label', 'GCash · InstaPay accepted') }}</p>
                        </div>
                        <p class="mt-3 text-center text-xs font-satoshi text-cream/60">GCash · Maya · Other e-wallets accepted
                        </p>
                        <p class="mt-4 text-sm font-satoshi leading-relaxed text-cream/85">
                            Pay the total deposit shown above, then enter your payment reference number below.
                        </p>
                        <label for="reservation-transaction-ref" class="sr-only">Payment reference number</label>
                        <input id="reservation-transaction-ref" wire:model.blur="transactionNumber" type="text"
                            inputmode="numeric" pattern="[0-9]*" maxlength="255" autocomplete="off"
                            placeholder="Reference No. (10+ digits)"
                            class="input-glow mt-3 w-full rounded-[10px] bg-muted-bg py-4 px-6 font-satoshi text-sm text-cream placeholder:text-cream/40 focus:outline-none lg:py-3 lg:px-3" />
                        @error('transactionNumber')
                            <span class="mt-1 block text-xs text-red-400">{{ $message }}</span>
                        @enderror
                    </section>
                @endif

                {{-- (2) Reservation Policy --}}
                <section
                    class="rounded-xl border border-cream/15 bg-black/20 p-5 shadow-[0_1px_0_0_rgba(255,255,255,0.04)_inset] lg:p-4"
                    aria-labelledby="heading-reservation-policy">
                    <h3 id="heading-reservation-policy"
                        class="font-forum text-lg uppercase tracking-[0.06em] text-cream sm:text-xl">Reservation Policy
                    </h3>
                    <p class="mt-1 text-xs font-satoshi text-cream/50">Please read and confirm before you submit.</p>
                    <div class="mt-4 space-y-3 border-t border-cream/10 pt-4">
                        <p class="text-sm font-semibold font-satoshi text-cream">I understand that:</p>
                        <p class="text-sm font-satoshi leading-relaxed text-cream/85">10-min late = auto-cancelled · Full
                            house = no guaranteed seating · Set meal required per guest · Dining guests only · Fee is
                            non-refundable &amp; non-transferable</p>
                        <label class="flex cursor-pointer items-start gap-2.5 text-sm font-satoshi text-cream/90">
                            <input type="checkbox" wire:model.live="policyAcknowledged"
                                class="mt-0.5 rounded border-cream/30" />
                            <span>I confirm I have read and understood the points above.</span>
                        </label>
                        @error('policyAcknowledged')
                            <span class="mt-1 block text-xs text-red-400">{{ $message }}</span>
                        @enderror
                    </div>
                </section>

                {{-- (3) Special Requests --}}
                <section
                    class="rounded-xl border border-cream/15 bg-black/20 p-5 shadow-[0_1px_0_0_rgba(255,255,255,0.04)_inset] lg:p-4"
                    aria-labelledby="heading-special-requests">
                    <h3 id="heading-special-requests" class="font-forum text-lg uppercase tracking-[0.06em] text-cream sm:text-xl">
                        Special Requests</h3>
                    <p id="special-requests-help" class="mt-1 text-xs font-satoshi text-cream/50">Optional — dietary needs,
                        occasion, accessibility, etc.</p>
                    <textarea wire:model.blur="specialRequests" rows="3"
                        placeholder="Optional dietary notes or occasion (we will try our best)"
                        aria-describedby="special-requests-help"
                        class="input-glow mt-4 min-h-[5rem] w-full resize-y rounded-[10px] bg-muted-bg py-4 px-6 font-satoshi text-sm text-cream placeholder:text-cream/40 focus:outline-none lg:py-3 lg:px-4"></textarea>
                    @error('specialRequests')
                        <span class="mt-1 block text-xs text-red-400">{{ $message }}</span>
                    @enderror
                </section>

                @if ($errorMessage)
                    <div
                        class="animate-fade-up rounded-lg border border-red-500/20 bg-red-500/10 px-4 py-3 font-satoshi text-sm text-red-300">
                        {{ $errorMessage }}
                    </div>
                @endif

                {{-- Privacy notice (RA 10173) --}}
                <p class="animate-fade-up text-[10px] font-satoshi leading-relaxed text-cream/30 lg:text-[9px] lg:leading-snug"
                    style="animation-delay: 950ms">
                    By submitting this form, you agree to the collection and use of your personal data solely for
                    reservation purposes, in accordance with the <strong>Philippine Data Privacy Act (RA
                        10173)</strong>. Your data will not be shared with third parties and will be automatically
                    deleted after 6 months.
                </p>

                <button type="submit" wire:loading.attr="disabled"
                    class="btn-slide-fill animate-fade-up mt-1 w-full rounded-lg border border-cream/30 py-4 px-6 font-satoshi text-xs uppercase tracking-[0.08em] text-cream disabled:opacity-50 lg:mt-0 lg:py-2.5 lg:px-4"
                    style="animation-delay: 1000ms">
                    @if (! $this->paymongoEnabled)
                        <span wire:loading.remove wire:target="submit,proceedToPayment">Reserve</span>
                    @elseif ($reservationPaymentMode === 'manual')
                        <span wire:loading.remove wire:target="submit,proceedToPayment">Submit reservation</span>
                    @else
                        <span wire:loading.remove wire:target="submit,proceedToPayment">Continue to Payment</span>
                    @endif
                    <span wire:loading wire:target="submit,proceedToPayment" class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Processing…
                    </span>
                </button>
            </div>
        </form>
    </div>

    {{-- ── Step 2: Deposit Payment ─────────────────────────────── --}}
    <div x-show="!submitted && step === 2" x-transition:enter="transition ease-out duration-400"
        x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        style="display: none;" class="flex flex-col gap-6 lg:gap-3">
        {{-- Summary --}}
        <div class="border border-cream/10 rounded-xl p-6 lg:p-4 space-y-4 lg:space-y-2">
            <h3 class="font-forum text-2xl lg:text-xl tracking-[0.025em] uppercase text-cream text-center">Reservation
                Deposit</h3>

            <div class="space-y-2 lg:space-y-1">
                <div class="flex justify-between text-sm font-satoshi">
                    <span class="text-cream/50">Name</span>
                    <span class="text-cream">{{ $name }}</span>
                </div>
                <div class="flex justify-between text-sm font-satoshi">
                    <span class="text-cream/50">Date & Time</span>
                    <span class="text-cream">{{ $date }} at {{ $time }}</span>
                </div>
                <div class="flex justify-between text-sm font-satoshi">
                    <span class="text-cream/50">Guests</span>
                    <span class="text-cream">{{ $guests }}</span>
                </div>
            </div>

            <div class="border-t border-cream/10 pt-4 lg:pt-2 space-y-2 lg:space-y-1">
                <div class="flex justify-between text-sm font-satoshi">
                    <span class="text-cream/50">Deposit per guest</span>
                    <span class="text-cream">₱{{ number_format($this->depositPerGuest) }}</span>
                </div>
                <div class="flex justify-between text-sm font-satoshi">
                    <span class="text-cream/50">× {{ $guests }} guest(s)</span>
                    <span class="text-cream font-medium">&nbsp;</span>
                </div>
                <div class="flex justify-between font-satoshi text-base font-medium border-t border-cream/10 pt-3">
                    <span class="text-cream">Total Deposit</span>
                    <span class="text-cream text-lg">₱{{ number_format($this->totalDeposit) }}</span>
                </div>
            </div>
        </div>

        <p class="text-center text-cream/40 text-xs lg:text-[11px] font-satoshi leading-relaxed lg:leading-snug">
            You will be redirected to PayMongo's secure checkout to complete payment via GCash, Maya, or card.
        </p>

        @if($errorMessage)
            <div class="bg-red-500/10 border border-red-500/20 rounded-lg px-4 py-3 text-sm text-red-300 font-satoshi">
                {{ $errorMessage }}
            </div>
        @endif

        <div class="flex gap-3 lg:gap-3">
            <button wire:click="backToForm" type="button"
                class="flex-1 border border-cream/20 rounded-lg py-4 px-6 lg:py-2.5 lg:px-3 font-satoshi text-xs tracking-[0.08em] uppercase text-cream/60 hover:text-cream hover:border-cream/40 transition-all">
                ← Back
            </button>

            <button wire:click="payDeposit" wire:loading.attr="disabled" type="button"
                class="flex-[2] btn-slide-fill border border-cream/30 rounded-lg py-4 px-6 lg:py-2.5 lg:px-3 font-satoshi text-xs tracking-[0.08em] uppercase text-cream disabled:opacity-50">
                <span wire:loading.remove wire:target="payDeposit">Pay ₱{{ number_format($this->totalDeposit) }}
                    Deposit</span>
                <span wire:loading wire:target="payDeposit" class="inline-flex items-center gap-2">
                    <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                        </path>
                    </svg>
                    Creating payment…
                </span>
            </button>
        </div>
    </div>

    {{-- ── Success after submit() — PayMongo checkout redirects away; manual_qr shows awaiting-verification copy --}}
    <div x-show="submitted" x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0"
        style="display: none;"
        class="mx-auto flex max-w-lg flex-col items-center gap-6 rounded-2xl border border-emerald-500/20 bg-[radial-gradient(ellipse_100%_80%_at_50%_0%,rgba(16,185,129,0.12),transparent_55%)] px-6 py-12 shadow-[0_24px_80px_-32px_rgba(16,185,129,0.25)]">
        <div
            class="flex h-16 w-16 items-center justify-center rounded-full border-2 border-emerald-400/50 bg-emerald-500/15 shadow-[0_8px_32px_rgba(16,185,129,0.25)] ring-4 ring-emerald-500/10">
            <svg class="h-8 w-8 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        @if ($successPaymentMethod === 'manual_qr')
            <h3 class="text-center font-forum text-3xl uppercase tracking-[0.025em] text-emerald-100">
                Booking Submitted — Awaiting Verification
            </h3>

            <div class="max-w-sm space-y-4 text-center font-satoshi text-sm leading-relaxed text-emerald-100/75">
                <div class="text-center my-4">
                    <p class="text-xs uppercase tracking-widest opacity-60 mb-1">
                        Booking Reference
                    </p>
                    <p class="text-2xl font-bold tracking-widest select-all">
                        {{ $bookingRef }}
                    </p>
                    <p class="text-xs opacity-50 mt-1">
                        Tap to select and copy
                    </p>
                </div>
                <p>
                    We've received your booking. Our staff will confirm your reservation shortly.
                </p>
            </div>
        @else
            <h3 class="text-center font-forum text-3xl uppercase tracking-[0.025em] text-emerald-100">
                Reservation Confirmed
            </h3>

            <div class="max-w-sm text-center font-satoshi text-sm leading-relaxed text-emerald-100/75">
                <div class="text-center my-4">
                    <p class="text-xs uppercase tracking-widest opacity-60 mb-1">
                        Booking Reference
                    </p>
                    <p class="text-2xl font-bold tracking-widest select-all">
                        {{ $bookingRef }}
                    </p>
                    <p class="text-xs opacity-50 mt-1">
                        Tap to select and copy
                    </p>
                </div>
                <p>
                    We've received your booking. Our staff will confirm your reservation shortly.
                </p>
            </div>
        @endif

        <a href="/" wire:navigate
            class="mt-2 font-satoshi text-xs uppercase tracking-[0.08em] text-emerald-200/65 transition-colors hover:text-emerald-100">
            ← Back to Home
        </a>
    </div>

</div>