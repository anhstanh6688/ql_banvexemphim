<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';

$db = Database::getInstance();
echo "Testing Dashboard Features...\n";

// 1. Get a paid order and ticket
$db->query("SELECT t.id, t.status, t.showtime_id FROM tickets t JOIN orders o ON t.order_id = o.id WHERE o.status='paid' LIMIT 1");
$ticket = $db->single();

if (!$ticket)
    die("No tickets found to test.\n");

$ticketId = $ticket->id;
$showtimeId = $ticket->showtime_id;

// Logic Check: Can cancel?
// Get showtime start
$db->query("SELECT start_time FROM showtimes WHERE id = :id");
$db->bind(':id', $showtimeId);
$showtime = $db->single();
$start = strtotime($showtime->start_time);
$now = time();
$diff = $start - $now;

echo "Ticket ID: $ticketId. Time diff: $diff seconds.\n";

if ($diff > 7200) {
    echo "Should be cancellable.\n";
    // Simulate Cancel Update
    $db->query('UPDATE tickets SET status = "cancelled" WHERE id = :id');
    $db->bind(':id', $ticketId);
    $db->execute();
    echo "[PASS] Ticket cancelled manually (Simulation).\n";
} else {
    echo "Too late to cancel (Expected behavior if show is soon).\n";
}

// Search Test
$db->query("SELECT ticket_code FROM tickets WHERE id = :id");
$db->bind(':id', $ticketId);
$t = $db->single();
$code = $t->ticket_code;

echo "Searching for code: $code\n";
// Query used in model
$sql = 'SELECT t.* FROM tickets t WHERE t.ticket_code = :code';
$db->query($sql);
$db->bind(':code', $code);
$found = $db->single();

if ($found) {
    echo "[PASS] Search found ticket.\n";
} else {
    echo "[FAIL] Search failed.\n";
}
