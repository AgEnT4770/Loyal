<?php
session_start();
require_once '../db.php'; // Include database connection

// Ensure the admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=You must log in as an admin!");
    exit();
}

// Get database connection
$db = Database::getInstance()->getConnection();

// Handle feedback deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete-feedback'])) {
    $feedbackId = $_POST['feedback-id'];

    $sql = "DELETE FROM feedback WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $feedbackId);

    if ($stmt->execute()) {
        header("Location: view-feedback.php?success=Feedback deleted successfully!");
        exit();
    } else {
        header("Location: view-feedback.php?error=Error deleting feedback. Please try again.");
        exit();
    }
}

// Fetch feedback from the database
$sql = "SELECT id, customer_name, feedback_text, submitted_at FROM feedback ORDER BY submitted_at DESC";
$result = $db->query($sql);
$feedbacks = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedback</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/feedback.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="admin.php">Back</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="feedback">
            <h2>Customer Feedback</h2>

            <!-- Display Success or Error Messages -->
            <?php if (isset($_GET['success'])) { ?>
                <p class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></p>
            <?php } ?>
            <?php if (isset($_GET['error'])) { ?>
                <p class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php } ?>

            <div class="feedback-box">
                <!-- Dynamically Display Feedback from Database -->
                <?php if (count($feedbacks) > 0) { ?>
                    <?php foreach ($feedbacks as $feedback) { ?>
                        <div class="feedback-item">
                            <div class="feedback-content">
                                <span class="customer-name"><?php echo htmlspecialchars($feedback['customer_name']); ?></span>
                                <p><?php echo htmlspecialchars($feedback['feedback_text']); ?></p>
                            </div>
                            <form method="POST" action="view-feedback.php">
                                <input type="hidden" name="feedback-id" value="<?php echo $feedback['id']; ?>">
                                <button type="submit" name="delete-feedback" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p>No feedback available yet.</p>
                <?php } ?>
            </div>
        </section>
    </main>
</body>
</html>
