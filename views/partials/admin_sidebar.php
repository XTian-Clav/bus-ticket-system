<?php
// views/partials/admin_sidebar.php — sidebar nav for the admin dashboard layout
$current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$links = [
    [url('/views/admin/admin_dashboard.php'), 'ri-dashboard-line', 'Dashboard'],
    [url('/views/admin/admin_buses.php'),     'ri-bus-2-line',     'Buses'],
    [url('/views/admin/admin_routes.php'),    'ri-route-line',     'Routes'],
    [url('/views/admin/admin_schedules.php'), 'ri-calendar-line',  'Schedules'],
    [url('/views/admin/admin_bookings.php'),  'ri-ticket-2-line',  'Bookings'],
    [url('/views/admin/admin_users.php'),     'ri-group-line',     'Users'],
];
?>

<!-- Mobile backdrop -->
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
     class="fixed inset-0 bg-navy/40 z-30 md:hidden" x-transition.opacity></div>

<aside class="w-64 bg-navy min-h-screen flex-shrink-0 flex flex-col fixed inset-y-0 left-0 z-40 transform transition-transform duration-200 md:static md:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <div class="h-16 flex items-center justify-between gap-2 px-6 border-b border-white/10">
        <a href="<?= url('/views/admin/admin_dashboard.php') ?>" class="flex items-center gap-2">
            <i class="ri-bus-2-fill text-gold text-2xl"></i>
            <span class="font-bold text-white text-m tracking-wide">PalTransit</span>
        </a>
        <button @click="sidebarOpen = false" class="md:hidden text-white/60 hover:text-white transition">
            <i class="ri-close-line text-xl"></i>
        </button>
    </div>

    <nav class="flex-1 px-3 py-6 space-y-1">
        <?php foreach ($links as [$href, $icon, $label]): $active = $current === $href; ?>
            <a href="<?= $href ?>" @click="sidebarOpen = false"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition <?= $active ? 'bg-gold text-navy' : 'text-white/70 hover:bg-white/10 hover:text-white' ?>">
                <i class="<?= $icon ?> text-lg"></i>
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </nav>

</aside>
