<?php
class Movies extends Controller
{
    private $movieModel;

    public function __construct()
    {
        // Enforce Admin Access
        require_once '../core/middleware.php'; // Ensure middleware is loaded
        if (!isLoggedIn()) {
            redirect('auth/login');
        }
        // Assuming we want only admins to manage movies
        // For now, let's strictly require admin
        requireAdmin();

        $this->movieModel = $this->model('Movie');
    }

    public function index()
    {
        // Pagination vars
        $limit = 6; // Set limit per page
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1)
            $page = 1;

        $total_movies = $this->movieModel->getMovieCount();
        $total_pages = ceil($total_movies / $limit);
        $offset = ($page - 1) * $limit;

        // Fetch paginated
        $movies = $this->movieModel->getMoviesPaginated($limit, $offset);

        $data = [
            'movies' => $movies,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_movies' => $total_movies
        ];
        $this->view('movies/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate csrf
            validate_csrf();

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => trim($_POST['title']),
                'genre' => trim($_POST['genre']),
                'duration' => trim($_POST['duration']),
                'description' => trim($_POST['description']),
                'release_date' => trim($_POST['release_date']),
                'poster' => trim($_POST['poster']), // Simple text for now
                'title_err' => '',
                'duration_err' => ''
            ];

            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter title';
            }
            if (empty($data['duration'])) {
                $data['duration_err'] = 'Please enter duration';
            }

            if (empty($data['title_err']) && empty($data['duration_err'])) {
                // Add Movie
                if ($this->movieModel->add($data)) {
                    flash('movie_message', 'Movie Added');
                    redirect('movies');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('movies/add', $data);
            }

        } else {
            $data = [
                'title' => '',
                'genre' => '',
                'duration' => '',
                'description' => '',
                'release_date' => '',
                'poster' => '',
                'title_err' => '',
                'duration_err' => ''
            ];
            $this->view('movies/add', $data);
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            validate_csrf();
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'genre' => trim($_POST['genre']),
                'duration' => trim($_POST['duration']),
                'description' => trim($_POST['description']),
                'release_date' => trim($_POST['release_date']),
                'poster' => trim($_POST['poster']),
                'title_err' => '',
                'duration_err' => ''
            ];

            if (empty($data['title']))
                $data['title_err'] = 'Please enter title';
            if (empty($data['duration']))
                $data['duration_err'] = 'Please enter duration';

            if (empty($data['title_err']) && empty($data['duration_err'])) {
                if ($this->movieModel->update($data)) {
                    flash('movie_message', 'Movie Updated');
                    redirect('movies');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('movies/edit', $data);
            }
        } else {
            $movie = $this->movieModel->getMovieById($id);
            if (!$movie)
                redirect('movies');

            $data = [
                'id' => $id,
                'title' => $movie->title,
                'genre' => $movie->genre,
                'duration' => $movie->duration,
                'description' => $movie->description,
                'release_date' => $movie->release_date,
                'poster' => $movie->poster,
                'title_err' => '',
                'duration_err' => ''
            ];
            $this->view('movies/edit', $data);
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->movieModel->delete($id)) {
                flash('movie_message', 'Movie Removed');
                redirect('movies');
            } else {
                die('Something went wrong. Movie might be linked to existing showtimes/tickets.');
            }
        } else {
            redirect('movies');
        }
    }
}
