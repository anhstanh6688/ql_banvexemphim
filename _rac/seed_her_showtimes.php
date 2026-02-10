<?php
require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

// 1. Find Movie "Her"
$db->query("SELECT id FROM movies WHERE title LIKE '%Her%' LIMIT 1");
$movie = $db->single();

if (!$movie) {
    die("Movie 'Her' not found. Please create it first.\n");
}
$movieId = $movie->id;
echo "Found Movie 'Her' (ID: $movieId)\n";

// 2. Find Room
$db->query("SELECT id FROM rooms LIMIT 1");
$room = $db->single();
if (!$room) {
    die("No rooms found.\n");
}
$roomId = $room->id;

// 3. Create 5 Showtimes for Tomorrow
$date = date('Y-m-d', strtotime('+1 day'));
$times = ['10:00:00', '13:00:00', '16:00:00', '19:00:00', '22:00:00'];

echo "Seeding showtimes for $date...\n";

foreach ($times as $time) {
    $start = "$date $time";
    // Check if exists
    $db->query("SELECT id FROM showtimes WHERE movie_id = :mid AND start_time = :start");
    $db->bind(':mid', $movieId);
    $db->bind(':start', $start);
    if ($db->single()) {
        echo "Showtime at $start already exists.\n";
        continue;
    }

    $db->query("INSERT INTO showtimes (movie_id, room_id, start_time, price) VALUES (:mid, :rid, :start, 50000)");
    $db->bind(':mid', $movieId);
    $db->bind(':rid', $roomId);
    $db->bind(':start', $start);
    $db->execute();
    echo "Created showtime at $start\n";
}

echo "Done.\n";
