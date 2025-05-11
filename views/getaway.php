<?php
session_start();
require_once '../db.php'; // Database connection

// Ensure client is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: index.html");
    exit();
}

// Get database connection
$db = Database::getInstance()->getConnection();
$user_id = $_SESSION['user_id'];

// Fetch current loyalty points
$sql = "SELECT loyaltyPoints FROM users WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: offer-home.php?error=User not found");
    exit();
}

$current_points = intval($user['loyaltyPoints']);
$new_points = max(0, $current_points - 500); // Prevent negative points

// Deduct points on checkout
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
    if ($current_points < 500) {
        echo "<script>alert('Insufficient points!'); window.location.href='offer-home.php';</script>";
        exit();
    }

    $update_sql = "UPDATE users SET loyaltyPoints = ? WHERE id = ?";
    $update_stmt = $db->prepare($update_sql);
    $update_stmt->bind_param("ii", $new_points, $user_id);

    if ($update_stmt->execute()) {
        header("Location: offer-home.php?success=Checkout successful! 500 points deducted.");
        exit();
    } else {
        header("Location: offer-home.php?error=Error processing checkout. Please try again.");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment Method</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/getaway.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="offer-home.php">Back</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Payment Method</h1>
        <div class="cards-container">
            <div class="card">
                <div class="card-icon">🖤💙🖤</div>
                <h2>Pay with Points</h2>
                <p>Use your accumulated points to make a payment.</p>
                <p class="requierd-points">
                    <span class="text-s">Total required points</span>: 500
                </p>
                <p class="products">
                    <span class="text-s">Selected products</span>: products
                </p>
                <form class="checkout-point" method="POST">
                    <button type="submit" name="checkout" class="checkout-btn">Checkout</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
