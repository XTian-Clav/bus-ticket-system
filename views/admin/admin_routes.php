<?php

// views/admin/admin_routes.php

require_once __DIR__ . '/../../app/core/core_view.php';

$title     = 'Routes';
$add_label = 'Add Route';

ob_start();
?>

<div x-data="routesPage()" x-init="load()">

    <?php require __DIR__ . '/../components/page_header.php'; ?>

    <?php
    $columns = ['Source', 'Destination', 'Added', ''];
    ob_start();
    ?>
        <template x-if="!loading && routes.length === 0">
            <tr><td colspan="4" class="px-5 py-8 text-center text-navy/40">No routes yet.</td></tr>
        </template>
        <template x-for="route in routes" :key="route.id">
            <tr>
                <td class="px-5 py-3 font-medium text-navy" x-text="route.source"></td>
                <td class="px-5 py-3 text-navy/70" x-text="route.destination"></td>
                <td class="px-5 py-3 text-navy/50" x-text="formatDate(route.created_at)"></td>
                <td class="px-5 py-3 text-right">
                    <button @click="openEdit(route)" class="text-navy/50 hover:text-navy transition mr-3">
                        <i class="ri-pencil-line"></i> Edit
                    </button>
                    <button @click="remove(route.id)" class="text-navy/50 hover:text-red-500 transition">
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
    $modal_title = 'Add Route';
    ob_start();
    ?>
        <form @submit.prevent="create()" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Source</label>
                <input x-model="form.source" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Destination</label>
                <input x-model="form.destination" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 bg-navy text-white font-semibold rounded-lg hover:bg-navy-light transition">
                <i class="ri-save-line"></i> Save Route
            </button>
        </form>
    <?php
    $content = ob_get_clean();
    require __DIR__ . '/../components/modal.php';
    ?>

    <?php
    $show        = 'showEdit';
    $modal_title = 'Edit Route';
    ob_start();
    ?>
        <form @submit.prevent="update()" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Source</label>
                <input x-model="form.source" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Destination</label>
                <input x-model="form.destination" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 bg-navy text-white font-semibold rounded-lg hover:bg-navy-light transition">
                <i class="ri-save-line"></i> Update Route
            </button>
        </form>
    <?php
    $content = ob_get_clean();
    require __DIR__ . '/../components/modal.php';
    ?>

</div>

<script>
    function routesPage() {
        return {
            routes: [],
            loading: true,
            showAdd: false,
            showEdit: false,
            form: { id: null, source: '', destination: '' },

            async load() {
                this.loading = true;
                try {
                    const res = await api('routes.php');
                    this.routes = res.data.routes;
                } catch (err) {
                    toast(err.message, true);
                }
                this.loading = false;
            },

            formatDate(d) {
                return d ? new Date(d).toLocaleDateString() : '';
            },

            openEdit(route) {
                this.form = { ...route };
                this.showEdit = true;
            },

            async create() {
                try {
                    await api('routes.php', 'POST', this.form);
                    toast('Route added.');
                    this.showAdd = false;
                    this.form = { id: null, source: '', destination: '' };
                    this.load();
                } catch (err) {
                    toast(err.message, true);
                }
            },

            async update() {
                try {
                    await api(`routes.php?id=${this.form.id}`, 'PUT', this.form);
                    toast('Route updated.');
                    this.showEdit = false;
                    this.load();
                } catch (err) {
                    toast(err.message, true);
                }
            },

            async remove(id) {
                if (!confirm('Delete this route?')) return;
                try {
                    await api(`routes.php?id=${id}`, 'DELETE');
                    toast('Route deleted.');
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
