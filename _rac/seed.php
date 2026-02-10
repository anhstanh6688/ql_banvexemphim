<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';

$db = Database::getInstance();

// 1. Promote a user to admin (or create one)
$email = 'admin@example.com';
$password = password_hash('123456', PASSWORD_DEFAULT);
$name = 'System Admin';

// Check if exists
$db->query("SELECT * FROM users WHERE email = :email");
$db->bind(':email', $email);
$existing = $db->single();

if (!$existing) {
    $db->query("INSERT INTO users (fullname, email, phone, password, role) VALUES (:name, :email, '0000000000', :pass, 'admin')");
    $db->bind(':name', $name);
    $db->bind(':email', $email);
    $db->bind(':pass', $password);
    $db->execute();
    echo "Admin user created (email: admin@example.com, pass: 123456)\n";
} else {
    // Force role update just in case
    $db->query("UPDATE users SET role = 'admin' WHERE email = :email");
    $db->bind(':email', $email);
    $db->execute();
    echo "Admin user ensured.\n";
}

// 2. Seed Rooms
$rooms = [
    ['name' => 'Room A', 'rows' => 10, 'cols' => 10],
    ['name' => 'Room B', 'rows' => 8, 'cols' => 8],
    ['name' => 'VIP Room', 'rows' => 5, 'cols' => 6]
];

foreach ($rooms as $r) {
    $db->query("SELECT * FROM rooms WHERE name = :name");
    $db->bind(':name', $r['name']);
    if (!$db->single()) {
        $db->query("INSERT INTO rooms (name, total_rows, total_cols) VALUES (:name, :r, :c)");
        $db->bind(':name', $r['name']);
        $db->bind(':r', $r['rows']);
        $db->bind(':c', $r['cols']);
        $db->execute();
        $roomId = $db->lastInsertId();

        // Generate Seats for this room
        // A1, A2... B1, B2...
        $rowsABC = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($i = 0; $i < $r['rows']; $i++) {
            for ($j = 1; $j <= $r['cols']; $j++) {
                $rowChar = $rowsABC[$i]; // A, B, C...
                $seatCode = $rowChar . $j; // A1, A2...
                $db->query("INSERT INTO seats (room_id, seat_code) VALUES (:rid, :code)");
                $db->bind(':rid', $roomId);
                $db->bind(':code', $seatCode);
                $db->execute();
            }
        }
        echo "Created " . $r['name'] . " with seats.\n";
    }
}
echo "Seeding completed.\n";
