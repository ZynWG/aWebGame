<?php
// Include the database configuration file
include_once '/home/protocol/public_html/inc/DatabaseConfig.php';

// Construct the SQL query to update gold for all users
$update_query = "UPDATE users SET gold = gold + (land * worker)";

// Prepare the SQL statement to update the gold column in the users table.
// This query calculates the new gold gain for each user by multiplying their land and worker values.
// The results are added to the current gold value for each user.

// Prepare the SQL statement
$stmt = $pdo->prepare($update_query);

// Execute the update query directly without fetching users
$stmt->execute();

// Output a success message indicating that the gold has been updated based on land * workers successfully.
echo "Gold updated based on land * workers successfully.";
