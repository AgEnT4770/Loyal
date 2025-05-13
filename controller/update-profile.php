<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

$db = Database::getInstance()->getConnection();
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updated_name = trim($_POST['customer-name']);
    $updated_email = trim($_POST['email']);
    $updated_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    $check_sql = "SELECT id FROM users WHERE email = ? AND id != ?";
    $check_stmt = $db->prepare($check_sql);
    $check_stmt->bind_param("si", $updated_email, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('Email already exists. Please use a different email.'); window.location.href = '../views/customer-profile.php';</script>";
        exit();
    }

    if ($updated_password) {
        $update_sql = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
        $update_stmt = $db->prepare($update_sql);
        $update_stmt->bind_param("sssi", $updated_name, $updated_email, $updated_password, $user_id);
    } else {
        $update_sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $update_stmt = $db->prepare($update_sql);
        $update_stmt->bind_param("ssi", $updated_name, $updated_email, $user_id);
    }

    if ($update_stmt->execute()) {
        $_SESSION['username'] = $updated_name;
        $_SESSION['email'] = $updated_email;

        echo "<script>alert('Profile updated successfully!'); window.location.href = '../views/customer-profile.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error updating profile. Please try again later.'); window.location.href = '../views/customer-profile.php';</script>";
        exit();
    }
}
?>