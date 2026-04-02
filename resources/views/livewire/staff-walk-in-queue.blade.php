<div class="flex h-full min-h-0 w-full flex-1 flex-col overflow-hidden bg-panel-canvas">
    <div
        class="flex min-h-0 flex-1 flex-col overflow-y-auto overflow-x-hidden tc-scrollbar px-4 py-4 sm:px-6 sm:py-5">
        <div class="mx-auto w-full max-w-md">
            {{-- Compact local header bar (replaces duplicate app header title) --}}
            <div
                class="mb-3 flex flex-wrap items-center gap-x-2 gap-y-1 border-b border-slate-100 pb-3 sm:mb-4 sm:pb-3">
                <a href="{{ route('admin.dashboard') }}"
                    class="inline-flex shrink-0 items-center gap-1.5 text-[12px] font-semibold text-slate-600 transition-colors hover:text-panel-primary">
                    <i class="fa-solid fa-arrow-left text-[10px]" aria-hidden="true"></i>
                    Dashboard
                </a>
                <span class="select-none text-slate-300" aria-hidden="true">/</span>
                <h2 class="text-[15px] font-semibold leading-tight text-slate-900">Register walk-in</h2>
            </div>

            <div class="rounded-xl border border-slate-200/90 bg-white p-4 shadow-sm sm:p-5">
                <p class="mb-3 text-[12px] leading-snug text-slate-500">Adds the guest to the waitlist. SMS sends when
                    enabled.</p>

                <form wire:submit.prevent="register" class="space-y-3">
                    <div>
                        <label class="block text-[12px] font-semibold text-slate-700">Name</label>
                        <input type="text" wire:model.blur="customer_name"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400/25">
                        @error('customer_name')
                            <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-slate-700">Phone</label>
                        <input type="tel" wire:model.blur="customer_phone"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400/25">
                        @error('customer_phone')
                            <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-slate-700">Party size</label>
                        <input type="number" wire:model.blur="party_size" min="1" max="20"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400/25">
                        @error('party_size')
                            <p class="mt-1 text-xs font-medium text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-[12px] font-semibold text-slate-700">Priority</label>
                        <select wire:model="priority_type"
                            class="mt-1 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-800 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-400/25">
                            <option value="none">Standard</option>
                            <option value="pwd">PWD</option>
                            <option value="pregnant">Pregnant</option>
                            <option value="senior">Senior</option>
                        </select>
                    </div>

                    <button type="submit"
                        class="mt-1 w-full rounded-lg bg-panel-primary py-2.5 text-sm font-semibold text-panel-on-bright shadow-sm transition-colors hover:bg-panel-primary-hover">
                        Add to queue
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
