<?php
session_start();

$customer_name = isset($_SESSION['username']) ? $_SESSION['username'] : "Customer Name";
$customer_email = isset($_SESSION['email']) ? $_SESSION['email'] : "customer@example.com";
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
                <li><a href="../controller/customer.php">Back</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Edit Profile</h1>
        <section class="temp">
            <form id="edit-profile-form" method="POST" action="../controller/update-profile.php">
                <div class="form-group">
                    <label for="customer-name">Customer Name:</label>
                    <input type="text" id="customer-name" name="customer-name" value="<?php echo htmlspecialchars($customer_name); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($customer_email); ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" placeholder="Change password" required>
                </div>
                <button type="submit" class="save-btn">Save Changes</button>

                <div class="delete-account">
                    <button type="button" class="delete-btn" onclick="deleteAccount(<?php echo htmlspecialchars($customer_email); ?>)">Delete Account</button>
                </div>
            </form>
        </section>
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

    <script>
        function deleteAccount(email) {
            if (confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
                window.location.href = "delete-user.php?email=" + encodeURIComponent(email);
            }
        }
    </script>
</body>
</html>