<?php
session_start();
require_once '../db.php'; // Database connection

// Ensure admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

// Get database connection
$db = Database::getInstance()->getConnection();

$error_message = "";
$success_message = "";
$role = isset($_GET['role']) ? $_GET['role'] : "customer";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email or name already exists
    $check_sql = "SELECT id FROM users WHERE name = ? OR email = ?";
    $check_stmt = $db->prepare($check_sql);
    $check_stmt->bind_param("ss", $name, $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $error_message = "The username or email already exists.";
    } else {
        // Insert new user
        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            header("Location: manage-clients-merchants.php?success=" . ucfirst($role) . " added successfully!");
            exit();
        } else {
            $error_message = "Error: " . $db->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="../css/mange_cust&mach.css">
</head>
<body>
    <main>
        <h1>Add <?php echo ucfirst($role); ?></h1>
        <section>
            <div class="glass-card">
                <?php if (!empty($error_message)) { ?>
                    <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
                <?php } ?>
                <?php if (!empty($success_message)) { ?>
                    <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
                <?php } ?>

                <form method="POST">
                    <div class="input-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="input-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="input-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="add-btn">Add <?php echo ucfirst($role); ?></button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
