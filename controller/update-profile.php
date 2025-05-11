<?php
include '../db.php'; // Include database connection
session_start(); // Start the session to access user data

// Ensure the customer is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Get the logged-in customer's ID (or username, email depending on your design)
$customer_name = $_SESSION['username'];
$customer_email = $_SESSION['email'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updated_name = $_POST['customer-name'];
    $updated_email = $_POST['email'];
    $updated_password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password securely

    // Update query to save changes to the database
    $sql = "UPDATE users SET name = ?, email = ?, password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $updated_name, $updated_email, $updated_password, $customer_email);

    if ($stmt->execute()) {
        // Update session data with new values
        $_SESSION['username'] = $updated_name;
        $_SESSION['email'] = $updated_email;

        // Redirect or show success message
        echo "<script>alert('Profile updated successfully!'); window.location.href = 'customer.php';</script>";
    } else {
        echo "<script>alert('Error updating profile. Please try again later.');</script>";
    }
}
?>