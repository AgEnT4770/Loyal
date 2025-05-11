<?php
require_once 'User.php'; // Ensure User class is included
require_once '../db.php'; // Include Database class

class Admin extends User {
    private $db;

    public function __construct($user_id, $name, $email, $password) {
        parent::__construct($user_id, $name, $email, $password, "admin");
        $this->db = Database::getInstance()->getConnection(); // Get DB connection
    }

    // Admin-specific functionality
    public function manageUsers() {
        return "Admin {$this->name} is managing users.";
    }

    public function viewReports() {
        return "Admin {$this->name} is viewing reports.";
    }

    // Fetch all users from the database
    public function getAllUsers() {
        $sql = "SELECT id, name, email, role FROM users";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Delete a user from the database
    public function deleteUser($userId) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        return $stmt->execute();
    }
}
?>
