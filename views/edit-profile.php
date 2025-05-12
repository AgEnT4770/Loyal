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
$sql = "SELECT merchant_name, email FROM users WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $merchantId);
$stmt->execute();
$result = $stmt->get_result();
$merchantData = $result->fetch_assoc();
$merchantName = $merchantData['merchant_name'];
$merchantEmail = $merchantData['email'];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newMerchantName = $_POST['business-name'];
    $newEmail = $_POST['email'];
    $newPassword = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    if ($newPassword) {
        $updateSql = "UPDATE users SET merchant_name = ?, email = ?, password = ? WHERE id = ?";
        $updateStmt = $db->prepare($updateSql);
        $updateStmt->bind_param("sssi", $newMerchantName, $newEmail, $newPassword, $merchantId);
    } else {
        $updateSql = "UPDATE users SET merchant_name = ?, email = ? WHERE id = ?";
        $updateStmt = $db->prepare($updateSql);
        $updateStmt->bind_param("ssi", $newMerchantName, $newEmail, $merchantId);
    }

    if ($updateStmt->execute()) {
        $_SESSION['merchant_name'] = $newMerchantName;
        $_SESSION['email'] = $newEmail;
        header("Location: merchant-dashboard.php?success=Profile updated successfully");
        exit();
    } else {
        $error_message = "Error updating profile. Please try again.";
    }
}

if (isset($_POST['delete-account'])) {
    $deleteSql = "DELETE FROM users WHERE id = ?";
    $deleteStmt = $db->prepare($deleteSql);
    $deleteStmt->bind_param("i", $merchantId);
    if ($deleteStmt->execute()) {
        session_unset();
        session_destroy();
        header("Location: login.php?success=Account deleted successfully");
        exit();
    } else {
        $error_message = "Error deleting account. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/edit_profile.css">
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
        <h1>Edit Profile</h1>
        
        <?php if (!empty($error_message)) { ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php } ?>

        <form id="edit-profile-form" method="POST">
            <div class="form-group">
                <label for="business-name">Business Name:</label>
                <input type="text" id="business-name" name="business-name" value="<?php echo htmlspecialchars($merchantName); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($merchantEmail); ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Change password">
            </div>
            <button type="submit" class="save-btn">Save Changes</button>
        </form>

        <form method="POST">
            <div class="delete-account">
                <button type="submit" name="delete-account" class="delete-btn" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">Delete Account</button>
            </div>
        </form>
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
