<?php
session_start();

// Check if user is already logged in, redirect to dashboard if logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

include_once './inc/DatabaseConfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate username and password
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $registrationError = "Username can only contain letters, numbers, and underscores.";
        } elseif (strlen($password) < 8) {
            $registrationError = "Password must be at least 8 characters long.";
        } else {
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $existingUser = $stmt->fetch();

            if ($existingUser) {
                $registrationError = "Username already exists.";
            } else {
                // Hash the password using bcrypt
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Insert new user into database
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                if ($stmt->execute([$username, $hashedPassword])) {
                    // Redirect to login page after successful registration
                    header("Location: login.php");
                    exit();
                } else {
                    $registrationError = "Registration failed. Please try again later.";
                }
            }
        }
    } else {
        $registrationError = "Please provide both username and password.";
    }
}


include_once 'header.php';

?>


    <h2>Register</h2>
    <?php if(isset($registrationError)) { ?>
        <p><?php echo htmlspecialchars($registrationError); ?></p>
    <?php } ?>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" pattern="[a-zA-Z0-9_]+" required><br>
        <input type="password" name="password" placeholder="Password" minlength="8" required><br>
        <small>Password must be at least 8 characters long.</small><br>
        <button type="submit">Register</button>
    </form>
<?php include_once 'footer.php'; ?>