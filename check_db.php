<?php
require_once 'config/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();
$movieId = 1;

$db->query("SELECT id, start_time, movie_id, room_id FROM showtimes WHERE movie_id = 1");
$showtimes = $db->resultSet();

$output = "Current PHP Time: " . date('Y-m-d H:i:s') . "\n";
$db->query("SELECT NOW() as db_time");
$output .= "Current DB Time: " . $db->single()->db_time . "\n";
$output .= "Showtimes for Movie 1:\n";
foreach ($showtimes as $s) {
    $output .= "ID: {$s->id} | Start: {$s->start_time}\n";
}

file_put_contents('db_dump.txt', $output);
echo "Dumped to db_dump.txt";
