<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';

$db = Database::getInstance();

// 1. Check if user exists
$email = 'testuser@example.com';
$db->query("SELECT * FROM users WHERE email = :email");
$db->bind(':email', $email);
$user = $db->single();

if ($user) {
    // Promote
    $db->query("UPDATE users SET role = 'admin' WHERE email = :email");
    $db->bind(':email', $email);
    if ($db->execute())
        echo "User promoted to ADMIN.";
} else {
    // Create
    $pass = password_hash('123456', PASSWORD_DEFAULT);
    $db->query("INSERT INTO users (fullname, email, phone, password, role) VALUES ('Admin', :email, '0000000000', :pass, 'admin')");
    $db->bind(':email', $email);
    $db->bind(':pass', $pass);
    if ($db->execute())
        echo "Admin user CREATED.";
}

