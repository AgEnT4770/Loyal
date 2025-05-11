<?php
session_start(); // Start the session
include '../db.php'; // Include database connection
include '../models/User.php'; // Include User class
include '../models/Admin.php'; // Include Admin class
include '../models/Merchant.php'; // Include Merchant class
include '../models/Customer.php'; // Include Customer class

// Get database connection using Singleton pattern
$db = Database::getInstance()->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['user-type'];

    // Fetch user from database
    $sql = "SELECT * FROM users WHERE name = ? AND role = ?";
    $stmt = $db->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $db->error);
    }

    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();

        // Create appropriate object based on role
        if ($role === "admin") {
            $user = new Admin($userData['id'], $userData['name'], $userData['email'], $userData['password']);
        } elseif ($role === "merchant") {
            $user = new Merchant($userData['id'], $userData['name'], $userData['email'], $userData['password'], $userData['merchant_name']);
        } elseif ($role === "customer") {
            $user = new Customer($userData['id'], $userData['name'], $userData['email'], $userData['password'], $userData['loyaltyPoints'], $userData['subscription']);
        } else {
            $error_message = "Invalid role selected.";
            header("Location: login.php?error=" . urlencode($error_message));
            exit();
        }

        // Verify password using encapsulated method
        if ($user->verifyPassword($password)) {
            // Set session variables securely using getters
            $_SESSION['user_id'] = $user->getUserId();
            $_SESSION['username'] = $user->getName();
            $_SESSION['email'] = $user->getEmail();
            $_SESSION['role'] = $user->getRole();

            // Redirect based on role
            header("Location: " . ($user->getRole() === "customer" ? "customer.php" : ($user->getRole() === "merchant" ? "../views/merchant-dashboard.php" : "../views/admin.php")));
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    } else {
        $error_message = "Invalid username or password.";
    }

    // Redirect back to login with error message
    header("Location: login.php?error=" . urlencode($error_message));
    exit();
}
?>
