{{--
    Modal shell for Livewire (use inside a Livewire component so $wire is available).
    Rounded card, slate borders, frosted dark overlay — matches admin panels & seat editor modal.
    @props:
      title, titleId, descriptionId (aria-describedby + sr-only helper), maxWidth, closeAction, showFooter
--}}
@props([
    'title' => '',
    'titleId' => 'modal-base-title',
    'descriptionId' => 'modal-base-desc',
    'maxWidth' => 'max-w-lg',
    'closeAction' => 'closeSettingsModal',
    'showFooter' => true,
])

<div
    {{ $attributes->merge([
        'class' =>
            'fixed inset-0 z-[110] flex max-sm:items-stretch max-sm:justify-center sm:items-center sm:justify-center p-0 sm:p-6 md:p-10',
    ]) }}
    @keydown.escape.window="$wire.{{ $closeAction }}()"
    role="presentation"
>
    <div
        class="absolute inset-0 bg-black/40 backdrop-blur-md transition-opacity duration-200 ease-out"
        wire:click="{{ $closeAction }}"
        aria-hidden="true"
    ></div>

    <div
        x-data="{ open: true }"
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-init="$nextTick(() => {
            const root = $el;
            const first = root.querySelector(
                'input:not([type=hidden]):not([type=file]), select, textarea, [data-modal-initial-focus]'
            );
            if (first && typeof first.focus === 'function') {
                first.focus();
            } else {
                const closeB = root.querySelector('[data-modal-close-btn]');
                if (closeB && typeof closeB.focus === 'function') closeB.focus();
            }
        })"
        class="{{ $maxWidth }} relative z-10 flex max-h-[min(92dvh,900px)] w-full flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.12)] sm:mx-auto sm:max-h-[90vh] max-sm:min-h-0 max-sm:flex-1"
        wire:click.stop
        role="dialog"
        aria-modal="true"
        aria-labelledby="{{ $titleId }}"
        tabindex="-1"
    >
        <header
            class="relative flex shrink-0 items-start justify-between gap-4 border-b border-slate-200 px-5 py-5 sm:px-8 sm:py-6"
        >
            <h2
                id="{{ $titleId }}"
                class="max-w-[85%] font-sans text-sm font-bold uppercase leading-tight tracking-[0.18em] text-slate-900"
            >
                {{ $title }}
            </h2>
            <button
                type="button"
                data-modal-close-btn
                wire:click="{{ $closeAction }}"
                class="group inline-flex shrink-0 items-center gap-2 border-0 bg-transparent pb-0.5 pl-2 pt-0 text-[11px] font-medium uppercase tracking-[0.2em] text-slate-700 transition hover:opacity-60 focus:outline-none focus-visible:ring-2 focus-visible:ring-panel-primary focus-visible:ring-offset-2"
                aria-label="Close dialog"
            >
                <span>Close</span>
                <span class="text-base font-light leading-none" aria-hidden="true">&times;</span>
            </button>
        </header>

        <div
            class="min-h-0 flex-1 overflow-y-auto overscroll-contain px-5 py-6 text-sm leading-relaxed text-slate-900 sm:px-8 sm:py-8"
        >
            <p id="{{ $descriptionId }}" class="sr-only">
                Use the form below. Save applies your changes; Cancel or Escape closes this dialog.
            </p>
            {{ $slot }}
        </div>

        @if ($showFooter && isset($footer))
            <footer
                class="sticky bottom-0 z-10 flex shrink-0 flex-wrap items-center justify-end gap-2 border-t border-slate-200 bg-white px-5 py-5 sm:gap-3 sm:px-8"
            >
                {{ $footer }}
            </footer>
        @endif
    </div>
</div>
