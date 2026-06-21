<?php
declare(strict_types=1);

// index.php — landing page

require_once __DIR__ . '/app/core/core_view.php';

start_session();

ob_start();
?>

<section id="home" class="max-w-6xl mx-auto px-6 py-24 grid md:grid-cols-2 gap-12 items-center">

    <div>
        <p class="text-gold font-semibold tracking-wide uppercase text-sm mb-3">Travel made simple</p>
        <h1 class="text-4xl md:text-5xl font-extrabold text-navy leading-tight mb-5">
            Book your next bus trip in minutes.
        </h1>
        <p class="text-navy/70 text-lg mb-8 max-w-md">
            Browse schedules, pick a seat, and manage your bookings — all from one clean dashboard.
        </p>
        <div class="flex flex-wrap gap-4">
            <a href="<?= url('/register.php') ?>"
               class="px-6 py-3 bg-navy text-white font-semibold rounded-lg hover:bg-navy-light transition">
                Get Started
            </a>
            <a href="<?= url('/login.php') ?>"
               class="px-6 py-3 border border-navy text-navy font-semibold rounded-lg hover:bg-navy hover:text-white transition">
                I have an account
            </a>
        </div>
    </div>

    <div class="bg-navy rounded-2xl p-10 text-white shadow-xl">
        <i class="ri-roadster-fill text-gold text-6xl mb-6"></i>
        <div class="space-y-4">
            <div class="flex items-center gap-3">
                <i class="ri-checkbox-circle-fill text-gold"></i>
                <span class="text-white/90">Real-time seat availability</span>
            </div>
            <div class="flex items-center gap-3">
                <i class="ri-checkbox-circle-fill text-gold"></i>
                <span class="text-white/90">Instant booking confirmation</span>
            </div>
            <div class="flex items-center gap-3">
                <i class="ri-checkbox-circle-fill text-gold"></i>
                <span class="text-white/90">Manage trips anytime</span>
            </div>
        </div>
    </div>

</section>

<?php
$content = ob_get_clean();
$title   = 'Home';
require __DIR__ . '/views/layouts/guest.php';
