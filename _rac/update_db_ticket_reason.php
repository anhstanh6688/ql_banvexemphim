<?php
require_once 'core/config/config.php';
require_once 'core/classes/Database.php';

$db = new Database();

// Add cancellation_reason column
try {
    $sql = "ALTER TABLE tickets ADD COLUMN cancellation_reason TEXT NULL AFTER status";
    $db->query($sql);
    $db->execute();
    echo "Successfully added 'cancellation_reason' column to 'tickets' table.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "Column 'cancellation_reason' already exists.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
