<?php
// Include the database configuration file
include_once '/home/protocol/public_html/inc/DatabaseConfig.php';

// Construct the SQL query to update land based on total land * workers * farmers
$update_query = "UPDATE users SET land = land + (land * worker)";

// Prepare and execute the update query
$stmt = $pdo->prepare($update_query);
$stmt->execute();

// Output a success message
echo "Land updated based on total land * workers successfully.";
