<?php
// Manual test script for CLI
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';

try {
    $db = Database::getInstance();
    echo "Database connection successful!\n";

    // Test a query (users table should be empty but exist)
    $db->query("SELECT count(*) as count FROM users");
    $res = $db->single();
    echo "Users count check: " . $res->count . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
