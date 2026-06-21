<?php
// views/components/avatar_upload.php
// Expects: $user (array with id, avatar)
// Provides its own Alpine scope; drop inside any page.
?>
<div x-data="avatarUpload(<?= (int) $user['id'] ?>, <?= json_encode($user['avatar'] ?? null) ?>)"
     class="bg-white border border-navy/10 rounded-2xl shadow-sm p-6 flex items-center gap-5 mb-6 flex-wrap">

    <div class="relative flex-shrink-0">
        <template x-if="preview || avatar">
            <img :src="preview || url('/assets/uploads/avatars/' + avatar)" alt="Profile photo"
                 class="w-20 h-20 rounded-full object-cover border border-navy/10">
        </template>
        <template x-if="!preview && !avatar">
            <span class="w-20 h-20 rounded-full bg-navy text-white flex items-center justify-center font-bold text-2xl">
                <?= e(strtoupper(substr($user['fullname'] ?? 'U', 0, 1))) ?>
            </span>
        </template>
    </div>

    <div>
        <p class="text-sm font-semibold text-navy mb-1">Profile photo</p>
        <p class="text-xs text-navy/50 mb-3">JPG, PNG, or WEBP. Max 2MB.</p>
        <label class="inline-flex items-center gap-2 px-4 py-2 bg-navy text-white text-sm font-semibold rounded-lg hover:bg-navy-light transition cursor-pointer">
            <i class="ri-upload-2-line"></i>
            <span x-text="uploading ? 'Uploading...' : 'Change Photo'"></span>
            <input type="file" accept="image/jpeg,image/png,image/webp" class="hidden" @change="upload($event)" :disabled="uploading">
        </label>
    </div>

</div>

<script>
    function avatarUpload(userId, initialAvatar) {
        return {
            avatar: initialAvatar,
            preview: null,
            uploading: false,

            async upload(event) {
                const file = event.target.files[0];
                if (!file) return;

                this.preview = URL.createObjectURL(file);

                const formData = new FormData();
                formData.append('avatar', file);

                this.uploading = true;
                try {
                    const res = await apiUpload(`avatar.php?id=${userId}`, formData);
                    this.avatar = res.data.avatar;
                    toast('Profile photo updated.');
                } catch (err) {
                    this.preview = null;
                    toast(err.message, true);
                } finally {
                    this.uploading = false;
                    event.target.value = '';
                }
            },
        };
    }
</script>
