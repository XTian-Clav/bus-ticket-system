<?php

// views/admin/admin_bookings.php

require_once __DIR__ . '/../../app/core/core_view.php';

$title = 'Bookings';

ob_start();
?>

<div x-data="bookingsPage()" x-init="load()">

    <?php require __DIR__ . '/../components/page_header.php'; ?>

    <?php
    $columns = ['Passenger', 'Route', 'Bus', 'Seat', 'Fare', 'Status', ''];
    ob_start();
    ?>
        <template x-if="!loading && bookings.length === 0">
            <tr><td colspan="7" class="px-5 py-8 text-center text-navy/40">No bookings yet.</td></tr>
        </template>
        <template x-for="b in bookings" :key="b.id">
            <tr>
                <td class="px-5 py-3 font-medium text-navy" x-text="b.fullname"></td>
                <td class="px-5 py-3 text-navy/70" x-text="`${b.source} → ${b.destination}`"></td>
                <td class="px-5 py-3 text-navy/70" x-text="b.bus_num"></td>
                <td class="px-5 py-3 text-navy/70" x-text="b.seat_num"></td>
                <td class="px-5 py-3 text-navy/70" x-text="`₱${Number(b.fare).toFixed(2)}`"></td>
                <td class="px-5 py-3">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold"
                          :class="b.status === 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'"
                          x-text="b.status"></span>
                </td>
                <td class="px-5 py-3 text-right">
                    <button x-show="b.status === 'confirmed'" @click="cancelBooking(b.id)"
                            class="text-navy/50 hover:text-red-500 transition">
                        <i class="ri-close-circle-line"></i>
                    </button>
                </td>
            </tr>
        </template>
    <?php
    $body = ob_get_clean();
    require __DIR__ . '/../components/table_shell.php';
    ?>

</div>

<script>
    function bookingsPage() {
        return {
            bookings: [],
            loading: true,

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
        };
    }
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin_layout.php';
