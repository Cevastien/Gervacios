<div class="w-full">
    @if ($sent)
        <p
            class="rounded-xl border border-cream/20 bg-cream/5 px-4 py-3 font-satoshi text-sm text-cream"
            role="status"
        >
            Thanks! We'll get back to you soon.
        </p>
    @endif

    <form wire:submit="submit" @class(['flex flex-col gap-3 md:gap-4', 'mt-4' => $sent])>
        <div>
            <label for="contact-name" class="sr-only">Name</label>
            <input
                id="contact-name"
                wire:model.blur="name"
                type="text"
                autocomplete="name"
                placeholder="Name"
                class="input-glow w-full bg-muted-bg rounded-[10px] py-3 px-4 lg:py-2.5 lg:px-4 text-cream text-sm font-satoshi placeholder:text-cream/40 focus:outline-none"
            />
            @error('name')
                <span class="mt-1 block text-xs text-red-400">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="contact-email" class="sr-only">Email</label>
            <input
                id="contact-email"
                wire:model.blur="email"
                type="email"
                autocomplete="email"
                placeholder="Email"
                class="input-glow w-full bg-muted-bg rounded-[10px] py-3 px-4 lg:py-2.5 lg:px-4 text-cream text-sm font-satoshi placeholder:text-cream/40 focus:outline-none"
            />
            @error('email')
                <span class="mt-1 block text-xs text-red-400">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="contact-message" class="sr-only">Message</label>
            <textarea
                id="contact-message"
                wire:model.blur="message"
                rows="4"
                placeholder="Message"
                class="input-glow w-full resize-y min-h-[100px] bg-muted-bg rounded-[10px] py-3 px-4 lg:py-2.5 lg:px-4 text-cream text-sm font-satoshi placeholder:text-cream/40 focus:outline-none"
            ></textarea>
            @error('message')
                <span class="mt-1 block text-xs text-red-400">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <button
                type="submit"
                wire:loading.attr="disabled"
                class="btn-slide-fill w-full border border-cream/30 rounded-lg py-3 px-6 font-satoshi text-xs tracking-[0.08em] uppercase text-cream disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="submit">Send Message</span>
                <span wire:loading wire:target="submit" class="inline-flex items-center justify-center gap-2">
                    <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Sending…
                </span>
            </button>
        </div>
    </form>
</div>
