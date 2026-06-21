<?php

// views/admin/admin_users.php

require_once __DIR__ . '/../../app/core/core_view.php';

$title     = 'Users';
$add_label = 'Add User';

ob_start();
?>

<div x-data="usersPage()" x-init="load()">

    <?php require __DIR__ . '/../components/page_header.php'; ?>

    <?php
    $columns = ['Name', 'Username', 'Email', 'Role', ''];
    ob_start();
    ?>
        <template x-if="!loading && users.length === 0">
            <tr><td colspan="5" class="px-5 py-8 text-center text-navy/40">No users yet.</td></tr>
        </template>
        <template x-for="user in users" :key="user.id">
            <tr>
                <td class="px-5 py-3 font-medium text-navy" x-text="user.fullname"></td>
                <td class="px-5 py-3 text-navy/70" x-text="user.username"></td>
                <td class="px-5 py-3 text-navy/70" x-text="user.email"></td>
                <td class="px-5 py-3">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold"
                          :class="user.role === 'admin' ? 'bg-gold/20 text-gold-dark' : 'bg-navy/10 text-navy'"
                          x-text="user.role"></span>
                </td>
                <td class="px-5 py-3 text-right">
                    <button @click="openEdit(user)" class="text-navy/50 hover:text-navy transition mr-3">
                        <i class="ri-pencil-line"></i>
                    </button>
                    <button @click="remove(user.id)" class="text-navy/50 hover:text-red-500 transition">
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
    $modal_title = 'Add User';
    ob_start();
    ?>
        <form @submit.prevent="create()" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Full name</label>
                <input x-model="form.fullname" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Username</label>
                <input x-model="form.username" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Email</label>
                <input x-model="form.email" type="email" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Contact number</label>
                <input x-model="form.contact" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Password</label>
                <input x-model="form.password" type="password" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Confirm password</label>
                <input x-model="form.confirm_password" type="password" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Role</label>
                <select x-model="form.role"
                        class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="w-full py-2.5 bg-navy text-white font-semibold rounded-lg hover:bg-navy-light transition">
                Save User
            </button>
        </form>
    <?php
    $content = ob_get_clean();
    require __DIR__ . '/../components/modal.php';
    ?>

    <?php
    $show        = 'showEdit';
    $modal_title = 'Edit User';
    ob_start();
    ?>
        <form @submit.prevent="update()" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Full name</label>
                <input x-model="form.fullname" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Contact number</label>
                <input x-model="form.contact" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Role</label>
                <select x-model="form.role"
                        class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="w-full py-2.5 bg-navy text-white font-semibold rounded-lg hover:bg-navy-light transition">
                Update User
            </button>
        </form>
    <?php
    $content = ob_get_clean();
    require __DIR__ . '/../components/modal.php';
    ?>

</div>

<script>
    function usersPage() {
        return {
            users: [],
            loading: true,
            showAdd: false,
            showEdit: false,
            form: { id: null, fullname: '', username: '', email: '', contact: '', password: '', confirm_password: '', role: 'user' },

            async load() {
                this.loading = true;
                try {
                    const res = await api('users.php');
                    this.users = res.data.users;
                } catch (err) {
                    toast(err.message, true);
                }
                this.loading = false;
            },

            openEdit(user) {
                this.form = { ...user };
                this.showEdit = true;
            },

            async create() {
                if (this.form.password !== this.form.confirm_password) {
                    toast('Passwords do not match.', true);
                    return;
                }

                try {
                    await api('users.php', 'POST', this.form);
                    toast('User added.');
                    this.showAdd = false;
                    this.form = { id: null, fullname: '', username: '', email: '', contact: '', password: '', confirm_password: '', role: 'user' };
                    this.load();
                } catch (err) {
                    toast(err.message, true);
                }
            },

            async update() {
                try {
                    await api(`users.php?id=${this.form.id}`, 'PUT', this.form);
                    toast('User updated.');
                    this.showEdit = false;
                    this.load();
                } catch (err) {
                    toast(err.message, true);
                }
            },

            async remove(id) {
                if (!confirm('Delete this user?')) return;
                try {
                    await api(`users.php?id=${id}`, 'DELETE');
                    toast('User deleted.');
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
