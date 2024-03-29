<?php

require_once './inc/DatabaseConfig.php';

// Function to display user statistics securely
function displayStats($userstats) {
    ?>
    <section id="stats">
        <h2 class="mt-4">User Statistics</h2>
        <div class="card">
            <div class="card-body">
                <p class="card-text">Welcome, <strong><?= htmlspecialchars($userstats['username']) ?></strong>!</p>
                <div class="row">
                    <div class="col-md-6">
                        <p class="card-text"><strong>Gold:</strong> <?= htmlspecialchars($userstats['gold']) ?></p>
                        <p class="card-text"><strong>Acres:</strong> <?= htmlspecialchars($userstats['land']) ?></p>
                        <p class="card-text"><strong>Land Level:</strong> <?= htmlspecialchars($userstats['land_level']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="card-text"><strong>Workers:</strong> <?= htmlspecialchars($userstats['worker']) ?></p>
                        <p class="card-text"><strong>Farmers:</strong> <?= htmlspecialchars($userstats['farmer']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
}

// Function to retrieve user land statistics securely
function getLandStats($pdo, $username) {
    $userstats_query = "SELECT id, username, gold, land, land_level, worker, farmer FROM users WHERE username = ?";
    $stmt = $pdo->prepare($userstats_query);
    $stmt->execute([$username]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// Function to handle land purchase securely
function handleLandPurchase($pdo, &$userstats, $acres) {
    // Validate and sanitize input
    if ($acres <= 0) {
        setLandPurchaseError("Invalid number of acres.");
        return;
    }

    // Calculate the total cost of land
    $totalCost = $acres * 100; // Assume land cost is 100 gold per acre

    // Check if the user has enough gold to make the purchase
    if ($userstats['gold'] < $totalCost) {
        setLandPurchaseError("You do not have enough gold to buy $acres acres of land.");
        return;
    }

    // If the user has enough gold, update the database with the new statistics using prepared statements
    $update_query = "UPDATE users SET gold = gold - ?, land = land + ? WHERE id = ?";
    $stmt = $pdo->prepare($update_query);
    // Bind parameters to prevent SQL injection
    $stmt->bindParam(1, $totalCost, PDO::PARAM_INT);
    $stmt->bindParam(2, $acres, PDO::PARAM_INT);
    $stmt->bindParam(3, $userstats['id'], PDO::PARAM_INT);

    // Execute the update query
    $success = $stmt->execute();

    // If the update was successful, update the userstats array and set a success message
    if ($success) {
        $userstats['gold'] -= $totalCost;
        $userstats['land'] += $acres;
        setLandPurchaseSuccess("You purchased $acres acres of land. Remaining Gold: {$userstats['gold']}.");
    } else {
        // If the update failed, set an error message
        setLandPurchaseError("Could not buy land. Please try again later.");
    }
}

// Function to set land purchase error message securely
function setLandPurchaseError($message) {
    $_SESSION['error_message'] = $message;
}

// Function to set land purchase success message securely
function setLandPurchaseSuccess($message) {
    $_SESSION['success_message'] = $message;
}

// Function to get land purchase error securely
function getLandPurchaseError() {
    if (isset($_SESSION['error_message'])) {
        $error_message = $_SESSION['error_message'];
        unset($_SESSION['error_message']); // Clear the error message after retrieving it
        return $error_message;
    }
    return ""; // Return an empty string if no error message is set
}

// Function to get land purchase success message securely
function getLandPurchaseSuccess() {
    if (isset($_SESSION['success_message'])) {
        $success_message = $_SESSION['success_message'];
        unset($_SESSION['success_message']); // Clear the success message after retrieving it
        return $success_message;
    }
    return ""; // Return an empty string if no success message is set
}