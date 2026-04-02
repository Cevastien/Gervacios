{{-- Seat status segmented control (classes in seating-map-inner-styles) --}}
<p class="mb-3 mt-5 text-[12px] leading-snug text-neutral-500">Status applies to <strong class="font-semibold text-black">all seats</strong> in this table group.</p>
<div class="seating-s-opts" role="radiogroup" aria-label="Status for every seat in this table group">
    <div class="seating-s-opt av" data-seat-status="free" tabindex="0" role="radio" aria-checked="false">
        <span class="seating-s-opt-label">Available</span>
    </div>
    <div class="seating-s-opt re" data-seat-status="reserved" tabindex="0" role="radio" aria-checked="false">
        <span class="seating-s-opt-label">Reserved</span>
    </div>
    <div class="seating-s-opt oc" data-seat-status="occupied" tabindex="0" role="radio" aria-checked="false">
        <span class="seating-s-opt-label">Occupied</span>
    </div>
</div>
