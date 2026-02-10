<?php
require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

echo "Adding 'cancellation_reason' column...\n";
try {
    $db->query("ALTER TABLE tickets ADD COLUMN cancellation_reason TEXT NULL AFTER status");
    $db->execute();
    echo "SUCCESS: Column added.\n";
} catch (Exception $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "SKIPPED: Column already exists.\n";
    } else {
        echo "ERROR: " . $e->getMessage() . "\n";
    }
}