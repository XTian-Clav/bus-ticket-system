<?php
declare(strict_types=1);

// app/actions/action_bookings.php

require_once __DIR__ . '/../core/core_crud.php';
require_once __DIR__ . '/../core/core_response.php';
require_once __DIR__ . '/../queries/query_bookings.php';
require_once __DIR__ . '/../queries/query_schedules.php';

function book_seat(int $user_id, int $schedule_id, int $seat_num): int
{
    if ($schedule_id < 1 || $seat_num < 1) {
        json_error('A valid schedule and seat number are required.');
    }

    $schedule = get_schedule_by_id($schedule_id);

    if (!$schedule) {
        json_error('Schedule not found.', 404);
    }

    if ($seat_num > (int) $schedule['seats']) {
        json_error("Seat {$seat_num} does not exist on this bus.");
    }

    if (seat_is_taken($schedule_id, $seat_num)) {
        json_error("Seat {$seat_num} is already booked.");
    }

    if (get_available_seats($schedule_id) <= 0) {
        json_error('No seats available on this schedule.');
    }

    return db_insert('bookings', [
        'schedule_id' => $schedule_id,
        'user_id'     => $user_id,
        'seat_num'    => $seat_num,
        'status'      => 'confirmed',
    ]);
}

function cancel_booking(int $booking_id, int $requesting_user_id, bool $is_admin = false): bool
{
    $booking = db_find_by_id('bookings', $booking_id);

    if (!$booking) {
        json_error('Booking not found.', 404);
    }

    if (!$is_admin && (int) $booking['user_id'] !== $requesting_user_id) {
        json_error('Forbidden.', 403);
    }

    if ($booking['status'] === 'cancelled') {
        json_error('Booking is already cancelled.');
    }

    return db_update('bookings', $booking_id, ['status' => 'cancelled']);
}
