<?php
session_start();

// Check if user is already logged in, redirect to dashboard if logged in
if (isset($_SESSION['user_id'])) {
    echo "User already logged in. Redirecting to dashboard...";
    header("Location: dashboard.php");
    exit();
}

// Include database configuration
include_once './inc/DatabaseConfig.php';

// Initialize login error variable
$loginError = '';

// Login process
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['username'], $_POST['password'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        // Prepare and execute the SQL query with placeholders to prevent SQL injection
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Verify password if user exists
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables and redirect to the dashboard
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            echo "Login successful. Redirecting to dashboard...";
            header("Location: dashboard.php");
            exit();
        } else {
            // Display error message if login fails
            $loginError = "Invalid username or password";
        }
    } else {
        $loginError = "Please provide both username and password";
    }
}
include_once 'Header.php'; 
?>

    <h2>Login</h2>
    <?php if(!empty($loginError)) { ?>
        <p><?php echo htmlspecialchars($loginError); ?></p>
    <?php } ?>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
<?php include_once 'Footer.php'; ?>