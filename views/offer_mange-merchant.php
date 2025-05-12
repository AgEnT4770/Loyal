<?php
session_start();
require_once '../db.php';
require_once '../models/Merchant.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'merchant') {
    header("Location: login.php");
    exit();
}

$db = Database::getInstance()->getConnection();
ob_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add-offer'])) {
    $offerDetails = $_POST['offer-details'];
    $rating = $_POST['rating'];

    $insertSql = "INSERT INTO offers (merchant_id, offer_details, rating) VALUES (?, ?, ?)";
    $insertStmt = $db->prepare($insertSql);
    $insertStmt->bind_param("isi", $_SESSION['user_id'], $offerDetails, $rating);
    if ($insertStmt->execute()) {
        header("Location: offer_mange-merchant.php?success=Offer added successfully");
        exit();
    } else {
        header("Location: offer_mange-merchant.php?error=Error adding offer. Please try again.");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete-offer'])) {
    $offerId = $_POST['offer-id'];

    $deleteSql = "DELETE FROM offers WHERE id = ?";
    $deleteStmt = $db->prepare($deleteSql);
    $deleteStmt->bind_param("i", $offerId);
    if ($deleteStmt->execute()) {
        header("Location: offer_mange-merchant.php?success=Offer deleted successfully");
        exit();
    } else {
        header("Location: offer_mange-merchant.php?error=Error deleting offer. Please try again.");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update-offer'])) {
    $offerId = $_POST['offer-id'];
    $newOfferDetails = $_POST['offer-details'];
    $newRating = $_POST['rating'];

    $updateSql = "UPDATE offers SET offer_details = ?, rating = ? WHERE id = ?";
    $updateStmt = $db->prepare($updateSql);
    $updateStmt->bind_param("sii", $newOfferDetails, $newRating, $offerId);
    if ($updateStmt->execute()) {
        header("Location: offer_mange-merchant.php?success=Offer updated successfully");
        exit();
    } else {
        header("Location: offer_mange-merchant.php?error=Error updating offer. Please try again.");
        exit();
    }
}

$merchantId = $_SESSION['user_id'];
$sql = "SELECT id, offer_details, rating FROM offers WHERE merchant_id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $merchantId);
$stmt->execute();
$result = $stmt->get_result();
$offers = $result->fetch_all(MYSQLI_ASSOC);
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Management</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/offer_manage-merchant.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="merchant-dashboard.php">Back</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section id="merchants">
            <h2>Offers</h2>
            
            <?php if (isset($_GET['success'])) { ?>
                <p class="success-message"><?php echo htmlspecialchars($_GET['success']); ?></p>
            <?php } ?>
            <?php if (isset($_GET['error'])) { ?>
                <p class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></p>
            <?php } ?>

            <div class="add-offer-container">
                <form method="POST" action="offer_mange-merchant.php">
                    <input type="text" name="offer-details" placeholder="Enter offer details" required>
                    <input type="number" name="rating" placeholder="Rating (1-5)" min="1" max="5" required>
                    <button type="submit" name="add-offer" class="add-merchant-btn">Add Offer</button>
                </form>
            </div>

            <div class="merchant-box">
                <?php foreach ($offers as $offer) { ?>
                    <div class="merchant-item">
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($offer['offer_details']); ?> | <strong>Rating:</strong> <?php echo htmlspecialchars($offer['rating']); ?></p>
                        <div class="merchant-actions">
                            <form method="POST" action="offer_mange-merchant.php">
                                <input type="hidden" name="offer-id" value="<?php echo $offer['id']; ?>">
                                <input type="text" name="offer-details" placeholder="Update offer details" required>
                                <input type="number" name="rating" placeholder="Rating (1-5)" min="1" max="5" required>
                                <button type="submit" name="update-offer" class="update-btn">Update</button>
                            </form>
                            <form method="POST" action="offer_mange-merchant.php">
                                <input type="hidden" name="offer-id" value="<?php echo $offer['id']; ?>">
                                <button type="submit" name="delete-offer" class="delete-btn">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </section>
    </main>
</body>
</html>
