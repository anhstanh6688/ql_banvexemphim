<?php
class Order
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Get Orders by User ID
    public function getOrdersByUserId($userId)
    {
        $this->db->query('SELECT o.*, m.title as movie_title, s.start_time, r.name as room_name,
                          (SELECT COUNT(*) FROM tickets t WHERE t.order_id = o.id) as ticket_count
                          FROM orders o
                          JOIN showtimes s ON o.showtime_id = s.id
                          JOIN movies m ON s.movie_id = m.id
                          JOIN rooms r ON s.room_id = r.id
                          WHERE o.user_id = :uid
                          ORDER BY o.created_at DESC');
        $this->db->bind(':uid', $userId);
        return $this->db->resultSet();
    }

    public function getOrderById($id)
    {
        $this->db->query('SELECT o.*, m.title as movie_title, s.start_time, r.name as room_name, u.fullname, u.email, u.phone
                          FROM orders o
                          JOIN showtimes s ON o.showtime_id = s.id
                          JOIN movies m ON s.movie_id = m.id
                          JOIN rooms r ON s.room_id = r.id
                          JOIN users u ON o.user_id = u.id
                          WHERE o.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getOrdersPaginated($limit = 10, $offset = 0)
    {
        $this->db->query('SELECT o.*, m.title as movie_title, u.fullname, s.start_time
                          FROM orders o
                          JOIN showtimes s ON o.showtime_id = s.id
                          JOIN movies m ON s.movie_id = m.id
                          JOIN users u ON o.user_id = u.id
                          ORDER BY o.created_at DESC
                          LIMIT :limit OFFSET :offset');
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getOrderCount()
    {
        $this->db->query('SELECT COUNT(*) as count FROM orders');
        $row = $this->db->single();
        return $row->count;
    }
}
