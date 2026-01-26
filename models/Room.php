<?php
class Room
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Get all rooms
    public function getRooms()
    {
        $this->db->query('SELECT * FROM rooms ORDER BY id DESC');
        return $this->db->resultSet();
    }

    // Get room by ID
    public function getRoomById($id)
    {
        $this->db->query('SELECT * FROM rooms WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Find room by name
    public function findRoomByName($name)
    {
        $this->db->query('SELECT * FROM rooms WHERE name = :name');
        $this->db->bind(':name', $name);
        return $this->db->single();
    }

    // Add new room
    public function add($data)
    {
        $this->db->query('INSERT INTO rooms (name, total_rows, total_cols) VALUES (:name, :total_rows, :total_cols)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':total_rows', $data['total_rows']);
        $this->db->bind(':total_cols', $data['total_cols']);

        if ($this->db->execute()) {
            // Get the ID of the newly created room
            $roomId = $this->db->lastInsertId();
            $this->generateSeats($roomId, $data['total_rows'], $data['total_cols']);
            return true;
        } else {
            return false;
        }
    }

    // Update room
    public function update($data)
    {
        $this->db->query('UPDATE rooms SET name = :name, total_rows = :total_rows, total_cols = :total_cols WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':total_rows', $data['total_rows']);
        $this->db->bind(':total_cols', $data['total_cols']);

        if ($this->db->execute()) {
            // Optional: Logic to regenerate seats if dimensions changed?
            // For now, simple update avoids destroying existing booking data.
            return true;
        } else {
            return false;
        }
    }

    // Delete room
    public function delete($id)
    {
        $this->db->query('DELETE FROM rooms WHERE id = :id');
        $this->db->bind(':id', $id);

        // Execute return true/false; foreign key constraints (like existing seats/showtimes) should be handled by DB or controller checks
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getSeatsByRoomId($roomId)
    {
        $this->db->query('SELECT * FROM seats WHERE room_id = :room_id ORDER BY id ASC');
        $this->db->bind(':room_id', $roomId);
        return $this->db->resultSet();
    }

    // Helper: Generate seats for a room
    private function generateSeats($roomId, $rows, $cols)
    {
        $rowLabels = range('A', 'Z'); // Supports up to 26 rows efficiently

        $sql = 'INSERT INTO seats (room_id, seat_code, status) VALUES ';
        $params = [];
        $values = [];

        for ($r = 0; $r < $rows; $r++) {
            // Handle row labels > Z (e.g. AA, AB) if needed, but simple A-Z is fine for now
            $rowLabel = isset($rowLabels[$r]) ? $rowLabels[$r] : 'R' . ($r + 1);

            for ($c = 1; $c <= $cols; $c++) {
                $seatCode = $rowLabel . $c;
                $values[] = "(:room_id_$seatCode, :seat_code_$seatCode, 'available')";

                $params[":room_id_$seatCode"] = $roomId;
                $params[":seat_code_$seatCode"] = $seatCode;
            }
        }

        if (!empty($values)) {
            $sql .= implode(', ', $values);
            $this->db->query($sql);
            foreach ($params as $param => $value) {
                $this->db->bind($param, $value);
            }
            $this->db->execute();
        }
    }

    // Update seat status (Used for Seat Management)
    public function updateSeatStatus($seatId, $status)
    {
        $this->db->query('UPDATE seats SET status = :status WHERE id = :id');
        $this->db->bind(':id', $seatId);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }
}
