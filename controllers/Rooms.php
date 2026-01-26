<?php
class Rooms extends Controller
{
    private $roomModel;

    public function __construct()
    {
        // Enforce Admin Access
        require_once '../core/middleware.php';
        if (!isLoggedIn()) {
            redirect('auth/login');
        }
        requireAdmin();

        $this->roomModel = $this->model('Room');
    }

    public function index()
    {
        $rooms = $this->roomModel->getRooms();
        $data = [
            'rooms' => $rooms
        ];
        $this->view('rooms/index', $data);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            validate_csrf();
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'total_rows' => trim($_POST['total_rows']),
                'total_cols' => trim($_POST['total_cols']),
                'name_err' => '',
                'rows_err' => '',
                'cols_err' => ''
            ];

            // Validation
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter room name';
            }
            if (empty($data['total_rows'])) {
                $data['rows_err'] = 'Please enter total rows';
            } elseif (!is_numeric($data['total_rows']) || $data['total_rows'] < 1) {
                $data['rows_err'] = 'Rows must be a positive number';
            }
            if (empty($data['total_cols'])) {
                $data['cols_err'] = 'Please enter total columns';
            } elseif (!is_numeric($data['total_cols']) || $data['total_cols'] < 1) {
                $data['cols_err'] = 'Columns must be a positive number';
            }

            if (empty($data['name_err']) && empty($data['rows_err']) && empty($data['cols_err'])) {
                if ($this->roomModel->add($data)) {
                    flash('room_message', 'Room Added Successfully');
                    redirect('rooms');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('rooms/add', $data);
            }

        } else {
            $data = [
                'name' => '',
                'total_rows' => '',
                'total_cols' => '',
                'name_err' => '',
                'rows_err' => '',
                'cols_err' => ''
            ];
            $this->view('rooms/add', $data);
        }
    }

    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            validate_csrf();
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'total_rows' => trim($_POST['total_rows']),
                'total_cols' => trim($_POST['total_cols']),
                'name_err' => '',
                'rows_err' => '',
                'cols_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter room name';
            }
            if (empty($data['total_rows'])) {
                $data['rows_err'] = 'Please enter total rows';
            } elseif (!is_numeric($data['total_rows']) || $data['total_rows'] < 1) {
                $data['rows_err'] = 'Rows must be a positive number';
            }
            if (empty($data['total_cols'])) {
                $data['cols_err'] = 'Please enter total columns';
            } elseif (!is_numeric($data['total_cols']) || $data['total_cols'] < 1) {
                $data['cols_err'] = 'Columns must be a positive number';
            }

            if (empty($data['name_err']) && empty($data['rows_err']) && empty($data['cols_err'])) {
                if ($this->roomModel->update($data)) {
                    flash('room_message', 'Room Updated');
                    redirect('rooms');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('rooms/edit', $data);
            }
        } else {
            $room = $this->roomModel->getRoomById($id);
            if (!$room) {
                redirect('rooms');
            }

            $data = [
                'id' => $id,
                'name' => $room->name,
                'total_rows' => $room->total_rows,
                'total_cols' => $room->total_cols,
                'name_err' => '',
                'rows_err' => '',
                'cols_err' => ''
            ];
            $this->view('rooms/edit', $data);
        }
    }

    public function delete($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->roomModel->delete($id)) {
                flash('room_message', 'Room Removed');
                redirect('rooms');
            } else {
                die('Something went wrong. This room may be in use by Showtimes.');
            }
        } else {
            redirect('rooms');
        }
    }

    // Manage Seats (Visual Grid)
    public function seats($id)
    {
        $room = $this->roomModel->getRoomById($id);
        if (!$room) {
            redirect('rooms');
        }

        $seats = $this->roomModel->getSeatsByRoomId($id);

        $data = [
            'room' => $room,
            'seats' => $seats
        ];

        $this->view('rooms/seats', $data);
    }

    // AJAX: Update Seat Status
    public function update_seat()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Decode JSON input
            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input) {
                echo json_encode(['success' => false, 'message' => 'Invalid input']);
                return;
            }

            $seatId = $input['seat_id'];
            $status = $input['status'];

            // Security check: Only allow valid statuses
            if (!in_array($status, ['available', 'locked'])) {
                echo json_encode(['success' => false, 'message' => 'Invalid status']);
                return;
            }

            if ($this->roomModel->updateSeatStatus($seatId, $status)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error']);
            }
        }
    }
}
