<?php
class Ticket
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getBookedSeats($showtimeId)
    {
        $this->db->query('SELECT seat_id FROM tickets WHERE showtime_id = :showtime_id AND status = "valid"');
        $this->db->bind(':showtime_id', $showtimeId);
        $results = $this->db->resultSet();

        $bookedSeatIds = [];
        foreach ($results as $row) {
            $bookedSeatIds[] = $row->seat_id;
        }
        return $bookedSeatIds;
    }

    // Get Tickets by Order ID
    public function getTicketsByOrderId($orderId)
    {
        $this->db->query('SELECT t.*, s.seat_code 
                          FROM tickets t 
                          JOIN seats s ON t.seat_id = s.id 
                          WHERE t.order_id = :oid');
        $this->db->bind(':oid', $orderId);
        return $this->db->resultSet();
    }

    // Get single ticket by Code
    public function getTicketByCode($code)
    {
        $this->db->query('SELECT t.*, o.user_id, s.start_time, m.title as movie_title, st.seat_code, sm.price
                          FROM tickets t
                          JOIN orders o ON t.order_id = o.id
                          JOIN showtimes s ON t.showtime_id = s.id
                          JOIN movies m ON s.movie_id = m.id
                          JOIN seats st ON t.seat_id = st.id
                          JOIN showtimes sm ON t.showtime_id = sm.id
                          WHERE t.ticket_code = :code');
        $this->db->bind(':code', $code);
        return $this->db->single();
    }

    // Get Ticket by ID with Details (for Cancellation check)
    public function getTicketByIdWithDetails($id)
    {
        $this->db->query('SELECT t.*, o.user_id, s.start_time 
                          FROM tickets t
                          JOIN orders o ON t.order_id = o.id
                          JOIN showtimes s ON t.showtime_id = s.id
                          WHERE t.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Cancel Ticket
    public function cancel($id, $reason = null)
    {
        $sql = 'UPDATE tickets SET status = "cancelled"';
        if ($reason) {
            $sql .= ', cancellation_reason = :reason';
        }
        $sql .= ' WHERE id = :id';

        $this->db->query($sql);
        $this->db->bind(':id', $id);
        if ($reason) {
            $this->db->bind(':reason', $reason);
        }
        return $this->db->execute();
    }

    public function getTicketCountsByShowtimeIds($showtimeIds)
    {
        if (empty($showtimeIds))
            return [];

        $placeholders = implode(',', array_fill(0, count($showtimeIds), '?'));

        // Count valid tickets for these showtimes
        $sql = "SELECT showtime_id, COUNT(*) as count 
                FROM tickets 
                WHERE showtime_id IN ($placeholders) 
                AND status = 'valid'
                GROUP BY showtime_id";

        $this->db->query($sql);
        foreach ($showtimeIds as $k => $id) {
            $this->db->bind($k + 1, $id);
        }

        $results = $this->db->resultSet();
        $counts = [];
        foreach ($results as $row) {
            $counts[$row->showtime_id] = $row->count;
        }
        return $counts;
    }

    // New Method: Count tickets ONLY on Available Seats
    public function getTicketCountsOnAvailableSeatsByShowtimeIds($showtimeIds)
    {
        if (empty($showtimeIds))
            return [];

        $placeholders = implode(',', array_fill(0, count($showtimeIds), '?'));

        $sql = "SELECT t.showtime_id, COUNT(*) as count 
                FROM tickets t
                JOIN seats s ON t.seat_id = s.id
                WHERE t.showtime_id IN ($placeholders) 
                AND t.status = 'valid'
                AND s.status = 'available'
                GROUP BY t.showtime_id";

        $this->db->query($sql);
        foreach ($showtimeIds as $k => $id) {
            $this->db->bind($k + 1, $id);
        }

        $results = $this->db->resultSet();
        $counts = [];
        foreach ($results as $row) {
            $counts[$row->showtime_id] = $row->count;
        }
        return $counts;
    }
}
