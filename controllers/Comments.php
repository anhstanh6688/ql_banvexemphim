<?php
class Comments extends Controller
{
    private $commentModel;

    public function __construct()
    {
        if (!isLoggedIn()) {
            redirect('auth/login');
        }
        $this->commentModel = $this->model('Comment');
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate CSRF token (assume helper exists or skip if not strict)
            // validate_csrf(); 

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'movie_id' => trim($_POST['movie_id']),
                'content' => trim($_POST['content']),
                'rating' => trim($_POST['rating']),
                'user_id' => $_SESSION['user_id']
            ];

            // Validation
            if (empty($data['content'])) {
                flash('comment_msg', 'Content cannot be empty', 'alert alert-danger');
                redirect('booking/movie/' . $data['movie_id']);
            }

            // Check spam (1 comment per movie)
            if ($this->commentModel->hasCommented($data['user_id'], $data['movie_id'])) {
                flash('comment_msg', 'You have already reviewed this movie.', 'alert alert-warning');
                redirect('booking/movie/' . $data['movie_id']);
            }

            if ($this->commentModel->create($data)) {
                flash('comment_msg', 'Review posted successfully');
                redirect('booking/movie/' . $data['movie_id']);
            } else {
                die('Something went wrong');
            }
        }
    }

    // Display Edit Form
    public function edit($id)
    {
        $comment = $this->commentModel->getCommentById($id);

        // Check ownership
        if (!$comment || $comment->user_id != $_SESSION['user_id']) {
            redirect('pages/index');
        }

        $movieModel = $this->model('Movie');
        $movie = $movieModel->getMovieById($comment->movie_id);

        $data = [
            'comment' => $comment,
            'movie' => $movie
        ];

        $this->view('comments/edit', $data);
    }

    // Process Update
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $comment = $this->commentModel->getCommentById($id);

            if (!$comment || $comment->user_id != $_SESSION['user_id']) {
                redirect('pages/index');
            }

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'rating' => trim($_POST['rating']),
                'content' => trim($_POST['content']),
                'user_id' => $_SESSION['user_id'],
                'movie_id' => $comment->movie_id
            ];

            if ($this->commentModel->updateByIdAndUser($id, $_SESSION['user_id'], $data)) {
                flash('comment_msg', 'Review updated successfully');
                redirect('booking/movie/' . $comment->movie_id);
            } else {
                die('Something went wrong');
            }
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Debug Log
            error_log("Attempting to delete comment ID: " . $id . " by User ID: " . $_SESSION['user_id']);

            $comment = $this->commentModel->getCommentById($id);

            if (!$comment) {
                error_log("Comment not found in DB");
                redirect('pages/index');
            }

            error_log("Comment Found. Owner ID: " . $comment->user_id);



            // Check permissions (Owner OR Admin)
            $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';

            if ($comment->user_id != $_SESSION['user_id'] && !$isAdmin) {
                // Not owner and Not Addmin
                redirect('pages/index');
            }

            $result = false;
            if ($isAdmin) {
                $result = $this->commentModel->delete($id);
            } else {
                $result = $this->commentModel->deleteByIdAndUser($id, $_SESSION['user_id']);
            }

            if ($result) {
                flash('comment_msg', 'Review deleted successfully');

                // Redirect logic
                if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'users/comments') !== false) {
                    redirect('users/comments');
                } else {
                    redirect('booking/movie/' . $comment->movie_id);
                }
            } else {
                die('Something went wrong');
            }
        }
    }

    // We can handle Update here too if needed
}
