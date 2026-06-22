<?php
// views/components/avatar.php
$size      = $size ?? 'w-9 h-9';
$text_size = $text_size ?? 'text-sm';

$username = $_SESSION['username'] ?? 'User';
$initial  = strtoupper(substr($username, 0, 1));
?>

<span class="<?= $size ?> rounded-full bg-navy text-white flex items-center justify-center font-semibold <?= $text_size ?> flex-shrink-0">
    <?= e($initial) ?>
</span>