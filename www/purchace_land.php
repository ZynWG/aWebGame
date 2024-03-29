<?php
session_start();

require_once './inc/DatabaseConfig.php';
require_once './inc/Functions.php';

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve user information from session
$username = $_SESSION['username'];

// Retrieve user statistics securely from the database using the function
$userstats = getLandStats($pdo, $username);

// Initialize error and success messages
$error_message = getLandPurchaseError(); // Retrieve any existing error message
$success_message = getLandPurchaseSuccess(); // Retrieve any existing success message

// Clear error and success messages to prevent displaying stale messages
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);

// Handle form submission to buy land
if (isset($_POST['submit'])) {
    // Input validation
    $acres = isset($_POST['landacres']) ? intval($_POST['landacres']) : 0;

    // Call the function to handle land purchase securely
    handleLandPurchase($pdo, $userstats, $acres);

    // Retrieve error or success message after form submission
    $error_message = getLandPurchaseError();
    $success_message = getLandPurchaseSuccess();
}

// Include Header
include_once 'Header.php';
?>

<section id="buy-land">
    <div class="container">
        <h2 class="mt-4">Buy Land</h2>
        <?php if (!empty($error_message)) { ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php } elseif (!empty($success_message)) { ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($success_message) ?>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-md-6">
                <p>100 Gold Coins, Each Acre</p>
                
                <form method="post" action="">
                    <div class="form-group">
                        <label for="landacres">Enter Amount</label>
                        <input type="number" class="form-control" name="landacres" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Buy Land</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include_once 'Footer.php';
?>
