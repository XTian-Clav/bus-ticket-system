<?php
declare(strict_types=1);

// app/queries/query_schedules.php

require_once __DIR__ . '/../core/core_db.php';

function get_all_schedules(): array
{
    $sql = '
        SELECT
            s.id,
            s.fare,
            s.depart_time,
            s.created_at,
            b.bus_num,
            b.seats,
            r.source,
            r.destination
        FROM schedules s
        JOIN buses  b ON b.id = s.bus_id
        JOIN routes r ON r.id = s.route_id
        ORDER BY s.depart_time ASC
    ';
    return db()->query($sql)->fetchAll();
}

function get_schedule_by_id(int $id): array|false
{
    $sql = '
        SELECT
            s.id,
            s.bus_id,
            s.route_id,
            s.fare,
            s.depart_time,
            b.bus_num,
            b.seats,
            r.source,
            r.destination
        FROM schedules s
        JOIN buses  b ON b.id = s.bus_id
        JOIN routes r ON r.id = s.route_id
        WHERE s.id = ?
        LIMIT 1
    ';
    $stmt = db()->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function get_schedules_by_route(int $route_id): array
{
    $sql = '
        SELECT
            s.id,
            s.fare,
            s.depart_time,
            b.bus_num,
            b.seats
        FROM schedules s
        JOIN buses b ON b.id = s.bus_id
        WHERE s.route_id = ?
        ORDER BY s.depart_time ASC
    ';
    $stmt = db()->prepare($sql);
    $stmt->execute([$route_id]);
    return $stmt->fetchAll();
}

function get_available_seats(int $schedule_id): int
{
    $sql = '
        SELECT b.seats - COUNT(bk.id) AS available
        FROM schedules s
        JOIN buses b ON b.id = s.bus_id
        LEFT JOIN bookings bk
            ON bk.schedule_id = s.id AND bk.status = "confirmed"
        WHERE s.id = ?
        GROUP BY b.seats
    ';
    $stmt = db()->prepare($sql);
    $stmt->execute([$schedule_id]);
    $row = $stmt->fetch();
    return $row ? (int) $row['available'] : 0;
}
