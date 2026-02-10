<?php
require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();
$ticketId = 52;
$orderId = 24;

echo "--- User Info ---\n";
$db->query("SELECT * FROM users WHERE id IN (1, 2)");
$users = $db->resultSet();
foreach ($users as $u) {
    echo "ID: $u->id, Name: $u->fullname, Role: $u->role\n";
}

echo "\n--- Order Info ---\n";
$db->query("SELECT * FROM orders WHERE id = :id");
$db->bind(':id', $orderId);
$order = $db->single();
if ($order) {
    echo "Order $orderId User ID: $order->user_id\n";
} else {
    echo "Order not found.\n";
}

echo "\n--- Cancellation Test ---\n";
// Attempt to update status manually to see if it works
$db->query('UPDATE tickets SET status = "cancelled" WHERE id = :id');
$db->bind(':id', $ticketId);
if ($db->execute()) {
    echo "Update query executed successfully.\n";
} else {
    echo "Update query FAILED.\n";
}

// Check status again
$db->query("SELECT status FROM tickets WHERE id = :id");
$db->bind(':id', $ticketId);
$row = $db->single();
echo "New Status: " . $row->status . "\n";

// Revert it back so we can test via browser if we want, or leave it if we fixed it
// But wait, if I change it here, I confirm DB works.
// Let's revert it to valid to allow browser testing.
$db->query('UPDATE tickets SET status = "valid" WHERE id = :id');
$db->bind(':id', $ticketId);
$db->execute();
echo "Reverted status to valid for verification.\n";
