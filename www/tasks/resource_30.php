<?php
// Include the database configuration file
include_once '/home/protocol/public_html/inc/DatabaseConfig.php';

// Construct the SQL query to update gold and food for all users
$update_query = "UPDATE users SET gold = gold + (land * worker), food = food + (land * farmer * 0.25)";

// Prepare the SQL statement to update the gold and food columns in the users table.
// This query calculates the new gold gain for each user by multiplying their land and worker values.
// Similarly, it calculates the new food gain for each user by multiplying their land and farmer values by 0.25.
// The results are added to the current gold and food values for each user.
// Adjust the multiplier (0.25 in this case) as needed based on the desired food gain calculation.

// Prepare the SQL statement
$stmt = $pdo->prepare($update_query);

// Execute the update query directly without fetching users
$stmt->execute();

// Output a success message indicating that the food gain has been updated based on farmers successfully.
echo "Food Gain updated based on farmers successfully.";
