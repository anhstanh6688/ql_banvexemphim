<?php
class Showtimes extends Controller
{
    private $showtimeModel;
    private $movieModel;
    private $roomModel;

    public function __construct()
    {
        require_once '../core/middleware.php';
        if (!isLoggedIn())
            redirect('auth/login');
        requireAdmin();

        $this->showtimeModel = $this->model('Showtime');
        $this->movieModel = $this->model('Movie');
        $this->roomModel = $this->model('Room');
    }

    public function index()
    {
        // Admin view to list all showtimes
        $limit = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1)
            $page = 1;

        $total_showtimes = $this->showtimeModel->getAllShowtimeCount();
        $total_pages = ceil($total_showtimes / $limit);
        $offset = ($page - 1) * $limit;

        $showtimes = $this->showtimeModel->getAllShowtimesPaginated($limit, $offset);

        $data = [
            'showtimes' => $showtimes,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_showtimes' => $total_showtimes
        ];
        $this->view('showtimes/index', $data);
    }

    public function add($movieId = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            validate_csrf();

            $data = [
                'movie_id' => trim($_POST['movie_id']),
                'room_id' => trim($_POST['room_id']),
                'start_time' => trim($_POST['start_time']),
                'price' => trim($_POST['price']),
                'error' => ''
            ];

            // Get Movie Duration
            $movie = $this->movieModel->getMovieById($data['movie_id']);
            if (!$movie)
                die('Invalid Movie');

            // Validate
            if (empty($data['start_time']))
                $data['error'] = 'Start time required';
            if (empty($data['price']))
                $data['error'] = 'Price required';

            // Check Overlap
            if (empty($data['error'])) {
                if ($this->showtimeModel->checkOverlap($data['room_id'], $data['start_time'], $movie->duration)) {
                    $data['error'] = 'Schedule conflict! This room is occupied during this time (including 15m buffer).';
                }
            }

            if (empty($data['error'])) {
                // Add with duration passed for calculation
                $data['duration'] = $movie->duration;
                if ($this->showtimeModel->add($data)) {
                    flash('showtime_message', 'Showtime Added');
                    redirect('showtimes');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Reload with error
                // Fetch all movies/rooms again in case of error
                $movies = $this->movieModel->getMovies();
                $rooms = $this->roomModel->getRooms();

                $data['movies'] = $movies;
                $data['rooms'] = $rooms;
                $this->view('showtimes/add', $data);
            }

        } else {
            // Get all movies and rooms for dropdowns
            $movies = $this->movieModel->getMovies();
            $rooms = $this->roomModel->getRooms();

            $data = [
                'movies' => $movies,
                'rooms' => $rooms,
                'movie_id' => $movieId,
                'room_id' => '',
                'start_time' => '',
                'price' => '',
                'error' => ''
            ];
            $this->view('showtimes/add', $data);
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            validate_csrf();

            $data = [
                'id' => $id,
                'movie_id' => trim($_POST['movie_id']),
                'room_id' => trim($_POST['room_id']),
                'start_time' => trim($_POST['start_time']),
                'price' => trim($_POST['price']),
                'error' => ''
            ];

            // Get Movie Duration
            $movie = $this->movieModel->getMovieById($data['movie_id']);
            if (!$movie)
                die('Invalid Movie');

            // Validate
            if (empty($data['start_time']))
                $data['error'] = 'Start time required';
            if (empty($data['price']))
                $data['error'] = 'Price required';

            // Check Overlap
            if (empty($data['error'])) {
                if ($this->showtimeModel->checkUpdateOverlap($id, $data['room_id'], $data['start_time'], $movie->duration)) {
                    $data['error'] = 'Schedule conflict! This room is occupied during this time.';
                }
            }

            if (empty($data['error'])) {
                $data['duration'] = $movie->duration; // Pass duration for end_time calc
                if ($this->showtimeModel->update($data)) {
                    flash('showtime_message', 'Showtime Updated');
                    redirect('showtimes');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Reload with error
                $movies = $this->movieModel->getMovies();
                $rooms = $this->roomModel->getRooms();

                // Get showtime details again to preserve original context if needed
                // But form values are in $data
                $showtime = $this->showtimeModel->getShowtimeById($id);

                $data['movies'] = $movies;
                $data['rooms'] = $rooms;
                $data['showtime'] = $showtime; // Pass object for view compatibility if needed, though form uses $data

                $this->view('showtimes/edit', $data);
            }

        } else {
            // Get Showtime to Edit
            $showtime = $this->showtimeModel->getShowtimeById($id);
            if (!$showtime) {
                redirect('showtimes');
            }

            $movies = $this->movieModel->getMovies();
            $rooms = $this->roomModel->getRooms();

            $data = [
                'id' => $id,
                'movie_id' => $showtime->movie_id,
                'room_id' => $showtime->room_id,
                // Format start_time for datetime-local input (Y-m-d\TH:i)
                'start_time' => date('Y-m-d\TH:i', strtotime($showtime->start_time)),
                'price' => $showtime->price,
                'movies' => $movies,
                'rooms' => $rooms,
                'showtime' => $showtime,
                'error' => ''
            ];
            $this->view('showtimes/edit', $data);
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->showtimeModel->delete($id)) {
                flash('showtime_message', 'Showtime Removed');
                redirect('showtimes');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('showtimes');
        }
    }
}
