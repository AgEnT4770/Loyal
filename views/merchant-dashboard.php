<?php
session_start();
require_once '../db.php';
require_once '../models/Merchant.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'merchant') {
    header("Location: login.php");
    exit();
}

$db = Database::getInstance()->getConnection();

$merchantId = $_SESSION['user_id'];
$sql = "SELECT id, merchant_name, email FROM users WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $merchantId);
$stmt->execute();
$result = $stmt->get_result();
$merchantData = $result->fetch_assoc();
$merchantName = $merchantData['merchant_name'];
$merchantEmail = $merchantData['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchant Dashboard - Rewards Platform</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/merchant.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Loyal</div>
            <ul>
                <li><a href="../controller/logout.php">Log out</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Merchant Dashboard</h1>
        <section id="profile-management">
            <h2>Profile Management</h2>
            <p><strong>Merchant ID:</strong> <?php echo htmlspecialchars($merchantId); ?></p>
            <p><strong>Business Name:</strong> <?php echo htmlspecialchars($merchantName); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($merchantEmail); ?></p>
            <p><a href="edit-profile.php">Edit Profile Details</a></p>
        </section>

        <section id="offer-management">
            <h2><a href="offer_mange-merchant.php">Offer Management</a></h2>
            <p>Create and manage offers for customers to earn points.</p>
        </section>

        <section id="report-layout">
            <h2>Report Layout</h2>
            <p>Generate reports to track your performance and points distribution.</p>
        </section>

        <section id="value-based-system">
            <h2><a href="merchant-donation.php">Value-Based System</a></h2>
            <p>Donate to charities using earned points.</p>
        </section>
    </main>

    <footer class="glass-footer">
        <p>Follow us on:</p>
        <ul>
            <li><a href="https://facebook.com" target="_blank"><i class="bi bi-facebook"></i></a></li>
            <li><a href="https://instagram.com" target="_blank"><i class="bi bi-instagram"></i></a></li>
            <li><a href="https://twitter.com" target="_blank"><i class="bi bi-twitter"></i></a></li>
        </ul>
        <p>© 2025 Rewards Platform. All rights reserved.</p>
    </footer>
</body>
</html>
