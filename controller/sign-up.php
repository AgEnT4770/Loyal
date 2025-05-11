<?php
include '../db.php'; // Include database connection
include '../models/User.php'; // Include User class
include '../models/Admin.php'; // Include Admin class
include '../models/Merchant.php'; // Include Merchant class
include '../models/Customer.php'; // Include Customer class
session_start(); // Start session for logged-in user handling

$error_message = ""; // Store error messages for duplicate users or other errors

// Get database connection using Singleton pattern
$db = Database::getInstance()->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['user-type'];

    // Check if the username or email already exists
    $check_sql = "SELECT * FROM users WHERE name = ? OR email = ?";
    $check_stmt = $db->prepare($check_sql);
    $check_stmt->bind_param("ss", $name, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $error_message = "The email or username already exists. Please choose another.";
    } else {
        // Create appropriate object based on role
        if ($role === "admin") {
            $newUser = new Admin(null, $name, $email, $password);
        } elseif ($role === "merchant") {
            $newUser = new Merchant(null, $name, $email, $password, $name); // Merchant name same as username for now
        } elseif ($role === "customer") {
            $newUser = new Customer(null, $name, $email, $password, 0, "None"); // Customers start with 0 loyalty points
        } else {
            $error_message = "Invalid role selected.";
            header("Location: sign-up.php?error=" . urlencode($error_message));
            exit();
        }

        // Insert new user into the database
        $sql = "INSERT INTO users (name, email, password, role, subscription, loyaltyPoints, merchant_name) VALUES (?, ?, ?, ?, 'None', 0, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssssi", 
            $newUser->getName(), 
            $newUser->getEmail(), 
            password_hash($password, PASSWORD_DEFAULT), // Hash password securely
            $newUser->getRole(),
            ($role === "customer" ? NULL : $newUser->getName()) // Set merchant_name = name for merchants
);


        if ($stmt->execute()) {
            // Retrieve the newly created user ID and store session variables using getters
            $_SESSION['user_id'] = $db->insert_id;
            $_SESSION['username'] = $newUser->getName();
            $_SESSION['email'] = $newUser->getEmail();
            $_SESSION['role'] = $newUser->getRole();

            header("Location: " . ($newUser->getRole() === "customer" ? "customer.php" : ($newUser->getRole() === "merchant" ? "../views/merchant-dashboard.php" : "../views/admin.php")));
            exit();
        } else {
            $error_message = "An error occurred during signup. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Loyal</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/sign-up.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="signup-container">
        <div class="signup-box">
            <h1 class="logo">Loyal</h1>
            <h2>Sign Up for an Account</h2>

            <!-- Display Error Message -->
            <?php if (!empty($error_message)) { ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php } ?>

            <form method="POST">
                <div class="input-group">
                    <label for="username"><i class="fas fa-user"></i> Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="input-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="input-group">
                    <label for="user-type"><i class="fas fa-users"></i> User Type</label>
                    <select id="user-type" name="user-type" required>
                        <option value="" disabled selected>Select your user type</option>
                        <option value="customer">Customer</option>
                        <option value="merchant">Merchant</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <button type="submit" class="signup-btn">Sign Up</button>
            </form>

            <p class="login-link">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
