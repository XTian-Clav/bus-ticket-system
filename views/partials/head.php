<?php $title = $title ?? 'Bus Ticket System'; ?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($title) ?> · Bus Ticket System</title>

<script>
    const BASE_URL = <?= json_encode(BASE_URL) ?>;

    // Mirrors the PHP url() helper in core_response.php.
    function url(path = '') {
        path = path.replace(/^\/+|\/+$/g, '');
        return path === '' ? (BASE_URL || '/') : `${BASE_URL}/${path}`;
    }
</script>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.6.0/fonts/remixicon.css">

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    navy:  { DEFAULT: '#0F1F3D', dark: '#0A1530', light: '#1C3463' },
                    gold:  { DEFAULT: '#E2A72B', dark: '#BA851A', light: '#F5D56E' },
                    offwhite: '#F2ECE1',
                },
                fontFamily: {
                    sans: ['Montserrat', 'sans-serif'],
                },
                keyframes: {
                    fadeIn: {
                        '0%':   { opacity: 0, transform: 'translateY(-6px)' },
                        '100%': { opacity: 1, transform: 'translateY(0)' },
                    },
                },
            },
        },
    };
</script>

<script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<style>
    [x-cloak] { display: none !important; }
    body { font-family: 'Montserrat', sans-serif; }

    /* Consistent native control styling (selects + date/time pickers) */
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none' stroke='%230F1F3D' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M5 7l5 5 5-5'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.9rem center;
        background-size: 1rem;
        padding-right: 2.75rem;
        cursor: pointer;
    }

    input[type="date"],
    input[type="datetime-local"],
    input[type="time"] {
        cursor: pointer;
        color-scheme: light;
    }

    input[type="date"]::-webkit-calendar-picker-indicator,
    input[type="datetime-local"]::-webkit-calendar-picker-indicator,
    input[type="time"]::-webkit-calendar-picker-indicator {
        cursor: pointer;
        opacity: 0.55;
        transition: opacity .15s ease;
    }

    input[type="date"]:hover::-webkit-calendar-picker-indicator,
    input[type="datetime-local"]:hover::-webkit-calendar-picker-indicator,
    input[type="time"]:hover::-webkit-calendar-picker-indicator {
        opacity: 1;
    }
</style>
