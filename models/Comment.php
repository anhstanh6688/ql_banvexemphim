<?php
class Comment
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Create a new comment
    public function create($data)
    {
        // $data: user_id, movie_id, rating, content
        $this->db->query('INSERT INTO comments (user_id, movie_id, rating, content) VALUES (:user_id, :movie_id, :rating, :content)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':movie_id', $data['movie_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':content', $data['content']);

        return $this->db->execute();
    }

    // Update comment (only by owner)
    public function updateByIdAndUser($commentId, $userId, $data)
    {
        $this->db->query('UPDATE comments SET content = :content, rating = :rating WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':id', $commentId);
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }

    // Delete comment (only by owner)
    public function deleteByIdAndUser($commentId, $userId)
    {
        $this->db->query('DELETE FROM comments WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $commentId);
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }

    // Delete comment (Admin)
    public function delete($id)
    {
        $this->db->query('DELETE FROM comments WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Get comments for a movie
    public function getByMovie($movieId, $limit = 20, $offset = 0)
    {
        $this->db->query('SELECT c.*, u.name as user_name, u.email as user_email 
                          FROM comments c
                          JOIN users u ON c.user_id = u.id
                          WHERE c.movie_id = :movie_id AND c.status = "visible"
                          ORDER BY c.created_at DESC
                          LIMIT :limit OFFSET :offset');
        $this->db->bind(':movie_id', $movieId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);

        return $this->db->resultSet();
    }

    // Get comments by a user (Comment History)
    public function getByUser($userId, $limit = 20, $offset = 0)
    {
        $this->db->query('SELECT c.*, m.title as movie_title, m.poster as movie_poster
                          FROM comments c
                          JOIN movies m ON c.movie_id = m.id
                          WHERE c.user_id = :user_id
                          ORDER BY c.created_at DESC
                          LIMIT :limit OFFSET :offset');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);

        return $this->db->resultSet();
    }

    // Get single comment
    public function getCommentById($id)
    {
        $this->db->query('SELECT * FROM comments WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Check if user has commented on this movie
    public function hasCommented($userId, $movieId)
    {
        $this->db->query('SELECT id FROM comments WHERE user_id = :user_id AND movie_id = :movie_id LIMIT 1');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':movie_id', $movieId);

        $row = $this->db->single();
        return ($row) ? true : false;
    }

    // Admin: Set status
    public function setStatus($commentId, $status)
    {
        $this->db->query('UPDATE comments SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $commentId);
        return $this->db->execute();
    }

    // Admin: Get all comments
    public function getAllComments($limit = 50, $offset = 0)
    {
        $this->db->query('SELECT c.*, u.name as user_name, m.title as movie_title
                          FROM comments c
                          JOIN users u ON c.user_id = u.id
                          JOIN movies m ON c.movie_id = m.id
                          ORDER BY c.created_at DESC
                          LIMIT :limit OFFSET :offset');
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet();
    }
}
