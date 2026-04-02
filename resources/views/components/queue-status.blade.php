@props([
    'queueNumber' => null,
    'position' => null,
    'wait' => null,
])
<div {{ $attributes->merge(['class' => 'rounded-2xl border border-border-subtle bg-muted-bg p-6 text-center']) }}>
    @if($queueNumber !== null)
        <p class="text-xs uppercase tracking-[0.2em] text-cream/60 mb-2">Queue ticket</p>
        <p class="font-forum text-6xl sm:text-7xl text-cream leading-none mb-4">#{{ $queueNumber }}</p>
    @endif
    @if($position !== null)
        <p class="text-cream/80 text-sm">Position in line: <span class="text-cream font-medium">{{ $position }}</span></p>
    @endif
    @if($wait !== null)
        <p class="text-cream/80 text-sm mt-1">Est. wait: <span class="text-cream font-medium">{{ $wait }} min</span></p>
    @endif
</div>
