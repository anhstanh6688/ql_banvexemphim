<?php
require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

echo "Seeding Coming Soon movies...\n";

$descriptions = [
    "A mind-bending thriller about memory.",
    "The journey of a lifetime across the stars.",
    "A romantic comedy that defies expectations.",
    "Action-packed adventure in the jungle.",
    "Historical drama set in the 1800s."
];

$genres = ["Action", "Sci-Fi", "Drama", "Comedy", "Horror"];

for ($i = 1; $i <= 12; $i++) {
    $title = "Coming Soon Movie " . $i;
    $genre = $genres[array_rand($genres)];
    $desc = $descriptions[array_rand($descriptions)];
    $duration = rand(90, 150) . " min";
    // Release date 10-60 days in future
    $future_days = rand(10, 60);
    $release_date = date('Y-m-d', strtotime("+$future_days days"));

    // Poster placeholder
    $poster = "https://via.placeholder.com/300x450.png?text=Movie+$i";

    $db->query('INSERT INTO movies (title, genre, duration, description, release_date, poster) VALUES(:title, :genre, :duration, :description, :release_date, :poster)');
    $db->bind(':title', $title);
    $db->bind(':genre', $genre);
    $db->bind(':duration', $duration);
    $db->bind(':description', $desc);
    $db->bind(':release_date', $release_date);
    $db->bind(':poster', $poster);

    if ($db->execute()) {
        echo "Created: $title ($release_date)\n";
    } else {
        echo "Failed to create: $title\n";
    }
}

echo "Done.\n";
