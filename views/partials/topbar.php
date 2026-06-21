<header class="h-16 bg-white border-b border-navy/10 flex items-center justify-between px-6">

    <h1 class="text-lg font-bold text-navy"><?= e($title ?? '') ?></h1>

    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" @click.outside="open = false"
                class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-offwhite transition">
            <?php require __DIR__ . '/../components/avatar.php'; ?>
            <span class="text-sm font-medium text-navy hidden sm:block"><?= e($_SESSION['username'] ?? '') ?></span>
            <i class="ri-arrow-down-s-line text-navy/50"></i>
        </button>

        <div x-show="open" x-cloak x-transition
             class="absolute right-0 mt-2 w-48 bg-white border border-navy/10 rounded-lg shadow-lg py-1 z-50">
            <a href="<?= is_admin() ? url('/views/admin/admin_profile.php') : url('/views/user/profile.php') ?>"
               class="flex items-center gap-2 px-4 py-2.5 text-sm text-navy hover:bg-offwhite transition">
                <i class="ri-user-line"></i> Profile
            </a>
            <a href="<?= url('/logout.php') ?>"
               class="flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-offwhite transition">
                <i class="ri-logout-box-line"></i> Logout
            </a>
        </div>
    </div>

</header>
