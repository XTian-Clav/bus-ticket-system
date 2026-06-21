<?php
declare(strict_types=1);

return [
    'avatar_dir'    => __DIR__ . '/../../assets/uploads/avatars',
    'avatar_url'    => url('/assets/uploads/avatars'),
    'max_size'      => 2 * 1024 * 1024, // 2MB
    'allowed_types' => ['jpg', 'jpeg', 'png', 'webp'],
];
