<?php
// Manual bootstrap
require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();
$db->query("SELECT * FROM coupons WHERE status = 'active'");
try {
    $coupons = $db->resultSet();
    echo "Count: " . count($coupons) . "\n";
    print_r($coupons);

    if (count($coupons) == 0) {
        echo "No coupons found. Inserting default coupons...\n";
        $db->query("INSERT INTO coupons (code, discount_type, discount_value, description, status) VALUES 
            ('WELCOME50', 'percent', 50, 'Get 50% off for new users', 'active'),
            ('SALE100', 'fixed', 100000, 'Get 100k off for orders over 200k', 'active'),
            ('SUMMER2026', 'percent', 20, 'Hot Summer Sale', 'active')
        ");
        $db->execute();
        echo "Inserted default coupons.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
