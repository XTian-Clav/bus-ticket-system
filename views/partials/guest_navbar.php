<?php
// views/partials/guest_navbar.php — top nav for landing, login, and register pages
?>
<nav class="bg-navy">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">

        <a href="<?= url('/index.php') ?>" class="flex items-center gap-2 text-white">
            <i class="ri-bus-2-fill text-gold text-2xl"></i>
            <span class="font-bold text-lg tracking-wide">RoadReady</span>
        </a>

        <div class="hidden md:flex items-center gap-8 text-sm font-medium text-white/80">
            <a href="<?= url('/index.php#home') ?>" class="hover:text-gold transition">Home</a>
            <a href="<?= url('/index.php#about') ?>" class="hover:text-gold transition">About</a>
            <a href="<?= url('/index.php#contact') ?>" class="hover:text-gold transition">Contact</a>
        </div>

        <div class="flex items-center gap-3">
            <a href="<?= url('/register.php') ?>"
               class="hidden sm:inline-block px-4 py-2 text-sm font-semibold text-gold border border-gold rounded-lg hover:bg-gold hover:text-navy transition">
                Register
            </a>
            <a href="<?= url('/login.php') ?>"
               class="px-4 py-2 text-sm font-semibold text-navy bg-gold rounded-lg hover:bg-gold-light transition">
                Login
            </a>
        </div>

    </div>
</nav>
