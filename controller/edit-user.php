<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

$db = Database::getInstance()->getConnection();

$error_message = "";
$success_message = "";
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT name, email FROM users WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: manage-clients-merchants.php?error=User not found");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    if ($password) {
        $update_sql = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
        $update_stmt = $db->prepare($update_sql);
        $update_stmt->bind_param("sssi", $name, $email, $password, $user_id);
    } else {
        $update_sql = "UPDATE users SET name = ?, email = ? WHERE id = ?";
        $update_stmt = $db->prepare($update_sql);
        $update_stmt->bind_param("ssi", $name, $email, $user_id);
    }

    if ($update_stmt->execute()) {
        header("Location: manage-clients-merchants.php?success=User updated successfully!");
        exit();
    } else {
        $error_message = "Error: " . $db->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../css/mange_cust&mach.css">
</head>
<body>
    <main>
        <h1>Edit User</h1>
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
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="input-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="input-group">
                        <label for="password">New Password (optional):</label>
                        <input type="password" id="password" name="password">
                    </div>
                    <button type="submit" class="add-btn">Update User</button>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
