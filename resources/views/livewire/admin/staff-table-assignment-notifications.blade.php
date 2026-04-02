<div wire:poll.5s class="mb-3 space-y-2 px-0 md:px-0">
    @forelse ($notifications as $n)
        <div
            class="flex items-start gap-3 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-slate-800 shadow-sm shadow-amber-900/5"
            role="status"
            wire:key="staff-notif-{{ $n->id }}">
            <div class="mt-0.5 shrink-0 text-amber-600" aria-hidden="true">
                <i class="fa-solid fa-utensils"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-amber-950">{{ $n->title }}</p>
                <p class="mt-1 text-sm leading-relaxed text-amber-950/90">{{ $n->message }}</p>
            </div>
            <button
                type="button"
                wire:click="dismiss({{ $n->id }})"
                class="shrink-0 rounded-md border border-amber-300/80 bg-white px-2.5 py-1.5 text-xs font-medium text-amber-900 transition hover:bg-amber-100"
                title="Dismiss">
                Dismiss
            </button>
        </div>
    @empty
        {{-- No banners when nothing unread --}}
    @endforelse
</div>
