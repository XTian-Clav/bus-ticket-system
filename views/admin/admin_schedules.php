<?php

// views/admin/admin_schedules.php

require_once __DIR__ . '/../../app/core/core_view.php';
require_once __DIR__ . '/../../app/queries/query_buses.php';
require_once __DIR__ . '/../../app/queries/query_routes.php';

$title       = 'Schedules';
$add_label   = 'Add Schedule';
$buses       = get_all_buses();
$routes_list = get_all_routes();

ob_start();
?>

<div x-data="schedulesPage()" x-init="load()">

    <?php require __DIR__ . '/../components/page_header.php'; ?>

    <?php
    $columns = ['Route', 'Bus', 'Fare', 'Departure', ''];
    ob_start();
    ?>
        <template x-if="!loading && schedules.length === 0">
            <tr><td colspan="5" class="px-5 py-8 text-center text-navy/40">No schedules yet.</td></tr>
        </template>
        <template x-for="sch in schedules" :key="sch.id">
            <tr>
                <td class="px-5 py-3 font-medium text-navy" x-text="`${sch.source} → ${sch.destination}`"></td>
                <td class="px-5 py-3 text-navy/70" x-text="sch.bus_num"></td>
                <td class="px-5 py-3 text-navy/70" x-text="`₱${Number(sch.fare).toFixed(2)}`"></td>
                <td class="px-5 py-3 text-navy/50" x-text="formatDateTime(sch.depart_time)"></td>
                <td class="px-5 py-3 text-right">
                    <button @click="openEdit(sch)" class="text-navy/50 hover:text-navy transition mr-3">
                        <i class="ri-pencil-line"></i> Edit
                    </button>
                    <button @click="remove(sch.id)" class="text-navy/50 hover:text-red-500 transition">
                        <i class="ri-delete-bin-line"></i> Delete
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
    $modal_title = 'Add Schedule';
    ob_start();
    ?>
        <form @submit.prevent="create()" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Bus</label>
                <select x-model="form.bus_id" required
                        class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                    <option value="">Select a bus</option>
                    <?php foreach ($buses as $bus): ?>
                        <option value="<?= e((string) $bus['id']) ?>"><?= e($bus['bus_num']) ?> (<?= e((string) $bus['seats']) ?> seats)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Route</label>
                <select x-model="form.route_id" required
                        class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                    <option value="">Select a route</option>
                    <?php foreach ($routes_list as $route): ?>
                        <option value="<?= e((string) $route['id']) ?>"><?= e($route['source']) ?> → <?= e($route['destination']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Fare</label>
                <input x-model="form.fare" type="number" min="0" step="0.01" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Departure date &amp; time</label>
                <input x-model="form.depart_time" type="datetime-local" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 bg-navy text-white font-semibold rounded-lg hover:bg-navy-light transition">
                <i class="ri-save-line"></i> Save Schedule
            </button>
        </form>
    <?php
    $content = ob_get_clean();
    require __DIR__ . '/../components/modal.php';
    ?>

    <?php
    $show        = 'showEdit';
    $modal_title = 'Edit Schedule';
    ob_start();
    ?>
        <form @submit.prevent="update()" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Fare</label>
                <input x-model="form.fare" type="number" min="0" step="0.01" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Departure date &amp; time</label>
                <input x-model="form.depart_time" type="datetime-local" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 bg-navy text-white font-semibold rounded-lg hover:bg-navy-light transition">
                <i class="ri-save-line"></i> Update Schedule
            </button>
        </form>
    <?php
    $content = ob_get_clean();
    require __DIR__ . '/../components/modal.php';
    ?>

</div>

<script>
    function schedulesPage() {
        return {
            schedules: [],
            loading: true,
            showAdd: false,
            showEdit: false,
            form: { id: null, bus_id: '', route_id: '', fare: '', depart_time: '' },

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
                return d ? new Date(d).toLocaleString().replace(',', '') : '';
            },

            openEdit(sch) {
                this.form = { id: sch.id, fare: sch.fare, depart_time: sch.depart_time.replace(' ', 'T').slice(0, 16) };
                this.showEdit = true;
            },

            async create() {
                try {
                    await api('schedules.php', 'POST', this.form);
                    toast('Schedule added.');
                    this.showAdd = false;
                    this.form = { id: null, bus_id: '', route_id: '', fare: '', depart_time: '' };
                    this.load();
                } catch (err) {
                    toast(err.message, true);
                }
            },

            async update() {
                try {
                    await api(`schedules.php?id=${this.form.id}`, 'PUT', this.form);
                    toast('Schedule updated.');
                    this.showEdit = false;
                    this.load();
                } catch (err) {
                    toast(err.message, true);
                }
            },

            async remove(id) {
                if (!confirm('Delete this schedule?')) return;
                try {
                    await api(`schedules.php?id=${id}`, 'DELETE');
                    toast('Schedule deleted.');
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
