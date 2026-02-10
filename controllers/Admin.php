<?php
class Admin extends Controller
{
    private $statsModel;
    private $orderModel;

    public function __construct()
    {
        require_once '../core/middleware.php';
        if (!isLoggedIn()) {
            redirect('auth/login');
        }
        requireAdmin();

        $this->statsModel = $this->model('Stats');
        $this->orderModel = $this->model('Order');
    }

    public function orders()
    {
        // Pagination
        $limit = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1)
            $page = 1;

        // Filters
        $filters = [
            'search' => isset($_GET['search']) ? trim($_GET['search']) : '',
            'date' => isset($_GET['date']) ? trim($_GET['date']) : ''
        ];

        // Get Filtered Data
        $total_orders = $this->orderModel->getFilteredOrderCount($filters);
        $total_pages = ceil($total_orders / $limit);
        $offset = ($page - 1) * $limit;

        $orders = $this->orderModel->getFilteredOrdersPaginated($filters, $limit, $offset);

        $data = [
            'orders' => $orders,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_orders' => $total_orders,
            'filters' => $filters
        ];

        // Check for AJAX
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $this->view('admin/orders_partial', $data);
        } else {
            $this->view('admin/orders', $data);
        }
    }

    public function index()
    {
        $totalRevenue = $this->statsModel->getTotalRevenue();
        $totalTickets = $this->statsModel->getTotalTickets();
        $monthlyRevenue = $this->statsModel->getMonthlyRevenue();
        $recentOrders = $this->statsModel->getRecentOrders();
        $topMovies = $this->statsModel->getTopMovies();
        $revenueTrend = $this->statsModel->getRevenueLast7Days();
        $ticketStats = $this->statsModel->getTicketStatusStats();
        $topCustomers = $this->statsModel->getTopCustomers();

        $data = [
            'total_revenue' => $totalRevenue,
            'total_tickets' => $totalTickets,
            'monthly_revenue' => $monthlyRevenue,
            'recent_orders' => $recentOrders,
            'top_movies' => $topMovies,
            'revenue_trend' => $revenueTrend,
            'ticket_stats' => $ticketStats,
            'top_customers' => $topCustomers
        ];

        $this->view('admin/dashboard', $data);
    }

    public function cancel_ticket()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST content
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $ticketId = $_POST['ticket_id'];
            $reason = trim($_POST['reason']);
            $orderId = $_POST['order_id']; // For redirecting back

            // Basic Validation
            if (empty($reason)) {
                flash('admin_msg', 'Cancellation reason is required', 'alert alert-danger');
                redirect('users/order_details/' . $orderId);
            }

            // Execute Cancellation
            if ($this->model('Ticket')->cancel($ticketId, $reason)) {
                flash('admin_msg', 'Ticket cancelled successfully');
            } else {
                flash('admin_msg', 'Something went wrong', 'alert alert-danger');
            }
            redirect('users/order_details/' . $orderId);
        } else {
            redirect('admin/orders');
        }
    }
}
