<!DOCTYPE html>
<html lang="en">
<head>
    <?php require __DIR__ . '/../partials/head.php'; ?>
</head>
<body class="bg-offwhite min-h-screen flex flex-col">

    <?php require __DIR__ . '/../partials/guest_navbar.php'; ?>

    <main class="flex-1">
        <?= $content ?>
    </main>

    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>
</html>
