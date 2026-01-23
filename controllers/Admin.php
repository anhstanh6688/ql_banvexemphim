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
        $limit = 10;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
        if ($page < 1)
            $page = 1;

        $total_orders = $this->orderModel->getOrderCount();
        $total_pages = ceil($total_orders / $limit);
        $offset = ($page - 1) * $limit;

        $orders = $this->orderModel->getOrdersPaginated($limit, $offset);

        $data = [
            'orders' => $orders,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_orders' => $total_orders
        ];

        $this->view('admin/orders', $data);
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
}
