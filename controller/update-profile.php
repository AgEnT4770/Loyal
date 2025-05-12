<?php
include '../db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$customer_name = $_SESSION['username'];
$customer_email = $_SESSION['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updated_name = $_POST['customer-name'];
    $updated_email = $_POST['email'];
    $updated_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "UPDATE users SET name = ?, email = ?, password = ? WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $updated_name, $updated_email, $updated_password, $customer_email);

    if ($stmt->execute()) {
        $_SESSION['username'] = $updated_name;
        $_SESSION['email'] = $updated_email;

        echo "<script>alert('Profile updated successfully!'); window.location.href = 'customer.php';</script>";
    } else {
        echo "<script>alert('Error updating profile. Please try again later.');</script>";
    }
}
?>