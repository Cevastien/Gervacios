<div class="overflow-hidden rounded-[14px] border border-panel-stroke bg-[#eef1f5] shadow-[0_1px_3px_rgba(26,34,50,0.10)]">
    <div class="border-b border-panel-stroke px-4 py-3 sm:px-5">
        <h2 class="text-base font-semibold text-panel-primary">Activity logs</h2>
        <p class="mt-0.5 text-sm text-[#5a6a7e]">Who did what, and when</p>
    </div>

    <div class="border-b border-panel-stroke bg-white px-4 py-3 sm:px-5">
        <div class="flex flex-col gap-2.5 md:flex-row md:items-center md:justify-between md:gap-4">
            <div class="relative w-full max-w-sm shrink-0">
                <label for="logs-q-live" class="sr-only">Search logs</label>
                <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[13px] text-[#5a6a7e]"
                    aria-hidden="true"></i>
                <input id="logs-q-live" type="search" wire:model.live.debounce.400ms="q" placeholder="Search…"
                    autocomplete="off"
                    class="w-full rounded-lg border border-panel-stroke bg-white py-2 pl-9 pr-3 text-sm text-panel-primary placeholder:text-[#94a3b8] focus:border-panel-primary focus:outline-none focus:ring-2 focus:ring-panel-primary/15">
            </div>
            <div class="flex flex-wrap items-center justify-end gap-2 md:shrink-0">
                <label for="logs-sort-live" class="sr-only">Sort order</label>
                <select id="logs-sort-live" wire:model.live="sort"
                    class="min-w-[10.5rem] cursor-pointer rounded-lg border border-panel-stroke bg-white py-2 pl-3 pr-8 text-sm text-panel-primary focus:border-panel-primary focus:outline-none focus:ring-2 focus:ring-panel-primary/15">
                    <option value="time_desc">Newest first</option>
                    <option value="time_asc">Oldest first</option>
                    <option value="action_asc">Action A–Z</option>
                    <option value="action_desc">Action Z–A</option>
                    <option value="user_asc">User A–Z</option>
                    <option value="user_desc">User Z–A</option>
                </select>
                <div class="flex flex-wrap items-center justify-end gap-1.5" role="group" aria-label="Log source">
                    @foreach (['all' => 'All', 'admin' => 'Admin', 'staff' => 'Staff'] as $key => $label)
                    <button type="button" wire:click="setType('{{ $key }}')"
                        class="inline-flex items-center rounded-lg border px-2.5 py-1 text-sm font-medium transition-colors {{ $filterType === $key ? 'border-panel-primary bg-panel-primary text-white' : 'border-panel-stroke bg-white text-[#5a6a7e] shadow-sm hover:bg-[#eef1f5]' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
        @if ($filterQ !== '')
        <p class="mt-2 text-xs text-[#5a6a7e]">
            <button type="button" wire:click="clearSearch" class="font-medium text-panel-primary hover:underline">Clear
                search</button>
        </p>
        @endif
    </div>

    <div class="overflow-x-auto" wire:loading.class="opacity-60">
        <table class="compact-table logs-panel-table min-w-full text-sm">
            <thead class="border-b border-panel-stroke bg-[#eef1f5]">
                <tr>
                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-[#5a6a7e]">Time
                    </th>
                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-[#5a6a7e]">User
                    </th>
                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-[#5a6a7e]">
                        Action</th>
                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-[#5a6a7e]">
                        Details</th>
                    <th class="px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wide text-[#5a6a7e]">IP
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#c2cad6]/50 bg-white">
                @forelse ($logs as $log)
                <tr class="transition-colors odd:bg-white even:bg-[#f8fafc] hover:bg-[#eef1f5]/70"
                    wire:key="log-{{ $log->id }}">
                    <td class="whitespace-nowrap px-4 py-2.5 text-[#5a6a7e]">
                        {{ $log->created_at->format('M d, g:i A') }}
                    </td>
                    <td class="px-4 py-2.5">
                        <span class="font-medium text-panel-primary">{{ $log->user?->name ?? 'Unknown' }}</span>
                        @if ($log->user)
                        <span
                            class="ml-1.5 inline-flex rounded-md bg-[#eef1f5] px-1.5 py-0.5 align-middle text-[10px] font-semibold uppercase tracking-wide text-[#5a6a7e]">
                            {{ $log->user->role === 'staff' ? 'Staff' : 'Admin' }}
                        </span>
                        @endif
                    </td>
                    <td class="whitespace-nowrap px-4 py-2.5">
                        <span
                            class="inline-flex items-center rounded-lg border border-panel-stroke bg-[#eef1f5] px-2.5 py-0.5 text-xs font-semibold text-panel-primary">{{ $log->action }}</span>
                    </td>
                    <td class="max-w-md px-4 py-2.5 text-[#5a6a7e]">{{ $log->details ?? '—' }}</td>
                    <td class="whitespace-nowrap px-4 py-2.5 font-mono text-xs text-[#94a3b8]">{{ $log->ip_address }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-[#5a6a7e]">
                        <i class="fa-solid fa-inbox mb-2 block text-3xl text-[#c2cad6]"></i>
                        @if ($filterType !== 'all' || $filterQ !== '')
                        No logs match your filters.
                        @else
                        No activity logs yet.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($logs->total() > 0)
    <div class="border-t border-panel-stroke bg-[#eef1f5] px-4 py-3 sm:px-5">
        <div class="flex w-full min-w-0 flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
            <p class="min-w-0 text-sm tabular-nums text-[#5a6a7e]">
                Showing <span class="font-medium text-panel-primary">{{ $logs->firstItem() }}</span> to
                <span class="font-medium text-panel-primary">{{ $logs->lastItem() }}</span> of
                <span class="font-medium text-panel-primary">{{ $logs->total() }}</span> results
            </p>
            @if ($logs->hasPages())
            <nav aria-label="Activity log pagination" class="w-full shrink-0 sm:w-auto">
                <div class="-space-x-px inline-flex rounded-lg shadow-sm" role="group">
                    @if ($logs->onFirstPage())
                    <span
                        class="inline-flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-l-lg border border-panel-stroke bg-white leading-5 text-[#94a3b8] box-border"
                        aria-disabled="true">
                        <svg class="h-4 w-4 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m15 19-7-7 7-7" />
                        </svg>
                    </span>
                    @else
                    <button type="button" wire:click="previousPage"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-l-lg border border-panel-stroke bg-white leading-5 text-[#5a6a7e] box-border hover:bg-[#eef1f5] hover:text-panel-primary focus:outline-none focus:ring-2 focus:ring-panel-primary/20 focus:ring-offset-0"
                        title="Previous">
                        <svg class="h-4 w-4 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m15 19-7-7 7-7" />
                        </svg>
                    </button>
                    @endif
                    <span
                        class="inline-flex h-9 shrink-0 items-center justify-center border border-panel-stroke bg-white px-3 text-sm leading-5 text-[#5a6a7e] tabular-nums box-border">
                        {{ $logs->currentPage() }} of {{ $logs->lastPage() }}
                    </span>
                    @if ($logs->hasMorePages())
                    <button type="button" wire:click="nextPage"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-r-lg border border-panel-stroke bg-white leading-5 text-[#5a6a7e] box-border hover:bg-[#eef1f5] hover:text-panel-primary focus:outline-none focus:ring-2 focus:ring-panel-primary/20 focus:ring-offset-0"
                        title="Next">
                        <svg class="h-4 w-4 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m9 5 7 7-7 7" />
                        </svg>
                    </button>
                    @else
                    <span
                        class="inline-flex h-9 w-9 cursor-not-allowed items-center justify-center rounded-r-lg border border-panel-stroke bg-white leading-5 text-[#94a3b8] box-border"
                        aria-disabled="true">
                        <svg class="h-4 w-4 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m9 5 7 7-7 7" />
                        </svg>
                    </span>
                    @endif
                </div>
            </nav>
            @endif
        </div>
    </div>
    @endif
</div>
