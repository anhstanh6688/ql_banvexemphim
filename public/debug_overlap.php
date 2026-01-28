<?php
// Load Config and Core
require_once '../app/config/config.php';
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
echo "Room C ID: " . $room->id . "\n";

// Find Locked Seat IDs
$db->query("SELECT id, seat_code FROM seats WHERE room_id = :rid AND status = 'locked'");
$db->bind(':rid', $room->id);
$lockedSeats = $db->resultSet();
$lockedIds = [];
foreach ($lockedSeats as $s) {
    $lockedIds[] = $s->id;
}
echo "Locked Seats Count: " . count($lockedIds) . "\n";

// Find Showtime 44 (or approximate)
$db->query("SELECT * FROM showtimes WHERE room_id = :rid AND start_time LIKE '%00:10%'");
$db->bind(':rid', $room->id);
$shows = $db->resultSet();

foreach ($shows as $s) {
    echo "Showtime ID: " . $s->id . "\n";

    // Get Valid Tickets
    $db->query("SELECT * FROM tickets WHERE showtime_id = :sid AND status = 'valid'");
    $db->bind(':sid', $s->id);
    $tickets = $db->resultSet();
    echo "Total Valid Tickets: " . count($tickets) . "\n";

    $overlap = 0;
    foreach ($tickets as $t) {
        if (in_array($t->seat_id, $lockedIds)) {
            $overlap++;
        }
    }
    echo "Tickets on Locked Seats (Overlap): " . $overlap . "\n";

    // Net Calculation Prediction
    $total = 48; // Assumed from rows*cols
    $locked = count($lockedIds);
    $bookedOnAvailable = count($tickets) - $overlap;

    $avail = $total - $locked - $bookedOnAvailable;
    echo "Predicted Available (Total - Locked - TicketsOnAvailable): " . $avail . "\n";
    echo "Current Logic (Total - Tickets - Locked): " . ($total - count($tickets) - $locked) . "\n";
}
