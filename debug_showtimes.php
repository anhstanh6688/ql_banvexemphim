<?php
require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();
$date = '2026-01-24';
$movieId = 1;

echo "Querying showtimes for Movie ID $movieId on $date...\n";

$db->query("SELECT id, start_time, room_id FROM showtimes WHERE movie_id = :mid AND DATE(start_time) = :date");
$db->bind(':mid', $movieId);
$db->bind(':date', $date);
$results = $db->resultSet();

echo "Found " . count($results) . " showtimes:\n";
foreach ($results as $s) {
    echo "- ID: {$s->id} | Time: {$s->start_time} | Room: {$s->room_id}\n";
}
