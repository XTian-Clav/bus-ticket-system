<?php
declare(strict_types=1);

// app/queries/query_bookings.php

require_once __DIR__ . '/../core/core_db.php';

function get_all_bookings(): array
{
    $sql = '
        SELECT
            bk.id,
            bk.seat_num,
            bk.status,
            bk.created_at,
            s.fare,
            s.depart_time,
            r.source,
            r.destination,
            b.bus_num,
            u.username,
            u.fullname
        FROM bookings bk
        JOIN schedules s ON s.id = bk.schedule_id
        JOIN routes    r ON r.id = s.route_id
        JOIN buses     b ON b.id = s.bus_id
        JOIN users     u ON u.id = bk.user_id
        ORDER BY s.depart_time DESC
    ';
    return db()->query($sql)->fetchAll();
}

function get_bookings_by_user(int $user_id): array
{
    $sql = '
        SELECT
            bk.id,
            bk.seat_num,
            bk.status,
            bk.created_at,
            s.fare,
            s.depart_time,
            r.source,
            r.destination,
            b.bus_num
        FROM bookings bk
        JOIN schedules s ON s.id = bk.schedule_id
        JOIN routes    r ON r.id = s.route_id
        JOIN buses     b ON b.id = s.bus_id
        WHERE bk.user_id = ?
        ORDER BY s.depart_time DESC
    ';
    $stmt = db()->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function get_bookings_by_schedule(int $schedule_id): array
{
    $sql = '
        SELECT
            bk.id,
            bk.seat_num,
            bk.status,
            u.username,
            u.fullname,
            u.email
        FROM bookings bk
        JOIN users u ON u.id = bk.user_id
        WHERE bk.schedule_id = ?
        ORDER BY bk.seat_num ASC
    ';
    $stmt = db()->prepare($sql);
    $stmt->execute([$schedule_id]);
    return $stmt->fetchAll();
}

function seat_is_taken(int $schedule_id, int $seat_num): bool
{
    $stmt = db()->prepare(
        'SELECT COUNT(*) FROM bookings WHERE schedule_id = ? AND seat_num = ? AND status = "confirmed"'
    );
    $stmt->execute([$schedule_id, $seat_num]);
    return (int) $stmt->fetchColumn() > 0;
}
