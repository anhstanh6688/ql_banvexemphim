<?php
// Load Config
require_once 'config/config.php';
// Load Database Library
require_once 'core/Database.php';

$db = Database::getInstance();

echo "Updating database schema for Coupons and VietQR...\n";

// 1. Create Coupons Table
$sql_create_coupons = "CREATE TABLE IF NOT EXISTS coupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    discount_type ENUM('percent', 'fixed') NOT NULL,
    discount_value DECIMAL(10, 2) NOT NULL,
    status ENUM('active', 'expired') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$db->query($sql_create_coupons);
if ($db->execute()) {
    echo "Table 'coupons' created or already exists.\n";
} else {
    echo "Error creating 'coupons' table.\n";
}

// 2. Insert Sample Data
$sql_insert_coupons = "INSERT IGNORE INTO coupons (code, discount_type, discount_value, status) VALUES
('SALE100', 'percent', 100.00, 'active'),
('CHAO2026', 'fixed', 50000.00, 'active')";
$db->query($sql_insert_coupons);
if ($db->execute()) {
    echo "Sample coupons inserted.\n";
}

// 3. Update Orders Table (Columns)
$alter_commands = [
    "ADD COLUMN coupon_code VARCHAR(50) NULL AFTER showtime_id",
    "ADD COLUMN original_amount DECIMAL(10, 2) NOT NULL DEFAULT 0 AFTER total_amount",
    "ADD COLUMN final_amount DECIMAL(10, 2) NOT NULL DEFAULT 0 AFTER original_amount",
    "MODIFY COLUMN payment_method ENUM('online_mock', 'vietqr', 'free') DEFAULT 'online_mock'"
];

foreach ($alter_commands as $cmd) {
    try {
        $db->query("ALTER TABLE orders " . $cmd);
        $db->execute();
        echo "Executed: ALTER TABLE orders $cmd\n";
    } catch (PDOException $e) {
        // Ignore "Duplicate column name" error
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "Column already exists (Skipped): $cmd\n";
        } else {
            echo "Error executing: $cmd - " . $e->getMessage() . "\n";
        }
    }
}

echo "Database update completed.\n";
