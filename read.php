<?php
include 'db.php';

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Fetch the story
    $query = "SELECT stories.*, users.username FROM stories 
              JOIN users ON stories.author_id = users.id 
              WHERE stories.id = '$id'";
    $result = mysqli_query($conn, $query);
    $story = mysqli_fetch_assoc($result);

    if(!$story) { die("Story not found."); }

    // Handle NEW Anonymous Comment
    if(isset($_POST['post_comment'])) {
        $msg = mysqli_real_escape_string($conn, $_POST['comment_text']);
        if(!empty($msg)) {
            mysqli_query($conn, "INSERT INTO comments (story_id, comment_text) VALUES ('$id', '$msg')");
        }
    }

    // Fetch existing comments
    $comments_result = mysqli_query($conn, "SELECT * FROM comments WHERE story_id = '$id' ORDER BY created_at DESC");
} else {
    header("Location: feed.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $story['title']; ?> | ReadToRest</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="navbar">
    <a href="feed.php" class="logo">ReadTo<span>Rest</span></a>
    <nav>
        <a href="feed.php">Library</a>
        <a href="write.php">Write</a>
    </nav>
</header>

<main class="container">
    <article class="full-story">
        <div class="story-header">
            <h1><?php echo htmlspecialchars($story['title']); ?></h1>
            <p class="author">by <?php echo htmlspecialchars($story['username']); ?></p>
        </div>
        
        <div class="story-content">
            <?php echo nl2br(htmlspecialchars($story['content'])); ?>
        </div>

        <section class="comment-section">
            <h3>Anonymous Whispers</h3>
            
            <form method="POST" class="comment-form">
                <textarea name="comment_text" placeholder="Leave an anonymous thought..." required></textarea>
                <button type="submit" name="post_comment">Send Whisper</button>
            </form>

            <div class="comments-list">
                <?php while($c = mysqli_fetch_assoc($comments_result)): ?>
                    <div class="comment-bubble">
                        <p><?php echo htmlspecialchars($c['comment_text']); ?></p>
                        <span class="comment-date"><?php echo date('M d, H:i', strtotime($c['created_at'])); ?></span>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <div class="story-footer">
            <a href="feed.php" class="read-btn">← Back to Library</a>
        </div>
    </article>
</main>

</body>
</html>