<div
    {{ $attributes->merge(['class' => 'shrink-0 flex flex-col items-center gap-1.5 rounded-2xl border border-cream/30 bg-black/35 px-2.5 py-2 shadow-[0_8px_32px_rgba(0,0,0,0.45)] backdrop-blur-md w-[min(100%,5.75rem)]']) }}
    role="img"
    aria-label="Scan this QR code with your phone to open the mobile menu and queue."
>
    <span class="font-forum text-[10px] font-normal uppercase tracking-[0.14em] text-cream/55">Mobile</span>
    <img
        src="https://api.qrserver.com/v1/create-qr-code/?size=72x72&amp;ecc=M&amp;color=efe7d2&amp;bgcolor=0a0b0a&amp;margin=2&amp;data={{ rawurlencode(url('/mobile')) }}"
        alt=""
        width="72"
        height="72"
        class="block h-[72px] w-[72px] max-w-none rounded-md opacity-[0.97]"
        loading="lazy"
        decoding="async"
    />
</div>
