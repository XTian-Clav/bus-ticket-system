<?php
declare(strict_types=1);

// register.php

require_once __DIR__ . '/app/core/core_view.php';

start_session();
require_guest_page();

ob_start();
?>

<section class="max-w-2xl mx-auto px-6 py-10 md:py-16">

    <div class="bg-white border border-navy/10 rounded-2xl shadow-sm p-8">
        <h1 class="text-2xl font-bold text-navy mb-1">Create an account</h1>
        <p class="text-navy/60 text-sm mb-6">Book bus trips in just a few clicks.</p>

        <form id="register-form" class="space-y-4">
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Full name</label>
                    <input type="text" name="fullname" required placeholder="Enter your full name"
                           class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Username</label>
                    <input type="text" name="username" required placeholder="Enter your username"
                           class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Email</label>
                    <input type="email" name="email" required placeholder="Enter your email"
                           class="w-full px-4 py-2.5 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Contact number</label>
                    <input type="text" name="contact" required placeholder="Enter your contact number"
                           pattern="09[0-9]{9}" maxlength="11" inputmode="numeric"
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
                    <p class="text-xs text-navy/40 mt-1">8+ characters, 1 uppercase, 1 number, 1 special character.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy mb-1">Confirm password</label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="confirm_password" required placeholder="Confirm your password"
                               class="w-full px-4 py-2.5 pr-11 border border-navy/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                        <button type="button" @click="show = !show" tabindex="-1"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-navy/40 hover:text-navy transition">
                            <i :class="show ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-2.5 bg-navy text-white font-semibold rounded-lg hover:bg-navy-light transition">
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