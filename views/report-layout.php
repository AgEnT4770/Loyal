<?php
session_start();
require_once '../db.php'; // Include database connection

// Ensure admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit();
}

// Get database connection
$db = Database::getInstance()->getConnection();

// Fetch users (customers & merchants) with emails, roles, and loyalty points
$sql = "SELECT name, email, role, loyaltyPoints FROM users";
$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Layout</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Loyal</div>
            <ul>
                <li><a href="admin.php">Back</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h1>Report Layout</h1>
        <section class="glass-card">
            <h2>Users & Loyalty Points</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Loyalty Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['loyaltyPoints']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No users found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>© 2025 Rewards Platform. All rights reserved.</p>
    </footer>
</body>
</html>
