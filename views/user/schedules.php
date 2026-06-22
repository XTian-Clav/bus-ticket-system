<?php

// views/user/schedules.php

require_once __DIR__ . '/../../app/core/core_view.php';

$title = 'Available Schedules';

ob_start();
?>

<div x-data="schedulesPage()" x-init="load()">

    <?php require __DIR__ . '/../components/page_header.php'; ?>

    <?php
    $columns = ['Route', 'Bus', 'Fare', 'Departure', 'Seats Left', ''];
    ob_start();
    ?>
        <template x-if="!loading && schedules.length === 0">
            <tr><td colspan="6" class="px-5 py-8 text-center text-navy/40">No schedules available right now.</td></tr>
        </template>
        <template x-for="sch in schedules" :key="sch.id">
            <tr>
                <td class="px-5 py-3 font-medium text-navy" x-text="`${sch.source} → ${sch.destination}`"></td>
                <td class="px-5 py-3 text-navy/70" x-text="sch.bus_num"></td>
                <td class="px-5 py-3 text-navy/70" x-text="`₱${Number(sch.fare).toFixed(2)}`"></td>
                <td class="px-5 py-3 text-navy/50" x-text="formatDateTime(sch.depart_time)"></td>
                <td class="px-5 py-3 text-navy/70" x-text="sch.seats"></td>
                <td class="px-5 py-3 text-right">
                    <button @click="openBook(sch)"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-navy text-white text-xs font-semibold rounded-lg hover:bg-navy-light transition">
                        <i class="ri-ticket-2-line"></i> Book
                    </button>
                </td>
            </tr>
        </template>
    <?php
    $body = ob_get_clean();
    require __DIR__ . '/../components/table_shell.php';
    ?>

    <?php
    $show        = 'showBook';
    $modal_title = 'Book a Seat';
    $modal_width = 'max-w-2xl';
    ob_start();
    ?>
        <form @submit.prevent="book()" class="space-y-4">
            <p class="text-sm text-navy/70">
                <span x-text="selected.source"></span> → <span x-text="selected.destination"></span>
                · <span x-text="selected.bus_num"></span>
                · <span x-text="`₱${Number(selected.fare || 0).toFixed(2)}`"></span>
            </p>

            <div>
                <label class="block text-sm font-medium text-navy mb-2">Choose a seat</label>

                <template x-if="seatsLoading">
                    <p class="text-sm text-navy/40 py-6 text-center">Loading seat map...</p>
                </template>

                <template x-if="!seatsLoading">
                    <?php require __DIR__ . '/../components/seat_map.php'; ?>
                </template>

                <p class="text-xs text-navy/40 mt-2" x-show="form.seat_num">
                    Selected seat: <span class="font-semibold text-navy" x-text="form.seat_num"></span>
                </p>
            </div>

            <button type="submit" :disabled="!form.seat_num"
                    class="w-full flex items-center justify-center gap-2 py-2.5 bg-navy text-white font-semibold rounded-lg hover:bg-navy-light transition disabled:opacity-40 disabled:cursor-not-allowed">
                <i class="ri-checkbox-circle-line"></i> Confirm Booking
            </button>
        </form>
    <?php
    $content = ob_get_clean();
    require __DIR__ . '/../components/modal.php';
    unset($modal_width);
    ?>

</div>

<script>
    function schedulesPage() {
        return {
            schedules: [],
            loading: true,
            showBook: false,
            selected: {},
            form: { schedule_id: null, seat_num: '' },
            totalSeats: 0,
            takenSeats: [],
            seatsLoading: false,

            async load() {
                this.loading = true;
                try {
                    const res = await api('schedules.php');
                    this.schedules = res.data.schedules;
                } catch (err) {
                    toast(err.message, true);
                }
                this.loading = false;
            },

            formatDateTime(d) {
                if (!d) return '';
                const date = new Date(d);
                const datePart = date.toLocaleDateString('en-US', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric',
                });
                const timePart = date.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true,
                });
                return `${datePart} — ${timePart}`;
            },

            async openBook(sch) {
                this.selected = sch;
                this.form = { schedule_id: sch.id, seat_num: '' };
                this.totalSeats = sch.seats;
                this.takenSeats = [];
                this.showBook = true;
                this.seatsLoading = true;

                try {
                    const res = await api(`schedules.php?id=${sch.id}`);
                    this.takenSeats = res.data.schedule.taken_seats.map(Number);
                } catch (err) {
                    toast(err.message, true);
                }
                this.seatsLoading = false;
            },

            async book() {
                if (!this.form.seat_num) return;

                try {
                    await api('bookings.php', 'POST', this.form);
                    toast('Seat booked successfully.');
                    this.showBook = false;
                    this.load();
                } catch (err) {
                    toast(err.message, true);
                }
            },
        };
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/user.php';
