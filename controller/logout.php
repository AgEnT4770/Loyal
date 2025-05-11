<?php
session_start(); // Start the session

// Regenerate session ID to prevent session fixation attacks
session_regenerate_id(true);

// Clear all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: ../index.html");
exit();
?>
