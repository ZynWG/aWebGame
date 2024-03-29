<?php
session_start();

require_once './inc/Functions.php'; // Include Functions.php file
require_once './inc/DatabaseConfig.php'; // Include database configuration file

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user information from session
$username = $_SESSION['username'];

// Retrieve user statistics securely from the database
$userstats = getLandStats($pdo, $username);

// Initialize error and success messages
$error_message = "";
$success_message = "";

// Handle form submission to buy workers and farmers
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    // Sanitize and validate input
    $workerQty = isset($_POST['worker']) ? filter_var($_POST['worker'], FILTER_VALIDATE_INT) : 0;
    $farmerQty = isset($_POST['farmer']) ? filter_var($_POST['farmer'], FILTER_VALIDATE_INT) : 0;

    // Perform validation
    if (($workerQty !== false || $farmerQty !== false) && $workerQty >= 0 && $farmerQty >= 0) {
        // Calculate total cost for workers and farmers
        $workerCost = $workerQty * 75; // Assume worker cost is 75 gold each
        $farmerCost = $farmerQty * 50; // Assume farmer cost is 50 gold each
        $totalCost = $workerCost + $farmerCost;

        // Check if the user has enough gold
        if ($userstats['gold'] < $totalCost) {
            $error_message = "You do not have enough gold to buy that many workers and farmers.";
        } else {
            // Update user's gold and units using prepared statement
            $update_query = "UPDATE users SET gold = gold - ?, worker = worker + ?, farmer = farmer + ? WHERE id = ?";
            $stmt = $pdo->prepare($update_query);
            $success = $stmt->execute([$totalCost, $workerQty, $farmerQty, $userstats['id']]);

            if ($success) {
                // Update userstats array and set success message
                $userstats['gold'] -= $totalCost;
                $userstats['worker'] += $workerQty;
                $userstats['farmer'] += $farmerQty;
                $success_message = "You hired $workerQty workers and $farmerQty farmers. Remaining Gold: {$userstats['gold']} gold.";
            } else {
                $error_message = "Could not buy workers and farmers. Please try again later.";
            }
        }
    } else {
        $error_message = "Invalid input. Please enter a non-negative integer value for workers and farmers.";
    }
}




// Include header
include_once 'Header.php';
?>

<section id="typography">
    <div class="container">
        <h2 class="mt-4">Hire Workers and Farmers</h2>
        <div class="row">
            <div class="col-md-6">
                <form method="post" action="">
                    <?php if (!empty($error_message)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($error_message) ?>
                        </div>
                    <?php elseif (!empty($success_message)) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= htmlspecialchars($success_message) ?>
                        </div>
                    <?php endif; ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Unit Type</th>
                                    <th>Number of Units</th>
                                    <th>Gold Cost</th>
                                    <th>Buy</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Worker</td>
                                    <td><?= number_format($userstats['worker']) ?></td>
                                    <td>75 gold each</td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" class="form-control" name="worker" min="0">
                                            <label for="worker">Workers</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Farmer</td>
                                    <td><?= number_format($userstats['farmer']) ?></td>
                                    <td>50 gold each</td>
                                    <td>
                                        <div class="form-group">
                                            <input type="number" class="form-control" name="farmer" min="0">
                                            <label for="farmer">Farmers</label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Hire Staff</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include_once 'Footer.php';
?>
