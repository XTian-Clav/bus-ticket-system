<?php
declare(strict_types=1);

// register.php

require_once __DIR__ . '/app/core/core_view.php';

start_session();
require_guest_page();

ob_start();
?>

<section class="max-w-md mx-auto px-6 py-16">

    <div class="bg-white border border-navy/10 rounded-2xl shadow-sm p-8">
        <h1 class="text-2xl font-bold text-navy mb-1">Create an account</h1>
        <p class="text-navy/60 text-sm mb-6">Book bus trips in just a few clicks.</p>

        <form id="register-form" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-navy mb-1">Full name</label>
                <input type="text" name="fullname" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>

            <div>
                <label class="block text-sm font-medium text-navy mb-1">Username</label>
                <input type="text" name="username" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                <p class="text-xs text-navy/40 mt-1">3–30 characters: letters, numbers, underscores.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-navy mb-1">Email</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>

            <div>
                <label class="block text-sm font-medium text-navy mb-1">Contact number</label>
                <input type="text" name="contact" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>

            <div>
                <label class="block text-sm font-medium text-navy mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                <p class="text-xs text-navy/40 mt-1">8+ characters, 1 uppercase, 1 number, 1 special character.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-navy mb-1">Confirm password</label>
                <input type="password" name="confirm_password" required
                       class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
            </div>

            <button type="submit"
                    class="w-full py-2.5 bg-navy text-white font-semibold rounded-lg hover:bg-navy-light transition">
                Register
            </button>
        </form>

        <p class="text-sm text-navy/60 mt-6 text-center">
            Already have an account?
            <a href="<?= url('/login.php') ?>" class="text-gold font-semibold hover:underline">Log in</a>
        </p>
    </div>

</section>

<script>
    document.getElementById('register-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const data = formToObject(e.target);

        if (data.password !== data.confirm_password) {
            toast('Passwords do not match.', true);
            return;
        }

        try {
            await api('users.php', 'POST', data);
            toast('Account created. You can now log in.');
            setTimeout(() => window.location.href = url('/login.php'), 1000);
        } catch (err) {
            toast(err.message, true);
        }
    });
</script>

<?php
$content = ob_get_clean();
$title   = 'Register';
require __DIR__ . '/views/layouts/guest.php';
