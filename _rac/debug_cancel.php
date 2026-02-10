<?php
require_once 'config/config.php';
require_once 'core/Database.php';

// Mock Session
$_SESSION['user_id'] = 1; // Assuming admin/user ID 1

$db = Database::getInstance();
$ticketId = 52; // From screenshot context

// 1. Check Timezone
echo "Current Timezone: " . date_default_timezone_get() . "\n";
echo "Current Time: " . date('Y-m-d H:i:s') . "\n";
echo "Current Timestamp: " . time() . "\n";

// 2. Fetch Ticket
$db->query('SELECT t.*, o.user_id, s.start_time 
            FROM tickets t
            JOIN orders o ON t.order_id = o.id
            JOIN showtimes s ON t.showtime_id = s.id
            WHERE t.id = :id');
$db->bind(':id', $ticketId);
$ticket = $db->single();

if (!$ticket) {
    echo "Ticket $ticketId not found.\n";
    exit;
}

echo "Ticket Found. Status: " . $ticket->status . "\n";
echo "Showtime: " . $ticket->start_time . "\n";
echo "User ID: " . $ticket->user_id . "\n";

// 3. Check Logic
$showtime_ts = strtotime($ticket->start_time);
$now = time();
$diff = $showtime_ts - $now;

echo "Showtime TS: $showtime_ts\n";
echo "Now TS: $now\n";
echo "Diff: $diff seconds (" . ($diff / 3600) . " hours)\n";

if ($diff < 7200) {
    echo "FAIL: Too close to showtime.\n";
} else {
    echo "PASS: Can cancel.\n";
}
