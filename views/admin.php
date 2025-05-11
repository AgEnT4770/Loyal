<?php
session_start();
require_once '../db.php'; // Include database connection

// Ensure the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=You must log in as an admin!");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">LOYAL</div>
            <ul>
                <li><a href="../controller/logout.php">Log out</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Admin Dashboard</h1>

        <section id="dashboard" class="glass-card">
            <h2><a href="../views/dashboard_overview.php">Dashboard Overview</a></h2>
            <p>Monitor customer and merchant registrations.</p>
        </section>

        <section id="report-layout" class="glass-card">
            <h2><a href="../views/report-layout.php">Report Layout</a></h2>
            <p>View customer and merchant loyalty points.</p>
        </section>

        <section id="manage-clients-merchants" class="glass-card">
            <h2><a href="../controller/manage-clients-merchants.php">Manage Clients & Merchants</a></h2>
            <p>Search, add, edit, or delete client and merchant accounts.</p>
        </section>

        <section id="view-feedback" class="glass-card">
            <h2><a href="view-feedback.php">View Feedback</a></h2>
            <p>Feedback submitted by customers.</p>
        </section>
    </main>

    <footer class="glass-footer">
        <p>Follow us on:</p>
        <ul>
            <li><a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
            <li><a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a></li>
            <li><a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a></li>
        </ul>
        <p>© 2025 Rewards Platform. All rights reserved.</p>
    </footer>
</body>
</html>
