<?php
declare(strict_types=1);

// index.php — landing page

require_once __DIR__ . '/app/core/core_view.php';

start_session();

ob_start();
?>

<section id="home" class="max-w-6xl mx-auto px-6 py-16 md:py-24 grid md:grid-cols-2 gap-12 items-center">

    <div>
        <p class="text-gold font-semibold tracking-wide uppercase text-sm mb-3">PalTransit • Travel Made Simple</p>
        <h1 class="text-4xl md:text-5xl font-extrabold text-navy leading-tight mb-5">
        Book your next bus journey across Palawan.
        </h1>
        <p class="text-navy/70 text-lg mb-8 max-w-md">
            Secure your seats, browse province-wide schedules, and manage your travel bookings all from one seamless digital platform.
        </p>
        <div class="flex flex-col w-full gap-4 sm:flex-row">
            <a href="<?= url('/register.php') ?>"
            class="flex items-center justify-center w-full gap-2 px-6 py-3 font-semibold text-white transition rounded-lg sm:w-auto bg-navy hover:bg-navy-light">
                <i class="ri-user-add-fill"></i> Get Started
            </a>
            <a href="<?= url('/login.php') ?>"
            class="flex items-center justify-center w-full gap-2 px-6 py-3 font-semibold transition border rounded-lg sm:w-auto border-navy text-navy hover:bg-navy hover:text-white">
                <i class="ri-login-box-line"></i> I have an account
            </a>
        </div>
    </div>

    <div class="flex flex-col items-center w-full max-w-3xl px-4">
        
        <figure class="hidden justify-center w-full max-w-sm -mb-2 md:flex">
            <img src="assets/images/bus.png" 
                 alt="PalTransit Modern Bus Graphic" 
                 class="object-contain w-full h-auto" />
        </figure>

        <ul class="grid w-full grid-cols-1 divide-y sm:grid-cols-3 sm:divide-y-0 sm:divide-x text-navy divide-navy/10">
            
            <li class="flex items-center gap-2 py-4 sm:py-0 sm:pr-4">
                <i class="flex-shrink-0 text-lg ri-checkbox-circle-fill text-gold"></i>
                <span class="text-sm font-semibold leading-snug tracking-tight">Real-time seat availability</span>
            </li>
            
            <li class="flex items-center gap-2 py-4 sm:py-0 sm:px-4">
                <i class="flex-shrink-0 text-lg ri-checkbox-circle-fill text-gold"></i>
                <span class="text-sm font-semibold leading-snug tracking-tight">Instant booking</span>
            </li>
            
            <li class="flex items-center gap-2 pt-4 sm:pt-0 sm:pl-4">
                <i class="flex-shrink-0 text-lg ri-checkbox-circle-fill text-gold"></i>
                <span class="text-sm font-semibold leading-snug tracking-tight">Manage trips anytime</span>
            </li>
            
        </ul>
    </div>

</section>

<?php
$content = ob_get_clean();
$title   = 'Home';
require __DIR__ . '/views/layouts/guest.php';
