<?php
declare(strict_types=1);

// login.php

require_once __DIR__ . '/app/core/core_view.php';

start_session();
require_guest_page();

ob_start();
?>

<section class="max-w-lg mx-auto px-6 py-12 md:py-20">

    <div class="bg-white border border-navy/10 rounded-2xl shadow-sm p-8">
        <h1 class="text-2xl font-bold text-navy mb-1">Welcome back</h1>
        <p class="text-navy/60 text-sm mb-6">Log in to manage your bookings.</p>

        <form id="login-form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Username or email</label>
                <input type="text" name="username_or_email" required placeholder="Enter your username or email"
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>

            <div>
                <label class="block text-sm font-medium text-navy mb-1">Password</label>
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" name="password" required placeholder="Enter your password"
                           class="w-full px-4 py-2.5 pr-11 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                    <button type="button" @click="show = !show" tabindex="-1"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-navy/40 hover:text-navy transition">
                        <i :class="show ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
                    </button>
                </div>
            </div>

            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-2.5 bg-navy text-white font-semibold rounded-lg hover:bg-navy-light transition">
                    Log In
            </button>
        </form>

        <p class="text-sm text-navy/60 mt-6 text-center">
            Don't have an account?
            <a href="<?= url('/register.php') ?>" class="text-gold font-semibold hover:underline">Register</a>
        </p>
    </div>

</section>

<script>
    document.getElementById('login-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        try {
            const data = await api('auth.php', 'POST', formToObject(e.target));
            toast(data.message);
            window.location.href = url(data.data.role === 'admin' ? '/views/admin/admin_dashboard.php' : '/views/user/dashboard.php');
        } catch (err) {
            toast(err.message, true);
        }
    });
</script>

<?php
$content = ob_get_clean();
$title   = 'Login';
require __DIR__ . '/views/layouts/guest.php';
