<?php
session_start();

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();
}

// Redirect to the login page after logout
header("Location: login.php");
exit();