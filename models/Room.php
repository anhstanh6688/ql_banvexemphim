<?php
class Room
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getRooms()
    {
        $this->db->query('SELECT * FROM rooms');
        return $this->db->resultSet();
    }

    public function getRoomById($id)
    {
        $this->db->query('SELECT * FROM rooms WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getSeatsByRoomId($roomId)
    {
        $this->db->query('SELECT * FROM seats WHERE room_id = :room_id ORDER BY seat_code');
        $this->db->bind(':room_id', $roomId);
        return $this->db->resultSet();
    }
}
