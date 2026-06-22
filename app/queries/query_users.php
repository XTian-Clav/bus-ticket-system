<?php
declare(strict_types=1);

// app/queries/query_users.php

require_once __DIR__ . '/../core/core_db.php';

function get_user_by_id(int $id): array|false
{
    $stmt = db()->prepare('SELECT id, username, fullname, email, contact, role, created_at FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function get_user_by_email(string $email): array|false
{
    $stmt = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function get_user_by_username(string $username): array|false
{
    $stmt = db()->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    return $stmt->fetch();
}

function get_all_users(string $role = ''): array
{
    if ($role === '') {
        return db()->query(
            'SELECT id, username, fullname, email, contact, role, created_at FROM users ORDER BY fullname'
        )->fetchAll();
    }

    $stmt = db()->prepare(
        'SELECT id, username, fullname, email, contact, role, created_at FROM users WHERE role = ? ORDER BY fullname'
    );
    $stmt->execute([$role]);
    return $stmt->fetchAll();
}
