<?php
session_start();
require_once '../db.php';
require_once '../models/User.php';
require_once '../models/Admin.php';
require_once '../models/Merchant.php';
require_once '../models/Customer.php';

$db = Database::getInstance()->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['user-type']);

    $sql = "SELECT id, name, email, password, role, merchant_name, loyaltyPoints, subscription FROM users WHERE name = ? AND role = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();

        if (!password_verify($password, $userData['password'])) {
            header("Location: login.php?error=" . urlencode("Invalid username or password."));
            exit();
        }

        if ($role === "admin") {
            $user = new Admin($userData['id'], $userData['name'], $userData['email'], $userData['password']);
        } elseif ($role === "merchant") {
            $user = new Merchant($userData['id'], $userData['name'], $userData['email'], $userData['password'], $userData['merchant_name']);
        } elseif ($role === "customer") {
            $user = new Customer($userData['id'], $userData['name'], $userData['email'], $userData['password'], $userData['loyaltyPoints'], $userData['subscription']);
        } else {
            header("Location: login.php?error=" . urlencode("Invalid role selected."));
            exit();
        }

        $_SESSION['user_id'] = $user->getUserId();
        $_SESSION['username'] = $user->getName();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['role'] = $user->getRole();

        $redirect_url = ($user->getRole() === "customer") ? "customer.php" : ($user->getRole() === "merchant" ? "../views/merchant-dashboard.php" : "../views/admin.php");
        header("Location: $redirect_url");
        exit();
    }

    header("Location: login.php?error=" . urlencode("Invalid username or password."));
    exit();
}
?>