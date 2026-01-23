<?php
class Stats
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getTotalRevenue()
    {
        $this->db->query('SELECT SUM(total_amount) as total FROM orders WHERE status = "paid"');
        $row = $this->db->single();
        return $row->total ?? 0;
    }

    public function getTotalTickets()
    {
        $this->db->query('SELECT COUNT(*) as count FROM tickets WHERE status = "valid"');
        $row = $this->db->single();
        return $row->count ?? 0;
    }

    public function getMonthlyRevenue()
    {
        // Simple revenue for current month
        $this->db->query('SELECT SUM(total_amount) as total FROM orders WHERE status = "paid" AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())');
        $row = $this->db->single();
        return $row->total ?? 0;
    }

    public function getRecentOrders($limit = 5)
    {
        $this->db->query('SELECT o.*, u.fullname, m.title 
                          FROM orders o
                          JOIN users u ON o.user_id = u.id
                          JOIN showtimes s ON o.showtime_id = s.id
                          JOIN movies m ON s.movie_id = m.id
                          ORDER BY o.created_at DESC LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getTopMovies($limit = 3)
    {
        $this->db->query('SELECT m.title, COUNT(t.id) as tickets_sold, SUM(s.price) as revenue
                          FROM tickets t
                          JOIN showtimes s ON t.showtime_id = s.id
                          JOIN movies m ON s.movie_id = m.id
                          WHERE t.status = "valid"
                          GROUP BY m.id
                          ORDER BY tickets_sold DESC
                          LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
    public function getTopCustomers($limit = 5)
    {
        $this->db->query('SELECT u.fullname, u.email, COUNT(o.id) as total_orders, SUM(o.total_amount) as total_spent
                          FROM users u
                          JOIN orders o ON u.id = o.user_id
                          WHERE o.status = "paid"
                          GROUP BY u.id
                          ORDER BY total_spent DESC
                          LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getRevenueLast7Days()
    {
        // Get last 7 days dates
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));

            $this->db->query('SELECT SUM(total_amount) as total FROM orders WHERE status = "paid" AND DATE(created_at) = :date');
            $this->db->bind(':date', $date);
            $row = $this->db->single();

            $data[] = [
                'date' => date('d/m', strtotime($date)),
                'revenue' => $row->total ?? 0
            ];
        }
        return $data;
    }

    public function getTicketStatusStats()
    {
        $this->db->query('SELECT status, COUNT(*) as count FROM tickets GROUP BY status');
        return $this->db->resultSet();
    }
}
