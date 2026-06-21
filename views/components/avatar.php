<?php
// views/components/avatar.php
// Expects: $size (tailwind size classes, default w-9 h-9), $text_size (default text-sm)
// Reads avatar + username from $_SESSION.
$size      = $size ?? 'w-9 h-9';
$text_size = $text_size ?? 'text-sm';
$avatar    = $_SESSION['avatar'] ?? null;
?>
<?php if ($avatar): ?>
    <img src="<?= url('/assets/uploads/avatars') ?>/<?= e($avatar) ?>" alt="Profile photo"
         class="<?= $size ?> rounded-full object-cover flex-shrink-0">
<?php else: ?>
    <span class="<?= $size ?> rounded-full bg-navy text-white flex items-center justify-center font-semibold <?= $text_size ?> flex-shrink-0">
        <?= e(strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1))) ?>
    </span>
<?php endif; ?>
