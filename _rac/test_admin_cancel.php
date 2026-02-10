require_once 'config/config.php';
require_once 'core/Database.php';
require_once 'models/Ticket.php';

$db = Database::getInstance();
$ticketModel = new Ticket();

echo "1. Checking database column...\n";
try {
$db->query("SELECT cancellation_reason FROM tickets LIMIT 1");
$db->execute();
echo "PASS: 'cancellation_reason' column exists.\n";
} catch (Exception $e) {
echo "FAIL: 'cancellation_reason' column does not exist.\n";
exit;
}

echo "\n2. Simulating Admin Cancellation...\n";
// Find a valid ticket to cancel
$db->query("SELECT id FROM tickets WHERE status = 'valid' LIMIT 1");
$ticket = $db->single();

if ($ticket) {
$ticketId = $ticket->id;
$reason = "Test cancellation by Admin script at " . date('H:i:s');

echo "Cancelling ticket ID: $ticketId with reason: '$reason'\n";

if ($ticketModel->cancel($ticketId, $reason)) {
echo "Ticket cancel method returned true.\n";

// Verify in DB
$db->query("SELECT status, cancellation_reason FROM tickets WHERE id = :id");
$db->bind(':id', $ticketId);
$updatedTicket = $db->single();

if ($updatedTicket->status === 'cancelled' && $updatedTicket->cancellation_reason === $reason) {
echo "PASS: SQL Update successful. Status is 'cancelled' and reason is correct.\n";
} else {
echo "FAIL: DB verification failed. Status: {$updatedTicket->status}, Reason: {$updatedTicket->cancellation_reason}\n";
}
} else {
echo "FAIL: Method cancel() returned false.\n";
}
} else {
echo "SKIP: No valid tickets found to test.\n";
}