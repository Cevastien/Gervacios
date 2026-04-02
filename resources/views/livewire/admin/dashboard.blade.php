<div class="space-y-4">
    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700">
        <p class="font-semibold text-slate-900">Admin dashboard component</p>
        <p class="mt-1">Tables: {{ $tables->count() }} · Waiting: {{ $queue->count() }} · Priority: {{ $priorityWaiting }}</p>
    </div>
</div>
