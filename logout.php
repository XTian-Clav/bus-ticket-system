<?php
declare(strict_types=1);

// logout.php

require_once __DIR__ . '/app/core/core_view.php';

start_session();
clear_auth_session();
redirect(url('/index.php'));
