<?php
session_start();
include 'db.php';

$error = "";

if(isset($_POST['login'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = $_POST['password'];

    // 1. Check for the Fixed Admin Password
    if ($user === 'admin' && $pass === 'admin@123') {
        $_SESSION['username'] = 'admin';
        $_SESSION['role'] = 'admin'; // Mark as admin for the dashboard
        header("Location: admin.php");
        exit();
    }

    // 2. Regular User Login Logic
    $sql = "SELECT * FROM users WHERE username='$user'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($pass, $row['password'])) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        // Set role to 'user' or whatever is in the DB
        $_SESSION['role'] = isset($row['role']) ? $row['role'] : 'user';
        
        header("Location: feed.php");
        exit();
    } else {
        $error = "Oops! Those details don't match our records.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | ReadToRest</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="navbar">
    <div class="logo">ReadTo<span>Rest</span></div>
    <nav>
        <a href="feed.php">Library</a>
        <a href="register.php">Join Now</a>
    </nav>
</header>

<div class="auth-container">
    <form method="POST" class="auth-form">
        <div class="welcome-icon">🌙</div>
        <h2>Welcome Back</h2>
        <p>Your stories are waiting for you.</p>

        <?php if($error != ""): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        
        <button type="submit" name="login">Sign In</button>
        
        <p class="switch-auth">New to ReadToRest? <a href="register.php">Create an account</a></p>
    </form>
</div>

</body>
</html>