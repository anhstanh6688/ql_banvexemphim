<?php
class Pages extends Controller
{
    private $showtimeModel;
    private $movieModel;

    public function __construct()
    {
        $this->showtimeModel = $this->model('Showtime');
        $this->movieModel = $this->model('Movie');
    }

    public function index()
    {
        // Pagination for Now Showing
        $limit = 8; // Show 8 items per page
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1)
            $page = 1;

        $total_showtimes = $this->movieModel->getNowShowingMovieCount();
        $total_pages = ceil($total_showtimes / $limit);
        $offset = ($page - 1) * $limit;

        // Fetch Movies, not showtimes
        $showtimes = $this->movieModel->getNowShowingMoviesPaginated($limit, $offset);

        // Other lists (Coming Soon / Ends) remain standard
        // Coming Soon Pagination
        $limit_coming = 8;
        $page_coming = isset($_GET['page_coming']) && is_numeric($_GET['page_coming']) ? (int) $_GET['page_coming'] : 1;
        if ($page_coming < 1)
            $page_coming = 1;

        $total_coming_soon = $this->movieModel->getComingSoonCount();
        $total_pages_coming = ceil($total_coming_soon / $limit_coming);
        $offset_coming = ($page_coming - 1) * $limit_coming;

        $comingSoon = $this->movieModel->getComingSoonPaginated($limit_coming, $offset_coming);
        // Stopped Showing Pagination
        $limit_ended = 8;
        $page_ended = isset($_GET['page_ended']) && is_numeric($_GET['page_ended']) ? (int) $_GET['page_ended'] : 1;
        if ($page_ended < 1)
            $page_ended = 1;

        $total_ended = $this->movieModel->getEndedCount();
        $total_pages_ended = ceil($total_ended / $limit_ended);
        $offset_ended = ($page_ended - 1) * $limit_ended;

        $ended = $this->movieModel->getEndedPaginated($limit_ended, $offset_ended);

        // Section handling
        $section = 'now-showing';
        if (isset($_GET['page_coming'])) {
            $section = 'coming-soon';
        } elseif (isset($_GET['page_ended'])) {
            $section = 'ended';
        } elseif (isset($_GET['page'])) { // Check distinct page param for now showing
            $section = 'now-showing';
        }


        $data = [
            'title' => 'Now Showing',
            'description' => 'Book your tickets now!',
            'showtimes' => $showtimes,
            'coming_soon' => $comingSoon,
            'ended' => $ended,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'current_page_coming' => $page_coming,
            'total_pages_coming' => $total_pages_coming,
            'current_page_ended' => $page_ended,
            'total_pages_ended' => $total_pages_ended,
            'section' => $section
        ];

        $this->view('pages/index', $data);
    }
}
