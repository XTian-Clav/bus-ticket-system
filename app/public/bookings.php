<?php

// app/public/bookings.php
//
// GET    /bookings.php          → admin: all bookings | user: own bookings
// GET    /bookings.php?id=1     → admin: bookings for a schedule
// POST   /bookings.php          → any logged-in user: book a seat
// DELETE /bookings.php?id=1     → any logged-in user: cancel own booking (admin cancels any)

require_once __DIR__ . '/../core/core_auth.php';
require_once __DIR__ . '/../core/core_response.php';
require_once __DIR__ . '/../actions/action_bookings.php';
require_once __DIR__ . '/../queries/query_bookings.php';

start_session();
require_login();

$method  = $_SERVER['REQUEST_METHOD'];
$id      = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$input   = json_decode(file_get_contents('php://input'), true) ?? [];
$user_id = auth_user_id();

if ($method === 'GET') {
    if (is_admin() && $id > 0) {
        json_ok(['bookings' => get_bookings_by_schedule($id)]);
    }

    if (is_admin()) {
        json_ok(['bookings' => get_all_bookings()]);
    }

    json_ok(['bookings' => get_bookings_by_user($user_id)]);
}

if ($method === 'POST') {
    $booking_id = book_seat(
        user_id:     $user_id,
        schedule_id: (int) ($input['schedule_id'] ?? 0),
        seat_num:    (int) ($input['seat_num']    ?? 0),
    );
    json_ok(['booking_id' => $booking_id], 'Seat booked successfully.');
}

if ($method === 'DELETE') {
    if ($id < 1) json_error('Booking ID is required.');

    cancel_booking(
        booking_id:         $id,
        requesting_user_id: $user_id,
        is_admin:           is_admin(),
    );

    json_ok([], 'Booking cancelled successfully.');
}

json_error('Method not allowed.', 405);
