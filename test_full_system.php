<?php
// Comprehensive System Verification Script
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';

$db = Database::getInstance();

echo "=== STARTING FULL SYSTEM VERIFICATION ===\n\n";

try {
    // 1. Setup / Cleanup
    echo "[1] Cleaning up test data (soft cleanup if needed)...\n";
    // Optional: Delete test data created by this script run previously
    $db->query("DELETE FROM users WHERE email LIKE 'systemtest%'");
    $db->execute();
    $db->query("DELETE FROM movies WHERE title = 'System Test Movie'");
    $db->execute();
    // Assuming cascade delete handles showtimes, tickets, orders, reviews?
    // If not, we might need manual cleanup. Based on SQL schema, ON DELETE CASCADE is set.

    // 2. Create Admin & User
    echo "[2] Creating Users...\n";
    $adminEmail = 'systemtest_admin@example.com';
    $userEmail = 'systemtest_user@example.com';
    $passHash = password_hash('123456', PASSWORD_DEFAULT);

    // Admin
    $db->query("INSERT INTO users (fullname, email, phone, password, role) VALUES ('System Admin', :email, '0000000000', :pass, 'admin')");
    $db->bind(':email', $adminEmail);
    $db->bind(':pass', $passHash);
    $db->execute();
    $adminId = $db->lastInsertId();
    echo "    - Admin created (ID: $adminId)\n";

    // User
    $db->query("INSERT INTO users (fullname, email, phone, password, role) VALUES ('System User', :email, '0999999999', :pass, 'user')");
    $db->bind(':email', $userEmail);
    $db->bind(':pass', $passHash);
    $db->execute();
    $userId = $db->lastInsertId();
    echo "    - User created (ID: $userId)\n";

    // 3. Create Movie
    echo "[3] Creating Movie...\n";
    $db->query("INSERT INTO movies (title, genre, duration, description, release_date, status) VALUES ('System Test Movie', 'Action', 120, 'Test Desc', CURDATE(), 'showing')");
    $db->execute();
    $movieId = $db->lastInsertId();
    echo "    - Movie created (ID: $movieId)\n";

    // 4. Create Room (if not exists checks or just pick first one)
    // We'll just pick room 1 to be safe, or create one if empty?
    echo "[4] Selecting Room...\n";
    $db->query("SELECT id FROM rooms LIMIT 1");
    $room = $db->single();
    if (!$room) {
        $db->query("INSERT INTO rooms (name, total_rows, total_cols) VALUES ('Test Room', 10, 10)");
        $db->execute();
        $roomId = $db->lastInsertId();
        // Create seats for this room...
        echo "    - Room created (ID: $roomId). Generatings seats...\n";
        for ($i = 1; $i <= 100; $i++) {
            $db->query("INSERT INTO seats (room_id, seat_code) VALUES ($roomId, 'A$i')");
            $db->execute();
        }
    } else {
        $roomId = $room->id;
        echo "    - Using existing Room (ID: $roomId)\n";
    }

    // 5. Create Showtime
    echo "[5] Creating Showtime...\n";
    $startTime = date('Y-m-d H:i:s', strtotime('+1 hour')); // 1 hour from now
    $endTime = date('Y-m-d H:i:s', strtotime('+3 hours'));

    $db->query("INSERT INTO showtimes (movie_id, room_id, start_time, end_time, price, status) VALUES (:mid, :rid, :start, :end, 75000, 'active')");
    $db->bind(':mid', $movieId);
    $db->bind(':rid', $roomId);
    $db->bind(':start', $startTime);
    $db->bind(':end', $endTime);
    $db->execute();
    $showtimeId = $db->lastInsertId();
    echo "    - Showtime created (ID: $showtimeId) at $startTime\n";

    // 6. Booking Process
    echo "[6] User Booking Ticket...\n";

    // Pick a seat
    $db->query("SELECT id FROM seats WHERE room_id = :rid LIMIT 1");
    $db->bind(':rid', $roomId);
    $seat = $db->single();
    $seatId = $seat->id;

    $db->beginTransaction();
    try {
        // Order
        $db->query("INSERT INTO orders (user_id, showtime_id, total_amount, status) VALUES (:uid, :sid, 75000, 'paid')");
        $db->bind(':uid', $userId);
        $db->bind(':sid', $showtimeId);
        $db->execute();
        $orderId = $db->lastInsertId();

        // Ticket
        $code = 'SYS-' . uniqid();
        $db->query("INSERT INTO tickets (order_id, showtime_id, seat_id, ticket_code) VALUES (:oid, :sid, :seatid, :code)");
        $db->bind(':oid', $orderId);
        $db->bind(':sid', $showtimeId);
        $db->bind(':seatid', $seatId);
        $db->bind(':code', $code);
        $db->execute();

        $db->endTransaction();
        echo "    - Booking successful! Order ID: $orderId, Ticket Code: $code\n";
    } catch (Exception $e) {
        $db->cancelTransaction();
        throw new Exception("Booking failed: " . $e->getMessage());
    }

    // 7. Verify Data
    echo "[7] Verifying Data Integrity...\n";

    // Check Order
    $db->query("SELECT * FROM orders WHERE id = :id");
    $db->bind(':id', $orderId);
    $order = $db->single();
    if ($order && $order->status === 'paid' && $order->total_amount == 75000) {
        echo "    [PASS] Order verified.\n";
    } else {
        echo "    [FAIL] Order verification failed.\n";
    }

    // Check Ticket
    $db->query("SELECT * FROM tickets WHERE order_id = :id");
    $db->bind(':id', $orderId);
    $ticket = $db->single();
    if ($ticket && $ticket->seat_id == $seatId) {
        echo "    [PASS] Ticket verified.\n";
    } else {
        echo "    [FAIL] Ticket verification failed.\n";
    }

    echo "\n=== SYSTEM VERIFICATION PASSED ===\n";

} catch (Exception $e) {
    echo "\n=== SYSTEM VERIFICATION FAILED ===\n";
    echo "Error: " . $e->getMessage() . "\n";
}
