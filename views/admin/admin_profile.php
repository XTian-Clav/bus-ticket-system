<?php

// views/admin/admin_profile.php

require_once __DIR__ . '/../../app/core/core_view.php';
require_once __DIR__ . '/../../app/queries/query_users.php';

$title = 'Profile';
$user  = get_user_by_id(auth_user_id());

ob_start();
?>

<div class="max-w-2xl" x-data="profilePage()">

    <!-- Profile info card -->
    <div class="bg-white border border-navy/10 rounded-2xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <span class="w-16 h-16 rounded-full bg-navy text-white flex items-center justify-center font-bold text-2xl flex-shrink-0">
                    <?= e(strtoupper(substr($user['fullname'] ?? $user['username'] ?? 'U', 0, 1))) ?>
                </span>
                <div>
                    <p class="text-lg font-bold text-navy"><?= e($user['fullname'] ?? '—') ?></p>
                    <p class="text-sm text-navy/60">@<?= e($user['username']) ?> · <?= e($user['email']) ?></p>
                    <p class="text-sm text-navy/50 mt-0.5"><?= e($user['contact'] ?? '—') ?></p>
                </div>
            </div>
            <button @click="showEdit = true"
                    class="flex items-center gap-2 px-4 py-2 bg-navy text-white text-sm font-semibold rounded-lg hover:bg-navy-light transition">
                <i class="ri-pencil-line"></i> Edit Profile
            </button>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <?php
    $show        = 'showEdit';
    $modal_title = 'Edit Profile';
    ob_start();
    ?>
        <form @submit.prevent="save()" class="space-y-4">
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Full name</label>
                    <input x-model="form.fullname"
                           class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Contact number</label>
                    <input x-model="form.contact"
                           class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Username</label>
                    <input x-model="form.username"
                           class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                    <p class="text-xs text-navy/40 mt-1">3–30 chars: letters, numbers, underscores.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Email</label>
                    <input x-model="form.email" type="email"
                           class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                </div>
            </div>
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-2.5 bg-navy text-white font-semibold rounded-lg hover:bg-navy-light transition">
                <i class="ri-save-line"></i> Save Changes
            </button>
        </form>
    <?php
    $content = ob_get_clean();
    require __DIR__ . '/../components/modal.php';
    ?>

</div>

<script>
    function profilePage() {
        return {
            showEdit: false,
            form: {
                fullname: <?= json_encode($user['fullname'] ?? '') ?>,
                contact:  <?= json_encode($user['contact']  ?? '') ?>,
                username: <?= json_encode($user['username'] ?? '') ?>,
                email:    <?= json_encode($user['email']    ?? '') ?>,
            },

            async save() {
                try {
                    await api('users.php?id=<?= (int) $user['id'] ?>', 'PUT', this.form);
                    toast('Profile updated.');
                    setTimeout(() => window.location.reload(), 700);
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
