<?php
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit(); // Stop further execution
}

// Include the database configuration file
require_once './inc/DatabaseConfig.php';

// Include functions file
require_once './inc/Functions.php';

// Fetch user details from the database
$userstats_query = "SELECT id, username, gold, land, land_level, worker, farmer FROM users WHERE username = ?";
$stmt = $pdo->prepare($userstats_query);
$stmt->execute([$_SESSION['username']]);
$userstats = $stmt->fetch(PDO::FETCH_ASSOC);

// Include header
include_once 'Header.php';
?>

<?php
// Call the displayStats function
displayStats($userstats);
?>

<?php
// Include footer
include_once 'Footer.php';
?>
