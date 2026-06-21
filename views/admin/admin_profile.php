<?php

// views/admin/admin_profile.php

require_once __DIR__ . '/../../app/core/core_view.php';
require_once __DIR__ . '/../../app/queries/query_users.php';

$title = 'Profile';
$user  = get_user_by_id(auth_user_id());

ob_start();
?>

<div class="max-w-2xl">

    <?php require __DIR__ . '/../components/avatar_upload.php'; ?>

    <div class="bg-white border border-navy/10 rounded-2xl shadow-sm p-6" x-data="profilePage()">
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
                    <input value="<?= e($user['username']) ?>" disabled
                           class="w-full px-4 py-2.5 border border-navy/10 bg-offwhite text-navy/50 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Email</label>
                    <input value="<?= e($user['email']) ?>" disabled
                           class="w-full px-4 py-2.5 border border-navy/10 bg-offwhite text-navy/50 rounded-lg">
                </div>
            </div>
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 bg-navy text-white font-semibold rounded-lg hover:bg-navy-light transition">
                <i class="ri-save-line"></i> Save Changes
            </button>
        </form>
    </div>

</div>

<script>
    function profilePage() {
        return {
            form: { fullname: <?= json_encode($user['fullname']) ?>, contact: <?= json_encode($user['contact']) ?> },

            async save() {
                try {
                    await api('users.php?id=<?= (int) $user['id'] ?>', 'PUT', this.form);
                    toast('Profile updated.');
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
