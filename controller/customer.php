<?php
// Start session to manage logged-in customer data
session_start();
require_once '../db.php'; // Include database connection

// Redirect to login if customer is not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit();
}

// Get database connection
$db = Database::getInstance()->getConnection();

// Fetch customer details
$customerId = $_SESSION['user_id'];
$sql = "SELECT name, email, loyaltyPoints, subscription FROM users WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $customerId);
$stmt->execute();
$result = $stmt->get_result();
$customerData = $result->fetch_assoc();

// Assign customer details
$username = $customerData['name'];
$email = $customerData['email'];
$loyaltyPoints = $customerData['loyaltyPoints'];
$subscription = $customerData['subscription'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/customer.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo">Loyal</div>
            <ul>
                <li><a href="logout.php">Log out</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Personalized Greeting -->
        <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>

        <!-- Profile Management Section -->
        <section id="profile-management">
            <h2>Profile Management</h2>
            <p>Email: <?php echo htmlspecialchars($email); ?></p>
            <p>Points: <?php echo htmlspecialchars($loyaltyPoints); ?></p>
            <p><a href="../views/customer-profile.php">Edit Profile Details</a></p>
        </section>

        <!-- Points Accumulation Section -->
        <section id="points-accumulation">
            <h2>Points Accumulation</h2>
            <p>Total Points: <?php echo htmlspecialchars($loyaltyPoints); ?> | Redeem Points: 100</p>
        </section>

        <!-- Tiered System Section -->
        <section id="tiered-system">
            <h2>Tiered System</h2>
            <p>Current Subscription: <strong><?php echo htmlspecialchars($subscription); ?></strong></p>
            <div class="tier-boxes">
                <div class="tier-box silver">
                    <h3>Silver</h3>
                    <p>Basic rewards with 200 points.</p>
                    <form method="POST" action="subscribe.php">
                        <button type="submit" name="tier" value="Silver" class="subscribe-btn">Subscribe</button>
                    </form>
                </div>
                <div class="tier-box gold">
                    <h3>Gold</h3>
                    <p>Unlock premium rewards with 500 points.</p>
                    <form method="POST" action="subscribe.php">
                        <button type="submit" name="tier" value="Gold" class="subscribe-btn">Subscribe</button>
                    </form>
                </div>
                <div class="tier-box platinum">
                    <h3>Platinum</h3>
                    <p>Exclusive benefits with 1000 points.</p>
                    <form method="POST" action="subscribe.php">
                        <button type="submit" name="tier" value="Platinum" class="subscribe-btn">Subscribe</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Subscription System Section -->
        <section id="subscription">
            <h2>Newsletter Subscription</h2>
            <p>Choose your subscription plan:</p>
            <div class="tier-boxes">
                <div class="tier-box silver">
                    <h3>Silver</h3>
                    <p>Earn 20 points/month.</p>
                    <form method="POST" action="subscribe.php">
                        <input type="hidden" name="tier" value="Silver">
                        <button type="submit" class="subscribe-btn">Subscribe</button>
                    </form>
                </div>
                <div class="tier-box gold">
                    <h3>Gold</h3>
                    <p>Earn 50 points/month.</p>
                    <form method="POST" action="subscribe.php">
                        <input type="hidden" name="tier" value="Gold">
                        <button type="submit" class="subscribe-btn">Subscribe</button>
                    </form>
                </div>
                <div class="tier-box platinum">
                    <h3>Platinum</h3>
                    <p>Earn 100 points/month.</p>
                    <form method="POST" action="subscribe.php">
                        <input type="hidden" name="tier" value="Platinum">
                        <button type="submit" class="subscribe-btn">Subscribe</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Value-Based System Section -->
        <section id="value-based-system">
            <h2><a href="../views/value-based-customer.php">Value-Based System</a></h2>
            <p>Donate points to charity organizations.</p>
        </section>

        <!-- Categories Section -->
        <section id="categories">
            <h2><a href="../views/offer-home.php">Categories</a></h2>
            <p>Explore rewards in various categories.</p>
        </section>

        <!-- Offers Section -->
        <section id="offers">
            <h2><a href="../views/offer-home.php">Best Offers</a></h2>
            <p>Check out the best offers from all categories.</p>
        </section>
    </main>

    <!-- Footer -->
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
