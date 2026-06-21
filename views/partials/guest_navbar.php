<?php
// views/partials/guest_navbar.php — top nav for landing, login, and register pages
?>
<nav class="bg-navy" x-data="{ open: false }">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">

        <a href="<?= url('/index.php') ?>" class="flex items-center gap-2 text-white">
            <i class="ri-bus-2-fill text-gold text-2xl"></i>
            <span class="font-bold text-lg tracking-wide">PalTransit</span>
        </a>

        <div class="hidden md:flex items-center gap-8 text-sm font-medium text-white/80">
            <a href="<?= url('/index.php#routes') ?>" class="flex items-center gap-1.5 hover:text-gold transition">
                <i class="ri-route-line"></i> Routes
            </a>
            <a href="<?= url('/index.php#schedule') ?>" class="flex items-center gap-1.5 hover:text-gold transition">
                <i class="ri-calendar-line"></i> Schedules
            </a>
            <a href="<?= url('/index.php#about') ?>" class="flex items-center gap-1.5 hover:text-gold transition">
                <i class="ri-information-line"></i> About
            </a>
            <a href="<?= url('/index.php#contact') ?>" class="flex items-center gap-1.5 hover:text-gold transition">
                <i class="ri-mail-line"></i> Contact
            </a>
        </div>

        <div class="hidden sm:flex items-center gap-3">
            <a href="<?= url('/register.php') ?>"
               class="flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-gold border border-gold rounded-lg hover:bg-gold hover:text-navy transition">
               Register
            </a>
            <a href="<?= url('/login.php') ?>"
               class="flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-navy bg-gold rounded-lg hover:bg-gold-light transition">
               Login
            </a>
        </div>

        <button @click="open = !open" class="md:hidden text-white">
            <i :class="open ? 'ri-close-line' : 'ri-menu-line'" class="text-2xl"></i>
        </button>

    </div>

    <div x-show="open" x-cloak x-transition class="md:hidden bg-navy-dark border-t border-white/10 px-6 py-4 space-y-1">
        <a href="<?= url('/index.php#home') ?>" class="flex items-center gap-2 px-2 py-2.5 text-sm font-medium text-white/80 hover:text-gold transition">
            <i class="ri-home-5-line"></i> Home
        </a>
        <a href="<?= url('/index.php#about') ?>" class="flex items-center gap-2 px-2 py-2.5 text-sm font-medium text-white/80 hover:text-gold transition">
            <i class="ri-information-line"></i> About
        </a>
        <a href="<?= url('/index.php#contact') ?>" class="flex items-center gap-2 px-2 py-2.5 text-sm font-medium text-white/80 hover:text-gold transition">
            <i class="ri-mail-line"></i> Contact
        </a>
        <div class="flex items-center gap-3 pt-3">
            <a href="<?= url('/register.php') ?>"
               class="flex-1 flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-semibold text-gold border border-gold rounded-lg hover:bg-gold hover:text-navy transition">
                <i class="ri-user-add-line"></i> Register
            </a>
            <a href="<?= url('/login.php') ?>"
               class="flex-1 flex items-center justify-center gap-1.5 px-4 py-2 text-sm font-semibold text-navy bg-gold rounded-lg hover:bg-gold-light transition">
                <i class="ri-login-box-line"></i> Login
            </a>
        </div>
    </div>
</nav>
