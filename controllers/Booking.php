<?php
class Booking extends Controller
{
    private $showtimeModel;
    private $movieModel;
    private $roomModel;
    private $ticketModel;
    private $userModel;
    private $commentModel;

    public function __construct()
    {
        if (!isLoggedIn()) {
            redirect('auth/login');
        }
        $this->showtimeModel = $this->model('Showtime');
        $this->movieModel = $this->model('Movie');
        $this->roomModel = $this->model('Room');
        $this->ticketModel = $this->model('Ticket');
        $this->userModel = $this->model('User');
        $this->commentModel = $this->model('Comment');
    }

    // Select Showtime for a Movie
    public function movie($movieId)
    {
        $movie = $this->movieModel->getMovieById($movieId);
        if (!$movie) {
            flash('movie_message', 'Movie not found');
            redirect('pages/index');
        }

        $showtimes = $this->showtimeModel->getShowtimesByMovieId($movieId);

        // Group showtimes by Date and Collect IDs for seat counting
        $groupedShowtimes = [];
        $showtimeIds = [];
        foreach ($showtimes as $show) {
            $date = date('Y-m-d', strtotime($show->start_time));
            $groupedShowtimes[$date][] = $show;
            $showtimeIds[] = $show->id;
        }

        // Get Booked Seat Counts
        $ticketCounts = $this->ticketModel->getTicketCountsByShowtimeIds($showtimeIds);

        // Extended Movie Info (Mock Data for Demo)
        $movieInfo = [
            'rating' => '8.5/10',
            'director' => 'Christopher Nolan',
            'cast' => 'Cillian Murphy, Emily Blunt, Matt Damon',
            'language' => 'English',
            'subtitle' => 'Vietnamese',
        ];

        // Fetch Comments
        $comments = $this->commentModel->getByMovie($movieId);

        // Check if current user has commented
        $userHasCommented = false;
        if (isLoggedIn()) {
            $userHasCommented = $this->commentModel->hasCommented($_SESSION['user_id'], $movieId);
        }

        $data = [
            'movie' => $movie,
            'grouped_showtimes' => $groupedShowtimes,
            'ticket_counts' => $ticketCounts,
            'movie_info' => $movieInfo,
            'total_seats' => 60,
            'comments' => $comments,
            'user_has_commented' => $userHasCommented
        ];

        $this->view('booking/showtimes', $data);
    }

    // Use showtime ID to book
    public function seats($showtimeId)
    {
        $showtime = $this->showtimeModel->getShowtimeById($showtimeId);
        if (!$showtime) {
            die('Invalid Showtime');
        }

        $movie = $this->movieModel->getMovieById($showtime->movie_id);
        $room = $this->roomModel->getRoomById($showtime->room_id);

        // Get all seats for this room
        // We can query seats table directly here or add a method in Room model
        // Let's use inline query via Database for speed or add to Room model. 
        // Better: add getSeatsByRoomId in Room model. I will add it shortly.
        // For now, let's assume Room model has it or we do it here via db instance if possible?
        // Controller doesn't have direct DB access unless we use model.
        // I will update Room model in next step.
        $seats = $this->roomModel->getSeatsByRoomId($room->id);

        // Get booked seats
        $bookedSeats = $this->ticketModel->getBookedSeats($showtimeId);

        $data = [
            'showtime' => $showtime,
            'movie' => $movie,
            'room' => $room,
            'seats' => $seats,
            'bookedSeats' => $bookedSeats
        ];

        $this->view('booking/seats', $data);
    }

    public function checkout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            validate_csrf();

            $showtimeId = $_POST['showtime_id'];
            $selectedSeats = isset($_POST['seats']) ? $_POST['seats'] : []; // Array of seat IDs

            if (empty($selectedSeats)) {
                die('No seats selected');
            }

            // Calculate Total
            $showtime = $this->showtimeModel->getShowtimeById($showtimeId);
            $movie = $this->movieModel->getMovieById($showtime->movie_id);
            $room = $this->roomModel->getRoomById($showtime->room_id);

