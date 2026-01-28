<?php
// Load Config and Core
require_once '../config/config.php';

// Autoload Core Libraries
spl_autoload_register(function ($className) {
    if (file_exists('../core/' . $className . '.php')) {
        require_once '../core/' . $className . '.php';
    } elseif (file_exists('../app/libraries/' . $className . '.php')) {
        require_once '../app/libraries/' . $className . '.php';
    } elseif (file_exists('../libraries/' . $className . '.php')) {
        require_once '../libraries/' . $className . '.php';
    }
});

$db = Database::getInstance();

// Find Room C
$db->query("SELECT * FROM rooms WHERE name = 'Room C'");
$room = $db->single();

if (!$room) {
    echo "Room C not found.\n";
    exit;
}

echo "Room C ID: " . $room->id . "\n";
echo "Dimensions: " . $room->total_rows . "x" . $room->total_cols . "\n";

// Count Seats by Status
$db->query("SELECT status, COUNT(*) as count FROM seats WHERE room_id = :rid GROUP BY status");
$db->bind(':rid', $room->id);
$counts = $db->resultSet();

echo "Seat Statuses:\n";
foreach ($counts as $c) {
    echo " - " . $c->status . ": " . $c->count . "\n";
}

// Check Tickets for a showtime in this room?
// User screenshot 2 says "Fri, 01 May", time "00:10".
// Need to find showtime ID.
$db->query("SELECT * FROM showtimes WHERE room_id = :rid AND start_time LIKE '%00:10%'"); // Approximate check
$db->bind(':rid', $room->id);
$shows = $db->resultSet();

foreach ($shows as $s) {
    echo "Showtime ID: " . $s->id . " | Start: " . $s->start_time . "\n";

    // Count Tickets
    $db->query("SELECT status, COUNT(*) as count FROM tickets WHERE showtime_id = :sid GROUP BY status");
    $db->bind(':sid', $s->id);
    $t_counts = $db->resultSet();
    echo "  Tickets:\n";
    foreach ($t_counts as $tc) {
        echo "   - " . $tc->status . ": " . $tc->count . "\n";
    }
}
