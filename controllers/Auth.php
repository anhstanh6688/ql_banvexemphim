<?php
class Auth extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function register()
    {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            validate_csrf();

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                // Check email
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            }

            // Validate Name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter name';
            }

            // Validate Phone
            if (empty($data['phone'])) {
                $data['phone_err'] = 'Please enter phone number';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            // Validate Confirm Password
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['phone_err'])) {
                // Validated

                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register User
                $data['fullname'] = $data['name'];
                if ($this->userModel->register($data)) {
                    flash('register_success', 'You are registered and can log in');
                    redirect('auth/login');
                } else {
                    die('Something went wrong');
                }

            } else {
                // Load view with errors
                $this->view('auth/register', $data);
            }

        } else {
            // Init data
            $data = [
                'name' => '',
                'email' => '',
                'phone' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Load view
            $this->view('auth/register', $data);
        }
    }

    public function login()
    {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            validate_csrf();

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',
            ];

            // Validate Email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }

            // Validate Password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // Check for user/email
            if ($this->userModel->findUserByEmail($data['email'])) {
                // User found
            } else {
                // User not found
                $data['email_err'] = 'No user found';
            }

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validated
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if ($loggedInUser) {
                    // Create Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('auth/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('auth/login', $data);
            }


        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
            ];

            // Load view
            $this->view('auth/login', $data);
        }
    }

    public function createUserSession($user, $redirect = true)
    {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->fullname;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['user_role'] = $user->role;

        if ($redirect) {
            if (isset($_SESSION['url_redirect'])) {
                $url = $_SESSION['url_redirect'];
                unset($_SESSION['url_redirect']);
                header('Location: ' . $url);
                exit;
            } else {
                redirect('pages/index');
            }
        }
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        session_destroy();
        redirect('auth/login');
    }

    public function google_login()
    {
        header('Content-Type: application/json');

        try {
            // Get JSON input
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (!isset($data['credential'])) {
                echo json_encode(['success' => false, 'message' => 'No credential provided']);
                return;
            }

            $id_token = $data['credential'];

            // Verify ID Token with Google API
            $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $id_token;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Fix for local development SSL certificate issues
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);

            if ($response === false) {
                throw new Exception('Curl error: ' . curl_error($ch));
            }
            curl_close($ch);

            $payload = json_decode($response, true);

            if (!$payload || isset($payload['error_description'])) {
                echo json_encode(['success' => false, 'message' => 'Invalid Token: ' . ($payload['error_description'] ?? 'Unknown error')]);
                return;
            }

            // Token is valid
            $email = $payload['email'];
            $name = $payload['name'];

            // Check if user exists
            $user = $this->userModel->getUserByEmail($email);

            if ($user) {
                // User exists, log them in
                $this->createUserSession($user, false);
                echo json_encode(['success' => true, 'redirect' => URLROOT . '/pages/index']);
            } else {
                // Register new user
                $password = bin2hex(random_bytes(8)); // Random password
                $newUser = [
                    'fullname' => $name,
                    'email' => $email,
                    'password' => password_hash($password, PASSWORD_DEFAULT)
                ];

                if ($this->userModel->registerGoogleUser($newUser)) {
                    // Get the new user to log them in
                    $user = $this->userModel->getUserByEmail($email);
                    $this->createUserSession($user, false);
                    echo json_encode(['success' => true, 'redirect' => URLROOT . '/pages/index']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Registration failed']);
                }
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()]);
        }
    }
}