            $totalAmount = count($selectedSeats) * $showtime->price;

            // Get Seat Codes for Display
            // Hacky way: Loop or fetch?
            // Let's rely on stored IDs and fetch names if needed or complex query.
            // For now, let's just show count or fetch codes?
            // Let's fetch Codes by IDs
            // We need a helper for this or loop query (bad perf).
            // Let's perform a query in Controller for simplicity using DB instance or simple loop.
            // Better: getSeatsByIds in Room Model?
            // Or just a quick loop since max 10 seats usually.

            $seatCodes = [];
            // Temporary direct DB or loop
            $db = Database::getInstance();
            // This is not ideal MVC but OK for quick patch.
            // Let's assume RoomModel has getSeatsByIds? No?
            // Let's just create an array of seat codes by querying.
            $placeholders = implode(',', array_fill(0, count($selectedSeats), '?'));
            $db->query("SELECT seat_code FROM seats WHERE id IN ($placeholders)");
            // execute with array
            // Database class might not support array in execute for IN clause easily with current wrapper?
            // Current Wrapper binding? default PDO supports execute([v1, v2])
            // Let's try raw PDO access or loop.
            // Safe loop:
            foreach ($selectedSeats as $sid) {
                $db->query("SELECT seat_code FROM seats WHERE id = :id");
                $db->bind(":id", $sid);
                $s = $db->single();
                if ($s)
                    $seatCodes[] = $s->seat_code;
            }

            $data = [
                'showtime' => $showtime,
                'movie' => $movie,
                'room' => $room,
                'selected_seats' => $selectedSeats,
                'seat_codes' => $seatCodes,
                'total_amount' => $totalAmount
            ];

            $this->view('booking/payment', $data);

        } else {
            redirect('pages/index');
        }
    }

    public function process_payment()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            validate_csrf();

            $showtimeId = $_POST['showtime_id'];
            $selectedSeats = isset($_POST['seats']) ? $_POST['seats'] : [];

            if (empty($selectedSeats)) {
                die('No seats provided');
            }

            // Re-calculate total to prevent tampering? Yes.
            $showtime = $this->showtimeModel->getShowtimeById($showtimeId);
            $totalAmount = count($selectedSeats) * $showtime->price;
            $userId = $_SESSION['user_id'];

            $db = Database::getInstance();

            try {
                $db->beginTransaction();

                // 1. Create Order
                $db->query('INSERT INTO orders (user_id, showtime_id, total_amount, status) VALUES (:uid, :sid, :total, "paid")');
                $db->bind(':uid', $userId);
                $db->bind(':sid', $showtimeId);
                $db->bind(':total', $totalAmount);
                $db->execute();
                $orderId = $db->lastInsertId();

                // 2. Create Tickets
                foreach ($selectedSeats as $seatId) {
                    $ticketCode = strtoupper(uniqid('TICKET-'));
                    $db->query('INSERT INTO tickets (order_id, showtime_id, seat_id, ticket_code) VALUES (:oid, :sid, :seatid, :code)');
                    $db->bind(':oid', $orderId);
                    $db->bind(':sid', $showtimeId);
                    $db->bind(':seatid', $seatId);
                    $db->bind(':code', $ticketCode);
                    $db->execute();
                }

                $db->endTransaction();

                // Redirect to Success
                flash('booking_success', 'Booking Successful!');
                redirect('pages/index');

            } catch (Exception $e) {
                $db->cancelTransaction();
                // Check if error is duplicate entry (SQLSTATE 23000)
                if (strpos($e->getMessage(), '23000') !== false || strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    flash('booking_msg', 'One or more selected seats were barely just taken by someone else. Please select other seats.', 'alert alert-danger');
                    redirect('booking/seats/' . $showtimeId);
                } else {
                    die('Booking Failed: System Error. ' . $e->getMessage());
                }
            }
        }
    }
}
