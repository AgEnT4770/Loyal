<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php?error=You must log in to subscribe!");
    exit();
}

$db = Database::getInstance()->getConnection();
$customerId = $_SESSION['user_id'];
$sql = "SELECT subscription, loyaltyPoints FROM users WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $customerId);
$stmt->execute();
$result = $stmt->get_result();
$customerData = $result->fetch_assoc();

$currentTier = $customerData['subscription'];
$currentPoints = $customerData['loyaltyPoints'];

$tierPoints = [
    "Silver" => 200,
    "Gold" => 500,
    "Platinum" => 1000
];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tier'])) {
    $newTier = $_POST['tier'];

    if (array_key_exists($newTier, $tierPoints) && $tierPoints[$newTier] > $tierPoints[$currentTier]) {
        $newPoints = $currentPoints + $tierPoints[$newTier];
    } else {
        $newPoints = $currentPoints;
    }
    $sql = "UPDATE users SET subscription = ?, loyaltyPoints = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sii", $newTier, $newPoints, $customerId);

    if ($stmt->execute()) {
        header("Location: customer.php?success=Subscribed to $newTier successfully!");
        exit();
    } else {
        header("Location: customer.php?error=Error subscribing. Please try again later.");
        exit();
    }
} else {
    header("Location: customer.php?error=No subscription tier selected!");
    exit();
}
?>
