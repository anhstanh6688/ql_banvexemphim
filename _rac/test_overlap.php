<?php
// Test Overlap Logic via CLI
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/models/Showtime.php';

$showtimeModel = new Showtime();
$db = Database::getInstance();

echo "Testing Overlap Logic...\n";

// Clear Showtimes
$db->query("DELETE FROM showtimes");
$db->execute();

// Setup: 
// Room ID 1
// Movie Duration 120 mins
$roomId = 1;
$duration = 120;

// Add First Show: 10:00 -> 12:00 (occupies until 12:15)
// Insert manually or via model? Let's use raw insert to be sure of data state, 
// OR use model if we trust add(). Let's use model add partial equivalent logic manually for control.
$start1 = '2026-05-01 10:00:00';
$end1 = '2026-05-01 12:00:00';
$db->query("INSERT INTO showtimes (movie_id, room_id, start_time, end_time, price, status) VALUES(1, :rid, :start, :end, 50000, 'active')");
$db->bind(':rid', $roomId);
$db->bind(':start', $start1);
$db->bind(':end', $end1);
// Ensure movies table has id 1
try {
    $db->execute();
    echo "Inserted Show 1: $start1 -> $end1 (Buffer until 12:15)\n";
} catch (Exception $e) {
    // If movie 1 doesn't exist, insert it for test
    $db->query("INSERT INTO movies (id, title, duration) VALUES (1, 'Test Movie', 120)");
    $db->execute();
    // Retry
    $db->query("INSERT INTO showtimes (movie_id, room_id, start_time, end_time, price, status) VALUES(1, :rid, :start, :end, 50000, 'active')");
    $db->bind(':rid', $roomId);
    $db->bind(':start', $start1);
    $db->bind(':end', $end1);
    $db->execute();
    echo "Inserted Show 1 (After fix): $start1 -> $end1\n";
}

// Case A: New Show starts at 12:10 (Overlap)
// New Show Duration 120
echo "Case A: 12:10 Start (Should FAIL)...\n";
if ($showtimeModel->checkOverlap($roomId, '2026-05-01 12:10:00', 120)) {
    echo "[PASS] Detected overlap correctly.\n";
} else {
    echo "[FAIL] Failed to detect overlap.\n";
}

// Case B: New Show starts at 12:15 (Boundary Condition - Exact match? Buffer is 15 mins)
// 12:00 + 15 = 12:15.
// Formula: NewStart < ExistingEnd + 15. 
// 12:15 < 12:15 is FALSE. So 12:15 should be OK.
echo "Case B: 12:15 Start (Should PASS)...\n";
if (!$showtimeModel->checkOverlap($roomId, '2026-05-01 12:15:00', 120)) {
    echo "[PASS] Allowed valid slot.\n";
} else {
    echo "[FAIL] False positive on valid slot.\n";
}

// Case C: New Show starts at 08:00, ends at 10:10 (Overlap with start of Show 1)
// Duration 130 mins -> Ends 10:10. Buffer -> 10:25.
// Show 1 starts 10:00.
// NewEnd+15 = 10:25 > 10:00 (TRUE). AND NewStart 08:00 < 12:15 (TRUE). -> OVERLAP.
echo "Case C: 08:00 Start, Ends 10:10 (Should FAIL)...\n";
if ($showtimeModel->checkOverlap($roomId, '2026-05-01 08:00:00', 130)) {
    echo "[PASS] Detected overlap correctly.\n";
} else {
    echo "[FAIL] Failed to detect overlap.\n";
}
