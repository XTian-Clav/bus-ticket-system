<?php
// views/components/seat_map.php
// Pure Alpine markup — expects an enclosing x-data with: totalSeats, takenSeats (array), seatNum (selected, via form.seat_num)
// Renders a simple grid of seat buttons (5 per row, 7 on wider screens).
?>
<div class="grid grid-cols-5 sm:grid-cols-7 gap-2 max-h-80 overflow-y-auto p-1">
    <template x-for="seat in Array.from({ length: totalSeats }, (_, i) => i + 1)" :key="seat">
        <button type="button"
                @click="!takenSeats.includes(seat) && (form.seat_num = seat)"
                :disabled="takenSeats.includes(seat)"
                :class="{
                    'bg-navy text-white border-navy': form.seat_num === seat,
                    'bg-offwhite text-navy/30 border-navy/5 cursor-not-allowed line-through': takenSeats.includes(seat),
                    'bg-white text-navy border-navy/20 hover:border-gold hover:bg-gold/10': form.seat_num !== seat && !takenSeats.includes(seat),
                }"
                class="aspect-square rounded-lg border text-sm font-semibold transition flex items-center justify-center">
            <span x-text="seat"></span>
        </button>
    </template>
</div>

<div class="flex items-center flex-wrap gap-x-4 gap-y-2 mt-3 text-xs text-navy/50">
    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-white border border-navy/20"></span> Available</span>
    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-navy"></span> Selected</span>
    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-offwhite border border-navy/5"></span> Taken</span>
</div>
