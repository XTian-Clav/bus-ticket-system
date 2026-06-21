<?php
// views/components/admin_stat_card.php
// Expects: $icon, $icon_bg (tailwind bg class), $icon_color (tailwind text class), $label, $value
?>
<div class="bg-white border border-navy/10 rounded-2xl p-6 flex items-center gap-4 shadow-sm">
    <div class="w-12 h-12 rounded-xl <?= $icon_bg ?> flex items-center justify-center flex-shrink-0">
        <i class="<?= $icon ?> <?= $icon_color ?> text-2xl"></i>
    </div>
    <div>
        <p class="text-2xl font-bold text-navy"><?= e((string) $value) ?></p>
        <p class="text-sm text-navy/60"><?= e($label) ?></p>
    </div>
</div>
