<?php
require_once __DIR__ . '/config/config.php';

try {
    // Connect without DB name first to create it
    $dsn = 'mysql:host=' . DB_HOST . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to MySQL server.\n";

    // Read SQL file
    $sql = file_get_contents(__DIR__ . '/database.sql');

    // Execute SQL
    $pdo->exec($sql);

    echo "Database imported successfully.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
