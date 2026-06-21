<?php

// views/admin/admin_buses.php

require_once __DIR__ . '/../../app/core/core_view.php';

$title       = 'Buses';
$add_label   = 'Add Bus';

ob_start();
?>

<div x-data="busesPage()" x-init="load()">

    <?php require __DIR__ . '/../components/page_header.php'; ?>

    <?php
    $columns = ['Bus Number', 'Seats', 'Added', ''];
    ob_start();
    ?>
        <template x-if="!loading && buses.length === 0">
            <tr><td colspan="4" class="px-5 py-8 text-center text-navy/40">No buses yet.</td></tr>
        </template>
        <template x-for="bus in buses" :key="bus.id">
            <tr>
                <td class="px-5 py-3 font-medium text-navy" x-text="bus.bus_num"></td>
                <td class="px-5 py-3 text-navy/70" x-text="bus.seats"></td>
                <td class="px-5 py-3 text-navy/50" x-text="formatDate(bus.created_at)"></td>
                <td class="px-5 py-3 text-right">
                    <button @click="openEdit(bus)" class="text-navy/50 hover:text-navy transition mr-3">
                        <i class="ri-pencil-line"></i>
                    </button>
                    <button @click="remove(bus.id)" class="text-navy/50 hover:text-red-500 transition">
                        <i class="ri-delete-bin-line"></i>
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
    $modal_title = 'Add Bus';
    ob_start();
    ?>
        <form @submit.prevent="create()" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Bus number</label>
                <input x-model="form.bus_num" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Seat count</label>
                <input x-model="form.seats" type="number" min="1" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <button type="submit" class="w-full py-2.5 bg-navy text-white font-semibold rounded-lg hover:bg-navy-light transition">
                Save Bus
            </button>
        </form>
    <?php
    $content = ob_get_clean();
    require __DIR__ . '/../components/modal.php';
    ?>

    <?php
    $show        = 'showEdit';
    $modal_title = 'Edit Bus';
    ob_start();
    ?>
        <form @submit.prevent="update()" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Bus number</label>
                <input x-model="form.bus_num" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Seat count</label>
                <input x-model="form.seats" type="number" min="1" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <button type="submit" class="w-full py-2.5 bg-navy text-white font-semibold rounded-lg hover:bg-navy-light transition">
                Update Bus
            </button>
        </form>
    <?php
    $content = ob_get_clean();
    require __DIR__ . '/../components/modal.php';
    ?>

</div>

<script>
    function busesPage() {
        return {
            buses: [],
            loading: true,
            showAdd: false,
            showEdit: false,
            form: { id: null, bus_num: '', seats: '' },

            async load() {
                this.loading = true;
                try {
                    const res = await api('buses.php');
                    this.buses = res.data.buses;
                } catch (err) {
                    toast(err.message, true);
                }
                this.loading = false;
            },

            formatDate(d) {
                return d ? new Date(d).toLocaleDateString() : '';
            },

            openEdit(bus) {
                this.form = { ...bus };
                this.showEdit = true;
            },

            async create() {
                try {
                    await api('buses.php', 'POST', this.form);
                    toast('Bus added.');
                    this.showAdd = false;
                    this.form = { id: null, bus_num: '', seats: '' };
                    this.load();
                } catch (err) {
                    toast(err.message, true);
                }
            },

            async update() {
                try {
                    await api(`buses.php?id=${this.form.id}`, 'PUT', this.form);
                    toast('Bus updated.');
                    this.showEdit = false;
                    this.load();
                } catch (err) {
                    toast(err.message, true);
                }
            },

            async remove(id) {
                if (!confirm('Delete this bus?')) return;
                try {
                    await api(`buses.php?id=${id}`, 'DELETE');
                    toast('Bus deleted.');
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
