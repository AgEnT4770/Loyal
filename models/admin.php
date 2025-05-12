<?php
require_once 'User.php';
require_once '../db.php';

class Admin extends User {
    private $db;

    public function __construct($user_id, $name, $email, $password) {
        parent::__construct($user_id, $name, $email, $password, "admin");
        $this->db = Database::getInstance()->getConnection();
    }

    public function manageUsers() {
        return "Admin {$this->name} is managing users.";
    }

    public function viewReports() {
        return "Admin {$this->name} is viewing reports.";
    }

    public function getAllUsers() {
        $sql = "SELECT id, name, email, role FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function deleteUser($userId) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
}
?>
