<?php
session_start();
include 'db.php';

$display_msg = "";

if(isset($_POST['send'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']); 
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // This now actually saves the feedback!
    $sql = "INSERT INTO contacts (name, message) VALUES ('$name', '$message')";
    
    if(mysqli_query($conn, $sql)) {
        $display_msg = "Thank you, " . $name . "! Your whisper has reached the library.";
    } else {
        $display_msg = "The library is full... try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact | ReadToRest</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Lively Animations */
        .auth-container {
            animation: fadeIn 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-form button:hover {
            transform: scale(1.05);
            background-color: #ff4d4d;
            transition: 0.3s;
        }

        textarea {
            border: 2px solid transparent;
            transition: 0.3s;
        }

        textarea:focus {
            border-color: #f39c12;
            outline: none;
        }
    </style>
</head>
<body>

<header class="navbar">
    <div class="logo"><a href="feed.php" style="text-decoration:none; color:inherit;">ReadTo<span>Rest</span></a></div>
    <nav>
        <?php if (
            (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') || 
            (isset($_SESSION['username']) && $_SESSION['username'] === 'admin')
        ): ?>
            <a href="admin.php" style="color: #6c63ff; font-weight: bold;">Dashboard</a>
        <?php endif; ?>

        <a href="feed.php">Library</a>
        <a href="write.php">Write</a> <a href="contact.php" class="active">Feedback</a>
        
        <?php if(isset($_SESSION['username'])): ?>
            <a href="logout.php" class="btn-login">Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn-login">Sign In</a>
        <?php endif; ?>
    </nav>
</header>

<main class="container">
    <div class="auth-container">
        <form method="POST" class="auth-form">
            <div class="welcome-icon">✨</div>
            <h2>Send a Whisper</h2>
            <p>Help us write the next chapter of ReadToRest.</p>

            <?php if($display_msg != ""): ?>
                <div class="alert success" style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    <?php echo $display_msg; ?>
                </div>
            <?php endif; ?>

            <div class="input-group">
                <input type="text" name="name" placeholder="Your Name" required>
            </div>
            <div class="input-group">
                <textarea name="message" placeholder="Your thoughts..." style="height: 120px;" required></textarea>
            </div>
            
            <button type="submit" name="send">Send Message</button>
            <div style="margin-top: 20px;">
                <a href="feed.php" style="color: #666; font-size: 0.9em;">← Back to Library</a>
            </div>
        </form>
    </div>
</main>

</body>
</html>