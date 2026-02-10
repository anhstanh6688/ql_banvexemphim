<?php
// Test Booking Transaction Logic
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';

$db = Database::getInstance();

echo "Testing Booking Transaction...\n";

// Setup: 
// User ID 1 (Admin) or create new user
// Showtime ID: Get from DB or create
// Seat ID: Get from DB

// 1. Get a Showtime
$db->query("SELECT id, price FROM showtimes LIMIT 1");
$showtime = $db->single();
if (!$showtime) {
    die("[FAIL] No showtimes found. Run seed or overlapping test first.\n");
}
$showtimeId = $showtime->id;
$price = $showtime->price;

// 2. Get a Seat (Room 1)
$db->query("SELECT id FROM seats WHERE room_id = 1 LIMIT 1");
$seat = $db->single();
if (!$seat)
    die("[FAIL] No seats found.\n");
$seatId = $seat->id;

// 3. User
$userId = 1;

echo "Attempting to book Seat $seatId for Showtime $showtimeId...\n";

// Simulate Transaction
try {
    $db->beginTransaction();

    // Create Order
    $db->query('INSERT INTO orders (user_id, showtime_id, total_amount, status) VALUES (:uid, :sid, :total, "paid")');
    $db->bind(':uid', $userId);
    $db->bind(':sid', $showtimeId);
    $db->bind(':total', $price);
    $db->execute();
    $orderId = $db->lastInsertId();

    // Create Ticket
    $ticketCode = 'TEST-' . uniqid();
    $db->query('INSERT INTO tickets (order_id, showtime_id, seat_id, ticket_code) VALUES (:oid, :sid, :seatid, :code)');
    $db->bind(':oid', $orderId);
    $db->bind(':sid', $showtimeId);
    $db->bind(':seatid', $seatId);
    $db->bind(':code', $ticketCode);
    $db->execute(); // This should work first time

    $db->endTransaction();
    echo "[PASS] Booking 1 successful.\n";

} catch (Exception $e) {
    $db->cancelTransaction();
    echo "[FAIL] Booking 1 failed: " . $e->getMessage() . "\n";
}

// 4. Test Double Booking (Should Fail)
echo "Attempting DOUBLE BOOKING for same seat...\n";
try {
    $db->beginTransaction();

    // Order
    $db->query('INSERT INTO orders (user_id, showtime_id, total_amount, status) VALUES (:uid, :sid, :total, "paid")');
    $db->bind(':uid', $userId);
    $db->bind(':sid', $showtimeId);
    $db->bind(':total', $price);
    $db->execute();
    $orderId = $db->lastInsertId();

    // Ticket (Same Seat!)
    $ticketCode = 'TEST-DOUBLE-' . uniqid();
    $db->query('INSERT INTO tickets (order_id, showtime_id, seat_id, ticket_code) VALUES (:oid, :sid, :seatid, :code)');
    $db->bind(':oid', $orderId);
    $db->bind(':sid', $showtimeId);
    $db->bind(':seatid', $seatId);
    $db->bind(':code', $ticketCode);
    $db->execute(); // Should throw exception due to Unique Constraint

    $db->endTransaction();
    echo "[FAIL] Double booking was ALLOWED (Critical Error).\n";

} catch (Exception $e) {
    $db->cancelTransaction();
    echo "[PASS] Double booking blocked correctly: " . $e->getMessage() . "\n";
}
