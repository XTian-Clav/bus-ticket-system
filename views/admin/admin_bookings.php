<?php

// views/admin/admin_bookings.php

require_once __DIR__ . '/../../app/core/core_view.php';

$title     = 'Bookings';
$add_label = 'Add Booking';
$add_target = 'openAdd()';

ob_start();
?>

<div x-data="bookingsPage()" x-init="load()">

    <?php require __DIR__ . '/../components/page_header.php'; ?>

    <?php
    $columns = ['Passenger', 'Route', 'Bus', 'Seat', 'Fare', 'Departure', 'Status', ''];
    ob_start();
    ?>
        <template x-if="!loading && bookings.length === 0">
            <tr><td colspan="8" class="px-5 py-8 text-center text-navy/40">No bookings yet.</td></tr>
        </template>
        <template x-for="b in bookings" :key="b.id">
            <tr>
                <td class="px-5 py-3 font-medium text-navy" x-text="b.fullname"></td>
                <td class="px-5 py-3 text-navy/70" x-text="`${b.source} → ${b.destination}`"></td>
                <td class="px-5 py-3 text-navy/70" x-text="b.bus_num"></td>
                <td class="px-5 py-3 text-navy/70" x-text="b.seat_num"></td>
                <td class="px-5 py-3 text-navy/70" x-text="`₱${Number(b.fare).toFixed(2)}`"></td>
                <td class="px-5 py-3 text-navy/50 text-sm" x-text="formatDateTime(b.depart_time)"></td>
                <td class="px-5 py-3">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold"
                          :class="b.status === 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'"
                          x-text="b.status"></span>
                </td>
                <td class="px-5 py-3 text-right">
                    <button x-show="b.status === 'confirmed'" @click="cancelBooking(b.id)"
                            class="inline-flex items-center gap-1 text-navy/50 hover:text-red-500 transition text-sm">
                        <i class="ri-close-circle-line"></i> Cancel
                    </button>
                </td>
            </tr>
        </template>
    <?php
    $body = ob_get_clean();
    require __DIR__ . '/../components/table_shell.php';
    ?>

    <?php
    $show        = 'showAdd';
    $modal_title = 'Add Booking';
    $modal_width = 'max-w-2xl';
    ob_start();
    ?>
        <form @submit.prevent="createBooking()" class="space-y-5">

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Passenger</label>
                    <select x-model="form.user_id" required
                            class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                        <option value="">— Select user —</option>
                        <template x-for="u in users" :key="u.id">
                            <option :value="u.id" x-text="`${u.fullname} (${u.username})`"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Schedule</label>
                    <select x-model="form.schedule_id" @change="onScheduleChange()" required
                            class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                        <option value="">— Select schedule —</option>
                        <template x-for="s in schedules" :key="s.id">
                            <option :value="s.id"
                                    x-text="`${s.source} → ${s.destination} · ${s.bus_num} · ₱${Number(s.fare).toFixed(2)}`">
                            </option>
                        </template>
                    </select>
                </div>
            </div>

            <template x-if="form.schedule_id">
                <div>
                    <label class="block text-sm font-medium text-navy mb-2">Choose a seat</label>

                    <template x-if="seatsLoading">
                        <p class="text-sm text-navy/40 py-6 text-center">Loading seat map...</p>
                    </template>

                    <template x-if="!seatsLoading && totalSeats > 0">
                        <div>
                            <div class="grid grid-cols-5 sm:grid-cols-7 gap-2 max-h-64 overflow-y-auto no-scrollbar p-1">
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

                            <p class="text-xs text-navy/40 mt-2" x-show="form.seat_num">
                                Selected seat: <span class="font-semibold text-navy" x-text="form.seat_num"></span>
                            </p>
                        </div>
                    </template>
                </div>
            </template>

            <button type="submit" :disabled="!form.user_id || !form.schedule_id || !form.seat_num"
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
    function bookingsPage() {
        return {
            bookings: [],
            loading: true,
            showAdd: false,

            // Add booking form
            users: [],
            schedules: [],
            form: { user_id: '', schedule_id: '', seat_num: '' },
            totalSeats: 0,
            takenSeats: [],
            seatsLoading: false,

            async load() {
                this.loading = true;
                try {
                    const res = await api('bookings.php');
                    this.bookings = res.data.bookings;
                } catch (err) {
                    toast(err.message, true);
                }
                this.loading = false;
            },

            async openAdd() {
                this.form = { user_id: '', schedule_id: '', seat_num: '' };
                this.totalSeats = 0;
                this.takenSeats = [];
                this.showAdd = true;

                try {
                    const [uRes, sRes] = await Promise.all([
                        api('users.php?role=user'),
                        api('schedules.php'),
                    ]);
                    this.users     = uRes.data.users;
                    this.schedules = sRes.data.schedules;
                } catch (err) {
                    toast(err.message, true);
                }
            },

            async onScheduleChange() {
                this.form.seat_num = '';
                this.totalSeats    = 0;
                this.takenSeats    = [];

                if (!this.form.schedule_id) return;

                const sch = this.schedules.find(s => s.id == this.form.schedule_id);
                this.totalSeats  = sch ? parseInt(sch.seats) : 0;
                this.seatsLoading = true;

                try {
                    const res = await api(`schedules.php?id=${this.form.schedule_id}`);
                    this.takenSeats = res.data.schedule.taken_seats.map(Number);
                } catch (err) {
                    toast(err.message, true);
                }
                this.seatsLoading = false;
            },

            async createBooking() {
                if (!this.form.user_id || !this.form.schedule_id || !this.form.seat_num) {
                    toast('Please select a passenger, schedule, and seat.', true);
                    return;
                }
                try {
                    await api('bookings.php', 'POST', {
                        user_id:     parseInt(this.form.user_id),
                        schedule_id: parseInt(this.form.schedule_id),
                        seat_num:    parseInt(this.form.seat_num),
                    });
                    toast('Booking created.');
                    this.showAdd = false;
                    this.load();
                } catch (err) {
                    toast(err.message, true);
                }
            },

            async cancelBooking(id) {
                if (!confirm('Cancel this booking?')) return;
                try {
                    await api(`bookings.php?id=${id}`, 'DELETE');
                    toast('Booking cancelled.');
                    this.load();
                } catch (err) {
                    toast(err.message, true);
                }
            },

            formatDateTime(d) {
                return d ? new Date(d).toLocaleString() : '';
            },
        };
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin_layout.php';
