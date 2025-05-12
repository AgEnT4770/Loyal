<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

$db = Database::getInstance()->getConnection();

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$check_sql = "SELECT id FROM users WHERE id = ?";
$check_stmt = $db->prepare($check_sql);
$check_stmt->bind_param("i", $user_id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows === 0) {
    header("Location: manage-clients-merchants.php?error=User not found");
    exit();
}

$sql = "DELETE FROM users WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    header("Location: manage-clients-merchants.php?success=User deleted successfully!");
    exit();
} else {
    header("Location: manage-clients-merchants.php?error=Error deleting user. Please try again.");
    exit();
}
?>
