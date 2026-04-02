{{--
    Seat editor modal — rounded shell (matches admin panels), CLOSE above panel.
    IDs / includes must stay stable for seating-layout.js.
--}}
<div id="seat-modal"
    class="seat-modal--editorial fixed inset-0 z-[999] items-center justify-center bg-black/40 p-4 backdrop-blur-md sm:p-6">
    <div class="relative mx-auto flex w-full max-w-[min(100%,480px)] flex-col">
        {{-- CLOSE + × above the box --}}
        <div class="mb-2 flex justify-end">
            <button type="button" id="seat-modal-close"
                class="inline-flex items-center gap-2 border-0 bg-transparent py-1 pl-2 text-[11px] font-medium uppercase tracking-[0.22em] text-black transition hover:opacity-60 focus:outline-none focus-visible:ring-2 focus-visible:ring-black focus-visible:ring-offset-2"
                aria-label="Close dialog">
                <span>Close</span>
                <span class="text-lg font-light leading-none" aria-hidden="true">&times;</span>
            </button>
        </div>

        {{--
          Height: one scroll region on the panel (not nested), max height = viewport minus outer padding + close row.
          Content stacks naturally so typical forms don’t clip before the fold.
        --}}
        <div
            class="seat-modal__panel w-full max-h-[calc(100dvh-5.5rem)] overflow-y-auto overscroll-contain rounded-xl border border-slate-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.12)]"
            role="dialog"
            aria-modal="true"
            aria-labelledby="seat-modal-dialog-label seat-modal-title">
            <div class="seat-modal__content flex flex-col px-5 py-6 text-black sm:px-7 sm:py-7">
                <header class="mb-5 shrink-0 border-b border-slate-200 pb-4">
                    <p id="seat-modal-dialog-label"
                        class="text-[10px] font-semibold uppercase tracking-[0.28em] text-neutral-500">
                        Table &amp; seat editor
                    </p>
                    <h3 id="seat-modal-title"
                        class="mt-2 text-base font-bold uppercase leading-snug tracking-[0.14em] text-black"></h3>
                    <p id="seat-modal-sub" class="mt-1.5 text-[13px] leading-relaxed text-neutral-500"></p>
                </header>

                <div class="seat-modal__body shrink-0">
                    @include('admin.partials.seat-modal.form')
                    @include('admin.partials.seat-modal.status')
                </div>

                <div class="seat-modal__footer mt-5 shrink-0 border-t border-slate-200 pt-5">
                    @include('admin.partials.seat-modal.actions')
                </div>
            </div>
        </div>
    </div>
</div>
