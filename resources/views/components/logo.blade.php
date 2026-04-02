@props([
    'variant' => 'compact',
])
<div {{ $attributes->merge(['class' => 'flex items-center']) }}>
    @if($variant === 'compact')
        <span class="font-['Cormorant_Garamond',Georgia,serif] tracking-[0.14em] uppercase text-cream text-sm sm:text-base leading-none">
            Gervacios
        </span>
    @else
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 15 320 120" class="h-9 w-auto max-w-[200px] text-cream" aria-label="Café Gervacios">
            <text x="160" y="38" text-anchor="middle" font-family="'Dancing Script', cursive" font-size="22" font-weight="400" fill="currentColor" letter-spacing="1">cafe</text>
            <line x1="88" y1="32" x2="118" y2="32" stroke="currentColor" stroke-width="0.8" opacity="0.6"/>
            <line x1="202" y1="32" x2="232" y2="32" stroke="currentColor" stroke-width="0.8" opacity="0.6"/>
            <text x="160" y="82" text-anchor="middle" font-family="'Cormorant Garamond', Georgia, serif" font-size="52" font-weight="400" fill="currentColor" letter-spacing="4">GERVACIOS</text>
        </svg>
    @endif
</div>
