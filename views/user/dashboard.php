<?php

// views/user/dashboard.php

require_once __DIR__ . '/../../app/core/core_view.php';

$title = 'Dashboard';

ob_start();
?>

<div class="bg-navy rounded-2xl p-6 sm:p-10 text-white shadow-sm flex items-center justify-between flex-wrap gap-6">
    <div>
        <p class="text-gold font-semibold tracking-wide uppercase text-sm mb-2">Welcome back</p>
        <h2 class="text-2xl sm:text-3xl font-extrabold mb-2"><?= e($_SESSION['username'] ?? '') ?></h2>
        <p class="text-white/70 max-w-md">Browse available schedules and book your next trip in just a few taps.</p>
    </div>
    <a href="<?= url('/views/user/schedules.php') ?>"
       class="flex items-center gap-2 px-6 py-3 bg-gold text-navy font-semibold rounded-lg hover:bg-gold-light transition flex-shrink-0">
        <i class="ri-search-line"></i> Browse Schedules
    </a>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/user.php';
