<?php
require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();
$today = date('Y-m-d');
echo "Today (PHP): $today\n";
$db->query("SELECT NOW() as db_now");
echo "Now (DB): " . $db->single()->db_now . "\n";

echo "\n--- Movies currently returned by getComingSoon() ---\n";
// This is the EXACT query from the model
$sql = "SELECT * FROM movies 
        WHERE release_date > :today 
        AND id NOT IN (
            SELECT DISTINCT movie_id FROM showtimes WHERE start_time >= NOW()
        )
        ORDER BY release_date ASC";

$db->query($sql);
$db->bind(':today', $today);
$movies = $db->resultSet();

if (empty($movies)) {
    echo "No movies found in Coming Soon.\n";
}

foreach ($movies as $m) {
    echo "Movie: [{$m->id}] {$m->title} (Release: {$m->release_date})\n";

    // VERIFICATION: Does this movie have active showtimes?
    $db->query("SELECT * FROM showtimes WHERE movie_id = :mid AND start_time >= NOW()");
    $db->bind(':mid', $m->id);
    $active_shows = $db->resultSet();

    if (count($active_shows) > 0) {
        echo "  [BUG DETECTED] This movie HAS active showtimes but passed NOT IN check!\n";
        foreach ($active_shows as $show) {
            echo "    -> Show: {$show->id} at {$show->start_time}\n";
        }
    } else {
        echo "  [OK] No active showtimes.\n";
    }
}
