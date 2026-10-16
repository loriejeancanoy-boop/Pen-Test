<?php
session_start();
include 'db.php';

// 1. Security Check: Are you logged in?
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// 2. Get the story data
if (isset($_GET['id'])) {
    $story_id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM stories WHERE id = '$story_id' AND author_id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $story = mysqli_fetch_assoc($result);

    if (!$story) {
        die("Story not found or you don't have permission to edit it.");
    }
}

// 3. Handle the Update
if (isset($_POST['update'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $update_sql = "UPDATE stories SET title='$title', content='$content' WHERE id='$story_id' AND author_id='$user_id'";
    
    if (mysqli_query($conn, $update_sql)) {
        $message = "Changes saved successfully! ✨";
        // Refresh data to show in form
        $story['title'] = $title;
        $story['content'] = $content;
    } else {
        $message = "Error updating story: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Story | ReadToRest</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="navbar">
    <div class="logo">ReadTo<span>Rest</span></div>
    <nav>
        <a href="feed.php">Library</a>
        <a href="write.php">Write</a>
        <a href="logout.php" class="btn-login">Logout</a>
    </nav>
</header>

<main class="container">
    <div class="auth-container">
        <div class="auth-form" style="max-width: 800px;">
            <div class="welcome-icon">📝</div>
            <h2>Edit Your Story</h2>
            
            <?php if($message != ""): ?>
                <div class="alert success"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="text" name="title" value="<?php echo htmlspecialchars($story['title']); ?>" required>
                <textarea name="content" style="height: 300px;" required><?php echo htmlspecialchars($story['content']); ?></textarea>
                
                <button type="submit" name="update">Save Changes</button>
            </form>
            
            <p class="switch-auth"><a href="read.php?id=<?php echo $story_id; ?>">← Cancel and Go Back</a></p>
        </div>
    </div>
</main>

</body>
</html>