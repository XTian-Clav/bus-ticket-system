<?php
// views/partials/user_sidebar.php — sidebar nav for the user dashboard layout
$current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$links = [
    [url('/views/user/dashboard.php'), 'ri-dashboard-line', 'Dashboard'],
    [url('/views/user/schedules.php'), 'ri-calendar-line',  'Schedules'],
    [url('/views/user/bookings.php'),  'ri-ticket-2-line',  'My Bookings'],
];
?>

<aside class="w-64 bg-navy min-h-screen flex-shrink-0 flex flex-col">

    <div class="h-16 flex items-center gap-2 px-6 border-b border-white/10">
        <i class="ri-bus-2-fill text-gold text-2xl"></i>
        <span class="font-bold text-white text-lg tracking-wide">RoadReady</span>
    </div>

    <nav class="flex-1 px-3 py-6 space-y-1">
        <?php foreach ($links as [$href, $icon, $label]): $active = $current === $href; ?>
            <a href="<?= $href ?>"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition <?= $active ? 'bg-gold text-navy' : 'text-white/70 hover:bg-white/10 hover:text-white' ?>">
                <i class="<?= $icon ?> text-lg"></i>
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </nav>

</aside>
