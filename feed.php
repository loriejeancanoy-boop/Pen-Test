<?php 
session_start(); 
include 'db.php'; 

// Fetching stories and joining with users table to get the author's name
// I added "stories.id AS story_id" to prevent ID conflicts between tables
$result = mysqli_query($conn, "SELECT stories.id AS story_id, stories.*, users.username FROM stories JOIN users ON stories.author_id = users.id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReadToRest | Your Peaceful Library</title>
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
    <div class="hero">
        <?php if(isset($_SESSION['username'])): ?>
            <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>.</h1>
        <?php else: ?>
            <h1>Find your peace in a story.</h1>
        <?php endif; ?>
        <p>A quiet corner for readers and dreamers.</p>
    </div>

    <h2>Recently Published</h2>
    
    <div class="story-grid">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="story-card">
                <div class="story-cover"></div>
                <div class="story-info">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p class="author">by <?php echo htmlspecialchars($row['username']); ?></p>
                    <p class="snippet">
                        <?php echo nl2br(htmlspecialchars(substr($row['content'], 0, 100))); ?>...
                    </p>
                    
                    <div class="card-actions">
                        <a href="read.php?id=<?php echo $row['story_id']; ?>" class="read-btn">Start Reading</a>

                        <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['author_id']): ?>
                            <a href="edit.php?id=<?php echo $row['story_id']; ?>" class="edit-link">Edit Story</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align: center; color: #888; width: 100%;">The library is quiet... no stories here yet.</p>
        <?php endif; ?>
    </div>
</main>

</body>
</html>