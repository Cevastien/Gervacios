@props([
    'title' => '',
    'subtitle' => null,
])

<div {{ $attributes->class(['admin-panel-heading-inner']) }}>
    <h1 class="text-xl font-semibold tracking-tight text-slate-900 md:text-[22px]">
        {{ $title }}
    </h1>
    @if ($subtitle)
        <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
    @endif
</div>
