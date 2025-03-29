<?php
require_once 'config.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $terms = isset($_POST['terms']) ? true : false;

    // Server-side validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (!$terms) {
        $error = "You must agree to the terms and conditions!";
    } else {
        // Check if username or email already exists
        $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email]);
        if ($stmt->rowCount() > 0) {
            $error = "Username or email already taken!";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if ($stmt->execute([$username, $email, $hashed_password])) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Something went wrong. Please try again!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ottawa Movies - Register</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo"><a href="index.php">Ottawa Movies</a></div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php"><img src="./image/home.png" alt="Home Icon" class="nav-icon"> Home</a></li>
                <li><a href="search.php"><img src="./image/search.png" alt="Search Icon" class="nav-icon"> Search</a></li>
                <li><a href="login.php" class="active"><img src="./image/regestier.png" alt="Login Icon" class="nav-icon"> Login/Register</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <section class="register-section">
            <div class="register-container">
                <h1>Register</h1>
                <?php if (isset($error)): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <form method="POST" action="register.php" id="registerForm">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                    </div>
                    <div class="form-group terms-group">
                        <input type="checkbox" id="terms" name="terms">
                        <label for="terms">I agree to the <a href="#">terms and conditions</a></label>
                    </div>
                    <button type="submit">Register</button>
                </form>
                <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
            </div>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>Copyright@Mahmoud Sharmarke Moustafa 2025</p>
    </footer>

    <script src="register.js"></script>
</body>
</html>