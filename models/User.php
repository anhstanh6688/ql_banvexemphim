<?php
class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Find user by email
    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        return ($this->db->rowCount() > 0) ? true : false;
    }

    // Get User by ID
    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return $row;
    }

    // Register User
    public function register($data)
    {
        $this->db->query('INSERT INTO users (fullname, email, phone, password, role) VALUES(:name, :email, :phone, :password, :role)');
        // Bind values
        $this->db->bind(':name', $data['fullname']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', 'user'); // Default role 'user'

        // Execute
        return $this->db->execute();
    }

    // Login User
    public function login($email, $password)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    // Update User
    public function update($data)
    {
        // Check if password update is needed
        if (!empty($data['password'])) {
            $this->db->query('UPDATE users SET fullname = :fullname, phone = :phone, password = :password WHERE id = :id');
            $this->db->bind(':password', $data['password']);
        } else {
            $this->db->query('UPDATE users SET fullname = :fullname, phone = :phone WHERE id = :id');
        }

        $this->db->bind(':fullname', $data['fullname']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':id', $data['id']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }


    // Get User by Email (Returns Object)
    public function getUserByEmail($email)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    // Register Google User
    public function registerGoogleUser($data)
    {
        $this->db->query('INSERT INTO users (fullname, email, phone, password, role) VALUES(:name, :email, :phone, :password, :role)');
        // Bind values
        $this->db->bind(':name', $data['fullname']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', ''); // Google users might not have phone initially
        $this->db->bind(':password', $data['password']); // Hashed random password
        $this->db->bind(':role', 'user');

        return $this->db->execute();
    }
}
