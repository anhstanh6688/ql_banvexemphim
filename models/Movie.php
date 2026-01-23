<?php
class Movie
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getMovies()
    {
        $this->db->query('SELECT * FROM movies ORDER BY id DESC');
        return $this->db->resultSet();
    }

    public function getMoviesPaginated($limit = 5, $offset = 0)
    {
        // MySQL LIMIT uses integer, PDO binding for LIMIT can be tricky with some drivers,
        // but normally works if bind as INT.
        $this->db->query('SELECT * FROM movies ORDER BY id DESC LIMIT :limit OFFSET :offset');
        $this->db->bind(':limit', $limit, PDO::PARAM_INT); // Database Class needs update if generic bind doesn't handle int well
        // Wait, Database::bind handles int check!
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getMovieCount()
    {
        $this->db->query('SELECT COUNT(*) as count FROM movies');
        $row = $this->db->single();
        return $row->count;
    }

    public function getMovieById($id)
    {
        $this->db->query('SELECT * FROM movies WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function add($data)
    {
        $this->db->query('INSERT INTO movies (title, genre, duration, description, release_date, poster) VALUES(:title, :genre, :duration, :description, :release_date, :poster)');
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':genre', $data['genre']);
        $this->db->bind(':duration', $data['duration']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':release_date', $data['release_date']);
        $this->db->bind(':poster', $data['poster']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data)
    {
        $this->db->query('UPDATE movies SET title = :title, genre = :genre, duration = :duration, description = :description, release_date = :release_date, poster = :poster WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':genre', $data['genre']);
        $this->db->bind(':duration', $data['duration']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':release_date', $data['release_date']);
        $this->db->bind(':poster', $data['poster']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        // Add logic: Check if has showtimes?
        // Basic implementation: Delete. DB might complain if FK constraints are restrictive without cascade.
        // Assuming user knows or DB has cascade or we catch error control side.
        // Let's rely on constraint violation catch in controller or just Delete.
        // For robustness, standard SQL delete.

        $this->db->query('DELETE FROM movies WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getComingSoon()
    {
        // Release date > Now AND Not currently showing
        $today = date('Y-m-d');
        // We exclude movies that have showtimes starting from NOW onwards.
        // If a movie has a showtime today or future, it's "Now Showing".

        $this->db->query('SELECT * FROM movies 
                          WHERE release_date > :today 
                          AND id NOT IN (
                              SELECT DISTINCT movie_id FROM showtimes WHERE start_time >= NOW()
                          )
                          ORDER BY release_date ASC');
        $this->db->bind(':today', $today);
        return $this->db->resultSet();
    }

    public function getEndedCount()
    {
        $today = date('Y-m-d');
        $this->db->query('SELECT COUNT(*) as count FROM movies 
                          WHERE id NOT IN (SELECT DISTINCT movie_id FROM showtimes WHERE start_time >= :today)
                          AND release_date < :today');
        $this->db->bind(':today', $today . ' 00:00:00');
        $row = $this->db->single();
        return $row->count;
    }

    public function getEndedPaginated($limit = 8, $offset = 0)
    {
        $today = date('Y-m-d');
        $this->db->query('SELECT * FROM movies 
                          WHERE id NOT IN (SELECT DISTINCT movie_id FROM showtimes WHERE start_time >= :today)
                          AND release_date < :today
                          ORDER BY release_date DESC LIMIT :limit OFFSET :offset');
        $this->db->bind(':today', $today . ' 00:00:00');
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    public function getComingSoonCount()
    {
        $today = date('Y-m-d');
        $this->db->query('SELECT COUNT(*) as count FROM movies 
                          WHERE release_date > :today 
                          AND id NOT IN (
                              SELECT DISTINCT movie_id FROM showtimes WHERE start_time >= NOW()
                          )');
        $this->db->bind(':today', $today);
        $row = $this->db->single();
        return $row->count;
    }

    public function getComingSoonPaginated($limit = 8, $offset = 0)
    {
        $today = date('Y-m-d');
        $this->db->query('SELECT * FROM movies 
                          WHERE release_date > :today 
                          AND id NOT IN (
                              SELECT DISTINCT movie_id FROM showtimes WHERE start_time >= NOW()
                          )
                          ORDER BY release_date ASC
                          LIMIT :limit OFFSET :offset');
        $this->db->bind(':today', $today);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }
    public function getNowShowingMovieCount()
    {
        $this->db->query('SELECT COUNT(DISTINCT movie_id) as count FROM showtimes WHERE start_time >= NOW()');
        $row = $this->db->single();
        return $row->count;
    }

    public function getNowShowingMoviesPaginated($limit = 8, $offset = 0)
    {
        // Select distinct movies that have active showtimes
        $this->db->query('SELECT m.* FROM movies m
                          JOIN showtimes s ON m.id = s.movie_id
                          WHERE s.start_time >= NOW()
                          GROUP BY m.id
                          ORDER BY MAX(s.start_time) ASC
                          LIMIT :limit OFFSET :offset');
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }
}
