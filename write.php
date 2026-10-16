<?php
session_start();
include 'db.php';

// Redirect to login if the user isn't signed in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if(isset($_POST['publish'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $author_id = $_SESSION['user_id']; 

    // Only check if the title is empty now
    if(!empty($title)) {
        $sql = "INSERT INTO stories (author_id, title, content) VALUES ('$author_id', '$title', '$content')";
        if(mysqli_query($conn, $sql)) {
            $message = "Your story has been added to the library! ✨";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    } else {
        // This only triggers if the title is missing
        $message = "Please give your story a title.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Write | ReadToRest Studio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="navbar">
    <div class="logo">ReadTo<span>Rest</span></div>
    <nav>
        <?php if (
            (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') || 
            (isset($_SESSION['username']) && $_SESSION['username'] === 'admin')
        ): ?>
            <a href="admin.php" style="color: #6c63ff; font-weight: bold;">Dashboard</a>
        <?php endif; ?>

        <a href="feed.php">Library</a>
        <a href="write.php">Write</a>
        <a href="contact.php">Feedback</a> <?php if(isset($_SESSION['username'])): ?>
            <a href="logout.php" class="btn-login">Logout</a>
        <?php else: ?>
            <a href="login.php" class="btn-login">Sign In</a>
        <?php endif; ?>
    </nav>
</header>

<main class="container">
    <div class="auth-container">
        <div class="auth-form" style="max-width: 800px;"> 
            <div class="welcome-icon">✍️</div>
            <h2>Create a Masterpiece</h2>
            <p>Share your thoughts with the ReadToRest community.</p>

            <?php if($message != ""): ?>
                <div class="alert success"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="text" name="title" placeholder="Give your story a title..." required>
                
                <textarea name="content" placeholder="Once upon a time... (Optional)" style="height: 300px;"></textarea>
                
                <button type="submit" name="publish">Publish to Library</button>
            </form>
            
            <p class="switch-auth"><a href="feed.php">← Back to Library</a></p>
        </div>
    </div>
</main>

</body>
</html>