<?php
class Showtime
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getShowtimes()
    {
        // Keep original for back-compat if needed, or redirect to paginated?
        // User specific request: paginate.
        // Let's keep this as 'all' or default, but we will likely use getShowtimesPaginated in Controller.
        $this->db->query('SELECT s.*, m.title, m.poster, r.name as room_name 
                          FROM showtimes s
                          JOIN movies m ON s.movie_id = m.id
                          JOIN rooms r ON s.room_id = r.id
                          WHERE s.start_time >= NOW()
                          ORDER BY s.start_time ASC');
        return $this->db->resultSet();
    }

    public function getShowtimesPaginated($limit = 8, $offset = 0)
    {
        $this->db->query('SELECT s.*, m.title, m.poster, r.name as room_name 
                          FROM showtimes s
                          JOIN movies m ON s.movie_id = m.id
                          JOIN rooms r ON s.room_id = r.id
                          WHERE s.start_time >= NOW()
                          ORDER BY s.start_time ASC
                          LIMIT :limit OFFSET :offset');
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getShowtimeCount()
    {
        $this->db->query('SELECT COUNT(*) as count FROM showtimes WHERE start_time >= NOW()');
        $row = $this->db->single();
        return $row->count;
    }

    // Get ALL Showtimes Paginated (Admin view)
    public function getAllShowtimesPaginated($limit = 10, $offset = 0)
    {
        $this->db->query('SELECT s.*, m.title, r.name as room_name 
                          FROM showtimes s
                          JOIN movies m ON s.movie_id = m.id
                          JOIN rooms r ON s.room_id = r.id
                          ORDER BY s.start_time DESC
                          LIMIT :limit OFFSET :offset');
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getAllShowtimeCount()
    {
        $this->db->query('SELECT COUNT(*) as count FROM showtimes');
        $row = $this->db->single();
        return $row->count;
    }

    public function getFilteredShowtimesCount($filters)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM showtimes s
                JOIN movies m ON s.movie_id = m.id
                JOIN rooms r ON s.room_id = r.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND m.title LIKE :search";
            $params[':search'] = "%" . $filters['search'] . "%";
        }

        if (!empty($filters['room_id'])) {
            $sql .= " AND s.room_id = :room_id";
            $params[':room_id'] = $filters['room_id'];
        }

        if (!empty($filters['date'])) {
            $sql .= " AND DATE(s.start_time) = :date";
            $params[':date'] = $filters['date'];
        }

        $this->db->query($sql);
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }

        $row = $this->db->single();
        return $row->count;
    }

    public function getFilteredShowtimesPaginated($filters, $limit = 10, $offset = 0)
    {
        $sql = "SELECT s.*, m.title, r.name as room_name 
                FROM showtimes s
                JOIN movies m ON s.movie_id = m.id
                JOIN rooms r ON s.room_id = r.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['search'])) {
            $sql .= " AND m.title LIKE :search";
            $params[':search'] = "%" . $filters['search'] . "%";
        }

        if (!empty($filters['room_id'])) {
            $sql .= " AND s.room_id = :room_id";
            $params[':room_id'] = $filters['room_id'];
        }

        if (!empty($filters['date'])) {
            $sql .= " AND DATE(s.start_time) = :date";
            $params[':date'] = $filters['date'];
        }

        $sql .= " ORDER BY s.start_time DESC LIMIT :limit OFFSET :offset";

        $this->db->query($sql);
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);

        return $this->db->resultSet();
    }

    // Get ALL Showtimes (Admin view - including past ones)
    public function getAllShowtimes()
    {
        $this->db->query('SELECT s.*, m.title, r.name as room_name 
                          FROM showtimes s
                          JOIN movies m ON s.movie_id = m.id
                          JOIN rooms r ON s.room_id = r.id
                          ORDER BY s.start_time DESC');
        return $this->db->resultSet();
    }

    public function getShowtimeById($id)
    {
        $this->db->query('SELECT s.*, m.title, r.name as room_name 
                          FROM showtimes s
                          JOIN movies m ON s.movie_id = m.id
                          JOIN rooms r ON s.room_id = r.id
                          WHERE s.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function add($data)
    {
        // Calculate End Time: Start + Duration + 15min buffer
        // Duration is in minutes.
        // We need to fetch movie duration first to be safe, but data passed should have it?
        // Let's assume controller passes the calculated end_time or we calculate it here.
        // Better to calculate here or let controller do it.
        // Let's take start_time and movie_duration.

        $start = new DateTime($data['start_time']);
        $duration = (int) $data['duration']; // from movie

        // Add duration
        $end = clone $start;
        $end->modify("+$duration minutes");
        // Add 15 mins buffer
        $endWithBuffer = clone $end;
        $endWithBuffer->modify("+15 minutes");

        $endTimeStr = $end->format('Y-m-d H:i:s');

        // Store precise end time in DB?
        // Schema says end_time. 
        // Logic: end_time in DB should be the time the movie actually ends?
        // Or end_time inclusive of buffer? 
        // User prompt: "logic... check trùng... based on duration + 15p".
        // Let's store actual movie end time in `end_time`, but use buffer for checking.

        $this->db->query('INSERT INTO showtimes (movie_id, room_id, start_time, end_time, price, status) VALUES(:movie_id, :room_id, :start_time, :end_time, :price, :status)');
        $this->db->bind(':movie_id', $data['movie_id']);
        $this->db->bind(':room_id', $data['room_id']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $endTimeStr);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':status', 'active');

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        $this->db->query('DELETE FROM showtimes WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Check Overlap
    public function checkOverlap($roomId, $startTime, $duration)
    {
        // New Show: Start = S1, End = E1 (where E1 = S1 + Duration + 15)
        // Existing Show: Start = S2, End = E2 (E2 in DB is actual end, so we treat its occupied slot as S2 to E2+15)

        // Formula for overlap: Not (E1 <= S2 OR S1 >= E2_with_buffer)
        // Which is: E1 > S2 AND S1 < E2_with_buffer

        $startObj = new DateTime($startTime);
        $endObjWithBuffer = clone $startObj;
        $endObjWithBuffer->modify("+" . ($duration + 15) . " minutes");

        $s1 = $startObj->format('Y-m-d H:i:s');
        $e1 = $endObjWithBuffer->format('Y-m-d H:i:s');

        // Query existing showtimes for this room
        // We need to compare against (S2) and (E2 + 15)
        // Or simpler:
        // We want to find any show where:
        // (NewStart < ExistingEnd + 15) AND (NewEnd+15 > ExistingStart)

        $sql = "SELECT * FROM showtimes 
                WHERE room_id = :room_id 
                AND status = 'active'
                AND :new_start < DATE_ADD(end_time, INTERVAL 15 MINUTE)
                AND :new_end > start_time";

        $this->db->query($sql);
        $this->db->bind(':room_id', $roomId);
        $this->db->bind(':new_start', $s1);
        $this->db->bind(':new_end', $e1);

        $this->db->single();
        // If row count > 0, we have an overlap
        return ($this->db->rowCount() > 0);
    }
    // Update Showtime
    public function update($data)
    {
        $start = new DateTime($data['start_time']);
        $duration = (int) $data['duration'];

        $end = clone $start;
        $end->modify("+$duration minutes");
        $endTimeStr = $end->format('Y-m-d H:i:s');

        $this->db->query('UPDATE showtimes SET movie_id = :movie_id, room_id = :room_id, start_time = :start_time, end_time = :end_time, price = :price WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':movie_id', $data['movie_id']);
        $this->db->bind(':room_id', $data['room_id']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $endTimeStr);
        $this->db->bind(':price', $data['price']);

        return $this->db->execute();
    }

    // Check Update Overlap (Exclude current showtime ID)
    public function checkUpdateOverlap($id, $roomId, $startTime, $duration)
    {
        $startObj = new DateTime($startTime);
        $endObjWithBuffer = clone $startObj;
        $endObjWithBuffer->modify("+" . ($duration + 15) . " minutes");

        $s1 = $startObj->format('Y-m-d H:i:s');
        $e1 = $endObjWithBuffer->format('Y-m-d H:i:s');

        // Overlap logic same as add, but exclude self
        $sql = "SELECT * FROM showtimes 
                WHERE room_id = :room_id 
                AND status = 'active'
                AND id != :id
                AND :new_start < DATE_ADD(end_time, INTERVAL 15 MINUTE)
                AND :new_end > start_time";

        $this->db->query($sql);
        $this->db->bind(':room_id', $roomId);
        $this->db->bind(':id', $id);
        $this->db->bind(':new_start', $s1);
        $this->db->bind(':new_end', $e1);

        $this->db->single();
        return ($this->db->rowCount() > 0);
    }

    // Get active showtimes for a specific movie
    public function getShowtimesByMovieId($movieId)
    {
        $this->db->query('SELECT s.*, r.name as room_name, r.total_rows, r.total_cols
                          FROM showtimes s
                          JOIN rooms r ON s.room_id = r.id
                          WHERE s.movie_id = :movie_id 
                          AND s.start_time >= NOW()
                          ORDER BY s.start_time ASC');
        $this->db->bind(':movie_id', $movieId);
        return $this->db->resultSet();
    }
}
