<?php
// views/partials/user_sidebar.php — sidebar nav for the user dashboard layout
$current = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$links = [
    [url('/views/user/dashboard.php'), 'ri-dashboard-line', 'Dashboard'],
    [url('/views/user/schedules.php'), 'ri-calendar-line',  'Schedules'],
    [url('/views/user/bookings.php'),  'ri-ticket-2-line',  'My Bookings'],
];
?>

<!-- Mobile backdrop -->
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
     class="fixed inset-0 bg-navy/40 z-30 md:hidden" x-transition.opacity></div>

<aside class="w-64 bg-navy min-h-screen flex-shrink-0 flex flex-col fixed inset-y-0 left-0 z-40 transform transition-transform duration-200 md:static md:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <div class="h-16 flex items-center justify-between gap-2 px-6 border-b border-white/10">
        <a href="<?= url('/views/user/dashboard.php') ?>" class="flex items-center gap-2">
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

    <!-- User card -->
    <div class="px-3 py-4 border-t border-white/10">
        <a href="<?= url('/views/user/profile.php') ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/10 transition group">
            <span class="w-8 h-8 rounded-full bg-gold text-navy flex items-center justify-center font-bold text-sm flex-shrink-0">
                <?= e(strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1))) ?>
            </span>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-white truncate"><?= e($_SESSION['username'] ?? '—') ?></p>
                <p class="text-xs text-white/50 capitalize"><?= e($_SESSION['role'] ?? '') ?></p>
            </div>
            <i class="ri-arrow-right-s-line text-white/30 group-hover:text-white/60 transition ml-auto flex-shrink-0"></i>
        </a>
    </div>

</aside>
