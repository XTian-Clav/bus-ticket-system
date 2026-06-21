<?php
// views/components/page_header.php
// Expects: $title, $add_label (optional), $add_target (optional Alpine target to open modal)
?>
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-navy"><?= e($title) ?></h2>

    <?php if (!empty($add_label)): ?>
        <button @click="<?= $add_target ?? 'showAdd = true' ?>"
                class="flex items-center gap-2 px-4 py-2.5 bg-navy text-white text-sm font-semibold rounded-lg hover:bg-navy-light transition">
            <i class="ri-add-line"></i> <?= e($add_label) ?>
        </button>
    <?php endif; ?>
</div>
