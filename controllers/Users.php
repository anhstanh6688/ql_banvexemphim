<?php
class Users extends Controller
{
    private $orderModel;
    private $ticketModel;
    private $userModel; // Add User Model

    public function __construct()
    {
        if (!isLoggedIn()) {
            redirect('auth/login');
        }
        $this->orderModel = $this->model('Order');
        $this->ticketModel = $this->model('Ticket');
        $this->orderModel = $this->model('Order');
        $this->ticketModel = $this->model('Ticket');
        $this->userModel = $this->model('User');
        $this->commentModel = $this->model('Comment');
    }

    // Default redirects to history
    public function index()
    {
        $this->history();
    }

    public function history()
    {
        $orders = $this->orderModel->getOrdersByUserId($_SESSION['user_id']);

        $data = [
            'orders' => $orders
        ];

        $this->view('users/history', $data);
    }

    public function order_details($orderId)
    {
        $order = $this->orderModel->getOrderById($orderId);

        // Check ownership
        if ($order->user_id != $_SESSION['user_id']) {
            redirect('users/history');
        }

        $tickets = $this->ticketModel->getTicketsByOrderId($orderId);

        $data = [
            'order' => $order,
            'tickets' => $tickets
        ];

        $this->view('users/order_details', $data);
    }

    public function search()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $code = trim($_POST['ticket_code']);
            $ticket = $this->ticketModel->getTicketByCode($code);

            $data = [
                'ticket' => $ticket,
                'code' => $code,
                'error' => $ticket ? '' : 'Ticket not found'
            ];
            $this->view('users/search', $data);
        } else {
            $data = [
                'ticket' => null,
                'code' => '',
                'error' => ''
            ];
            $this->view('users/search', $data);
        }
    }

    public function cancel_ticket($ticketId)
    {
        $ticket = $this->ticketModel->getTicketByIdWithDetails($ticketId);

        if (!$ticket) {
            flash('booking_msg', 'Ticket not found', 'alert alert-danger');
            redirect('users/history');
        }

        // 1. Check Ownership
        if ($ticket->user_id != $_SESSION['user_id']) {
            redirect('users/history');
        }

        // 2. Check Status
        if ($ticket->status == 'cancelled') {
            flash('booking_msg', 'Ticket already cancelled', 'alert alert-warning');
            redirect('users/order_details/' . $ticket->order_id);
        }

        // 3. Check Time Condition (Example: Must be 2 hours before showtime)
        $showtime = strtotime($ticket->start_time);
        $now = time();
        $diff = $showtime - $now;

        // 2 hours = 7200 seconds
        if ($diff < 7200) {
            flash('booking_msg', 'Cannot cancel. Too close to showtime (Must be > 2 hours).', 'alert alert-danger');
            redirect('users/order_details/' . $ticket->order_id);
        } else {
            if ($this->ticketModel->cancel($ticketId)) {
                flash('booking_msg', 'Ticket cancelled successfully.');
                redirect('users/order_details/' . $ticket->order_id);
            } else {
                die('Something went wrong');
            }
        }
    }

    public function comments()
    {
        $comments = $this->commentModel->getByUser($_SESSION['user_id']);

        $data = [
            'comments' => $comments
        ];

        $this->view('users/comments', $data);
    }

    public function profile()
    {
        $user = $this->userModel->getUserById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $_SESSION['user_id'],
                'fullname' => trim($_POST['name']), // View uses 'name'
                'email' => $user->email, // Email cannot be changed here easily (usually requires verification)
                'phone' => trim($_POST['phone']),
                'password' => trim($_POST['password']),
                'name_err' => '',
                'phone_err' => '',
                'password_err' => ''
            ];

            if (empty($data['fullname'])) {
                $data['name_err'] = 'Please enter name';
            }
            if (empty($data['phone'])) {
                $data['phone_err'] = 'Please enter phone';
            }

            if (empty($data['name_err']) && empty($data['phone_err'])) {
                if (!empty($data['password'])) {
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }

                if ($this->userModel->update($data)) {
                    // Update Session Name
                    $_SESSION['user_name'] = $data['fullname'];
                    flash('profile_msg', 'Profile updated successfully');
                    redirect('users/profile');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/profile', $data);
            }

        } else {
            $data = [
                'fullname' => $user->fullname,
                'email' => $user->email,
                'phone' => $user->phone,
                'password' => '', // Don't show password
                'name_err' => '',
                'phone_err' => '',
                'password_err' => ''
            ];
            $this->view('users/profile', $data);
        }
    }
}
