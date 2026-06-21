<?php

// views/admin/admin_dashboard.php

require_once __DIR__ . '/../../app/core/core_view.php';
require_once __DIR__ . '/../../app/core/core_crud.php';
require_once __DIR__ . '/../../app/queries/query_bookings.php';

$title = 'Dashboard';

$stats = [
    ['ri-group-line',    'Total Users',     count(db_find_all('users')),    'gold'],
    ['ri-bus-2-line',    'Total Buses',     count(db_find_all('buses')),    'gold'],
    ['ri-route-line',    'Total Routes',    count(db_find_all('routes')),   'gold'],
    ['ri-calendar-line', 'Total Schedules', count(db_find_all('schedules')),'gold'],
    ['ri-ticket-2-line', 'Total Bookings',  count(get_all_bookings()),      'gold'],
];

ob_start();
?>

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach ($stats as [$icon, $label, $value, $tint]): ?>
        <?php require __DIR__ . '/../components/admin_stat_card.php'; ?>
    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin_layout.php';
