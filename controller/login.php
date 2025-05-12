<?php
session_start();
include '../db.php';
include '../models/User.php';
include '../models/Admin.php';
include '../models/Merchant.php';
include '../models/Customer.php';
if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    $redirectPage = $_SESSION['role'] === "customer" ? "customer.php" :
                    ($_SESSION['role'] === "merchant" ? "../views/merchant-dashboard.php" : "../views/admin.php");
    header("Location: $redirectPage");
    exit();
}

$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Loyal</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1 class="logo">Loyal</h1>
            <h2>Login to Your Account</h2>

            <?php if (!empty($error_message)) { ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php } ?>

            <form method="POST" action="login-handler.php">
                <div class="input-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="input-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="input-group">
                    <label for="user-type"><i class="fas fa-users"></i> User Type</label>
                    <select id="user-type" name="user-type" required>
                        <option value="customer">Customer</option>
                        <option value="merchant">Merchant</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>

            <p class="signup-link">Don't have an account? <a href="sign-up.php">Sign Up</a></p>
        </div>
    </div>
</body>
</html>
