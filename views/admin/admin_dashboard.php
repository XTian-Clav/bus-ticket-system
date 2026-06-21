<?php

// views/admin/admin_dashboard.php

require_once __DIR__ . '/../../app/core/core_view.php';
require_once __DIR__ . '/../../app/core/core_crud.php';
require_once __DIR__ . '/../../app/queries/query_bookings.php';

$title = 'Dashboard';

$stats = [
    ['ri-group-line',    'bg-gold/10', 'text-gold-dark', 'Total Users',     count(db_find_all('users'))],
    ['ri-bus-2-line',    'bg-gold/10', 'text-gold-dark', 'Total Buses',     count(db_find_all('buses'))],
    ['ri-route-line',    'bg-gold/10', 'text-gold-dark', 'Total Routes',    count(db_find_all('routes'))],
    ['ri-calendar-line', 'bg-gold/10', 'text-gold-dark', 'Total Schedules', count(db_find_all('schedules'))],
    ['ri-ticket-2-line', 'bg-gold/10', 'text-gold-dark', 'Total Bookings',  count(get_all_bookings())],
];

ob_start();
?>

<div class="bg-navy rounded-2xl p-6 sm:p-10 text-white shadow-sm flex items-center justify-between flex-wrap gap-6 mb-6">
    <div>
        <p class="text-gold font-semibold tracking-wide uppercase text-sm mb-2">Welcome back</p>
        <h2 class="text-2xl sm:text-3xl font-extrabold mb-2"><?= e($_SESSION['username'] ?? '') ?></h2>
        <p class="text-white/70 max-w-md">Monitor system analytics, manage routes, and oversee real-time passenger bookings.</p>
    </div>
    <a href="<?= url('/views/admin/admin_schedules.php') ?>"
       class="flex items-center gap-2 px-6 py-3 bg-gold text-navy font-semibold rounded-lg hover:bg-gold-light transition flex-shrink-0">
        <i class="ri-calendar-2-fill"></i> Manage Schedules
    </a>
</div>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($stats as [$icon, $icon_bg, $icon_color, $label, $value]): ?>
        <?php require __DIR__ . '/../components/admin_stat_card.php'; ?>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin_layout.php';
