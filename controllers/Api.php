<?php
class Api extends Controller
{
    private $movieModel;
    private $showtimeModel;

    public function __construct()
    {
        // No auth required for public API
        $this->movieModel = $this->model('Movie');
        $this->showtimeModel = $this->model('Showtime');
    }

    public function movies()
    {
        // Set JSON header
        header('Content-Type: application/json');

        $payload = [];

        // 1. Get "Now Showing" (Has active showtimes)
        // Use a high limit to get all current movies
        $nowShowing = $this->movieModel->getMoviesFilteredPaginated(['status' => 'now_showing'], 50);
        foreach ($nowShowing as $movie) {
            $payload[] = [
                'id' => $movie->id,
                'title' => $movie->title,
                'poster' => $movie->poster,
                'status' => 'showing'
            ];
        }

        // 2. Get "Coming Soon"
        $comingSoon = $this->movieModel->getMoviesFilteredPaginated(['status' => 'coming_soon'], 50);
        foreach ($comingSoon as $movie) {
            $payload[] = [
                'id' => $movie->id,
                'title' => $movie->title,
                'poster' => $movie->poster,
                'status' => 'coming_soon'
            ];
        }

        echo json_encode(['success' => true, 'data' => $payload]);
    }
}
