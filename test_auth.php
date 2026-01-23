<?php
// Test Auth Logic via CLI
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/models/User.php';

echo "Testing User Model...\n";
$userModel = new User();

// 1. Register
$email = 'testuser' . time() . '@example.com';
$password = '123456';
$data = [
    'fullname' => 'Test User',
    'email' => $email,
    'phone' => '0123456789',
    'password' => password_hash($password, PASSWORD_DEFAULT),
    'role' => 'user'
];

echo "Registering user ($email)...\n";
if ($userModel->register($data)) {
    echo "[PASS] User registered.\n";
} else {
    echo "[FAIL] Registration failed.\n";
}

// 2. Login
echo "Logging in...\n";
$loggedInUser = $userModel->login($email, $password);
if ($loggedInUser) {
    echo "[PASS] Login successful. User ID: " . $loggedInUser->id . "\n";
    echo "Hash verification passed.\n";
} else {
    echo "[FAIL] Login failed.\n";
}

// 3. Duplicate Email Check
echo "Checking duplicate email...\n";
if ($userModel->findUserByEmail($email)) {
    echo "[PASS] Duplicate email detected correctly.\n";
} else {
    echo "[FAIL] Failed to detect duplicate email.\n";
}
