<?php
// views/components/admin_stat_card.php
// Expects: $icon, $label, $value, $tint (tailwind color word, default gold)
$tint = $tint ?? 'gold';
?>
<div class="bg-white border border-navy/10 rounded-2xl p-6 flex items-center gap-4 shadow-sm">
    <div class="w-12 h-12 rounded-xl bg-<?= $tint ?>/15 flex items-center justify-center flex-shrink-0">
        <i class="<?= $icon ?> text-<?= $tint ?>-dark text-2xl"></i>
    </div>
    <div>
        <p class="text-2xl font-bold text-navy"><?= e((string) $value) ?></p>
        <p class="text-sm text-navy/60"><?= e($label) ?></p>
    </div>
</div>
