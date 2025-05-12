<?php
session_start();
require_once '../db.php';

$db = Database::getInstance()->getConnection();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit-feedback'])) {
    $customerName = $_POST['name'];
    $feedbackText = $_POST['feedback'];

    if (!empty($customerName) && !empty($feedbackText)) {
        $sql = "INSERT INTO feedback (customer_name, feedback_text) VALUES (?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ss", $customerName, $feedbackText);

        if ($stmt->execute()) {
            header("Location: offer-home.php?success=Feedback submitted successfully!");
            exit();
        } else {
            header("Location: offer-home.php?error=Error submitting feedback. Please try again.");
            exit();
        }
    } else {
        header("Location: offer-home.php?error=Please fill in all fields.");
        exit();
    }
}

$sql = "SELECT customer_name, feedback_text, submitted_at FROM feedback ORDER BY submitted_at DESC";
$result = $db->query($sql);
$feedbacks = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offers Page</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/offer_home.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">loyal</div>
            <ul>
                <li><a href="../controller/customer.php">Back</a></li>
            </ul>
        </nav>
    </header>

    <section class="categories-section">
        <h1>Browse by Category</h1>
        <div class="categories-container">
            <div class="category-card">
                <i class="bi bi-cup-hot"></i>
                <h3><a href="getaway.php">Famous Restaurants</a></h3>
                <p>Explore deals from top dining spots.</p>
            </div>
            <div class="category-card">
                <i class="bi bi-film"></i>
                <h3><a href="getaway.php">Cinemas</a></h3>
                <p>Grab tickets and offers for your favorite movies.</p>
            </div>
            <div class="category-card">
                <i class="bi bi-gift"></i>
                <h3><a href="getaway.php">Gift Cards</a></h3>
                <p>Get exclusive discounts on gift cards.</p>
            </div>
            <div class="category-card">
                <i class="bi bi-laptop"></i>
                <h3><a href="getaway.php">Electronics</a></h3>
                <p>Save big on gadgets and tech.</p>
            </div>
            <div class="category-card">
                <i class="bi bi-handbag"></i>
                <h3><a href="getaway.php">Clothing</a></h3>
                <p>Shop the latest fashion with great deals.</p>
            </div>
        </div>
    </section>

    <section class="offers-section">
        <h1>Our Special Offers</h1>
        <div class="offers-container">
            <div class="offer-card">
                <img src="../image/offer/elec.jpg" alt="Electronics Offer">
                <h3>Up to 60% Off on Electronics</h3>
                <p>Enjoy an up to 60% discount on all electronic products for a limited time!</p>
                <button>Claim Offer</button>
            </div>
            <div class="offer-card">
                <img src="../image/offer/clot.jpg" alt="Clothing Offer">
                <h3>Buy 2 Get 1 Free</h3>
                <p>Special offer on clothing: Buy two items and get another for free! (limited Time)</p>
                <button>Claim Offer</button>
            </div>
            <div class="offer-card">
                <img src="../image/offer/meal.jpeg" alt="Restaurant Offer">
                <h3>Family discounts on Restaurants</h3>
                <p>Bring your Family and get discounts when dining at our restaurants.</p>
                <button>Claim Offer</button>
            </div>
        </div>
    </section>

    <section class="feedback-section">
        <h2>Share Your Feedback</h2>
        <form method="POST" action="offer-home.php" class="feedback-form">
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="form-group">
                <label for="feedback">Your Feedback:</label>
                <textarea id="feedback" name="feedback" placeholder="Write your feedback here..." required></textarea>
            </div>
            <button type="submit" name="submit-feedback">Submit Feedback</button>
        </form>

        <h2>What Our Users Say</h2>
        <div class="feedback-container">
            
            <?php foreach ($feedbacks as $feedback) { ?>
                <div class="feedback-card">
                    <h4><?php echo htmlspecialchars($feedback['customer_name']); ?></h4>
                    <p><?php echo htmlspecialchars($feedback['feedback_text']); ?></p>
                </div>
            <?php } ?>
        </div>
    </section>
</body>
</html>
