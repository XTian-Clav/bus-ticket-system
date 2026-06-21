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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/fonts/remixicon.css">

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    navy:  { DEFAULT: '#0F1F3D', dark: '#0A1530', light: '#1C3463' },
                    gold:  { DEFAULT: '#C9A24B', dark: '#A9853B', light: '#E0C374' },
                    offwhite: '#F8F6F1',
                },
                fontFamily: {
                    sans: ['Montserrat', 'sans-serif'],
                },
            },
        },
    };
</script>

<script src="//unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<style>
    [x-cloak] { display: none !important; }
    body { font-family: 'Montserrat', sans-serif; }
</style>
