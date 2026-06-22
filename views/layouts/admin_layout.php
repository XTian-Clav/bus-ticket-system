<?php
// views/layouts/admin_layout.php — admin dashboard layout (sidebar + topbar)
// Expects: $content, $title

require_once __DIR__ . '/../../app/core/core_view.php';

start_session();
require_admin_page();
?>
<!DOCTYPE html>
<html lang="en" class="h-full"> <head>
    <?php require __DIR__ . '/../partials/head.php'; ?>
</head>
<body class="bg-offwhite h-screen overflow-hidden flex" x-data="{ sidebarOpen: false }">

    <?php require __DIR__ . '/../partials/admin_sidebar.php'; ?>

    <div class="flex-1 min-w-0 flex flex-col h-full overflow-hidden">
        
        <?php require __DIR__ . '/../partials/topbar.php'; ?>

        <main class="flex-1 p-4 md:p-6 overflow-y-auto">
            <?= $content ?>
        </main>
    </div>

    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>
</html>