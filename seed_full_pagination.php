<?php
require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

echo "Seeding additional movies...\n";

// 1. Seed Stopped Showing (Ended)
// Need > 8 movies with release_date < today AND no future showtimes
for ($i = 1; $i <= 10; $i++) {
    $title = "Ended Movie " . $i;
    $release_date = date('Y-m-d', strtotime("-60 days"));
    $poster = "https://via.placeholder.com/300x450.png?text=Ended+$i";

    $db->query('INSERT INTO movies (title, genre, duration, description, release_date, poster) VALUES(:title, "Drama", "100 min", "Old movie", :release_date, :poster)');
    $db->bind(':title', $title);
    $db->bind(':release_date', $release_date);
    $db->bind(':poster', $poster);
    $db->execute();
}
echo "Seeded Ended movies.\n";

// 2. Seed Now Showing
// Need > 8 showtimes. We likely have some, but let's ensure we have enough independent movies designated as Now Showing (via showtimes)
// We need movies released < today AND having showtimes >= NOW
for ($i = 1; $i <= 10; $i++) {
    $title = "Now Showing Movie " . $i;
    $release_date = date('Y-m-d', strtotime("-5 days"));
    $poster = "https://via.placeholder.com/300x450.png?text=Now+$i";

    $db->query('INSERT INTO movies (title, genre, duration, description, release_date, poster) VALUES(:title, "Action", "120 min", "Current movie", :release_date, :poster)');
    $db->bind(':title', $title);
    $db->bind(':release_date', $release_date);
    $db->bind(':poster', $poster);
    $db->execute();
    $movieId = $db->lastInsertId();

    // Create Showtime
    $db->query('INSERT INTO showtimes (movie_id, room_id, start_time, price) VALUES(:mid, 1, :start, 100000)');
    $db->bind(':mid', $movieId);
    $db->bind(':start', date('Y-m-d H:i:s', strtotime("+1 day"))); // Future showtime
    $db->execute();
}
echo "Seeded Now Showing movies.\n";
