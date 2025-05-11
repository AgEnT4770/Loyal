<?php
class Database {
    private $host = "localhost"; // Change if needed
    private $username = "root"; // Your DB username
    private $password = ""; // Your DB password
    private $dbname = "loyalty_db"; // Your database name
    private $conn;
    
    // Singleton pattern: Ensure only one connection instance
    private static $instance = null;

    private function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
    }

    // Get the database instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Get the connection
    public function getConnection() {
        return $this->conn;
    }
}

// Usage example:
// $db = Database::getInstance()->getConnection();
?>
