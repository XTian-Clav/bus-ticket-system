<?php

// index.php — landing page
// This is the single entry point users hit at the project root.
// Frontend content is intentionally left as a stub for now.

require_once __DIR__ . '/app/core/core_auth.php';

start_session();

// Once views are built, route based on session role, e.g.:
//   if (is_admin())    redirect('/views/admin/dashboard.php');
//   if (is_logged_in()) redirect('/views/user/dashboard.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bus Ticket System</title>
</head>
<body>
    <h1>Bus Ticket System</h1>
    <p>Landing page placeholder — frontend views coming soon.</p>
</body>
</html>
