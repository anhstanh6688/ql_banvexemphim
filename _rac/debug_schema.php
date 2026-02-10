<?php
require_once 'core/config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

echo "Checking columns for 'tickets' table:\n";
try {
    $db->query("DESCRIBE tickets");
    $columns = $db->resultSet();
    foreach ($columns as $col) {
        echo "- " . $col->Field . " (" . $col->Type . ")\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
