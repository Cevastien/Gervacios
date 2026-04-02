{{-- Seat / place modal: table label, capacity, furniture (IDs wired in seating-layout.js) --}}
@php
    $seatField =
        'mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder:text-neutral-400 shadow-sm transition focus:border-panel-primary focus:outline-none focus:ring-2 focus:ring-panel-primary/15';
@endphp
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-4">
    <div class="min-w-0 sm:col-span-1">
        <label for="seat-modal-table-name" class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-black">Name (table label)</label>
        <input id="seat-modal-table-name" type="text" maxlength="50" placeholder="e.g. T4, Window 2"
            class="{{ $seatField }}" />
    </div>

    <div id="seat-modal-capacity-row" class="min-w-0 sm:col-span-1">
        <label for="seat-modal-capacity" class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-black">Capacity (guests)</label>
        <input id="seat-modal-capacity" type="number" min="1" max="99" value="1"
            class="{{ $seatField }}" />
        <p id="seat-modal-capacity-hint" class="mt-1.5 text-[11px] leading-snug text-neutral-500"></p>
    </div>
</div>

<div class="mt-4">
    <label for="seat-modal-furniture-type" class="block text-[11px] font-semibold uppercase tracking-[0.12em] text-black">Seat / table type</label>
    <select id="seat-modal-furniture-type"
        class="{{ $seatField }}">
        <option value="standard">Standard</option>
        <option value="booth">Booth</option>
        <option value="bar">Bar / counter</option>
        <option value="high-top">High-top</option>
        <option value="outdoor">Outdoor</option>
        <option value="bench">Bench</option>
    </select>
</div>
