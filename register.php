<?php 
include 'db.php'; 

$message = "";

if(isset($_POST['register'])) {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    // Password hashing makes it unreadable to hackers
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username already exists
    $checkUser = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    
    if(mysqli_num_rows($checkUser) > 0) {
        $message = "Username already taken!";
    } else {
        $sql = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";
        if(mysqli_query($conn, $sql)) {
            $message = "Account created! <a href='login.php'>Login here</a>";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Join ReadToRest</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="navbar">
    <div class="logo">ReadTo<span>Rest</span></div>
    <nav>
        <a href="feed.php">Library</a>
        <a href="register.php" class="btn-login">Join Now</a>
    </nav>
</header>

<div class="auth-container">
    <form method="POST" class="auth-form">
        <h2>Create Account</h2>
        <p>Join our community of quiet readers.</p>
        
        <?php if($message != ""): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>

        <input type="text" name="username" placeholder="Choose a Username" required>
        <input type="password" name="password" placeholder="Create a Password" required>
        
        <button type="submit" name="register">Sign Up</button>
        
        <p class="switch-auth">Already have an account? <a href="login.php">Log In</a></p>
    </form>
</div>

</body>
</html>