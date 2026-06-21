<?php
// views/components/modal.php
// Expects: $show (alpine expression, e.g. "showAdd"), $modal_title, $content (inner HTML/form)
// Optional: $modal_width (tailwind max-w-* class, default max-w-md)
$modal_width = $modal_width ?? 'max-w-md';
?>
<div x-show="<?= $show ?>" x-cloak
     class="fixed inset-0 bg-navy/40 flex items-center justify-center z-50 p-4"
     x-transition.opacity>
    <div @click.outside="<?= $show ?> = false"
         class="bg-white rounded-2xl shadow-xl w-full <?= $modal_width ?> p-6"
         x-transition>
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-bold text-navy"><?= e($modal_title) ?></h3>
            <button @click="<?= $show ?> = false" class="text-navy/40 hover:text-navy transition">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>
        <?= $content ?>
    </div>
</div>
