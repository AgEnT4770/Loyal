<?php

class User {
    protected $user_id;
    protected $name;
    protected $email;
    protected $password;
    protected $role;

    public function __construct($user_id, $name, $email, $password, $role) {
        $this->user_id = $user_id;
        $this->name = $name;
        $this->email = $email;
        $this->setPassword($password);
        $this->role = $role;
    }

    public function getUserId() { return $this->user_id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getRole() { return $this->role; }

    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function setName($name) { $this->name = $name; }
    
    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }
}
?>
