<?php
session_start();
include 'db.php';

// Redirect to login if the user isn't an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Logic to delete stories
if (isset($_GET['delete_story'])) {
    $id = (int)$_GET['delete_story'];
    mysqli_query($conn, "DELETE FROM stories WHERE id=$id");
    header("Location: admin.php");
    exit();
}

// Logic to delete feedback whispers
if (isset($_GET['delete_feedback'])) {
    $id = (int)$_GET['delete_feedback'];
    mysqli_query($conn, "DELETE FROM contacts WHERE id=$id");
    header("Location: admin.php");
    exit();
}

// Stats Queries
$userCountQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$userData = mysqli_fetch_assoc($userCountQuery);

$storyCountQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM stories");
$storyData = mysqli_fetch_assoc($storyCountQuery);

$feedbackCountQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM contacts");
$feedbackData = mysqli_fetch_assoc($feedbackCountQuery);

// Data Fetching
$stories = mysqli_query($conn, "SELECT stories.*, users.username FROM stories JOIN users ON stories.author_id = users.id ORDER BY stories.id DESC");
$feedbacks = mysqli_query($conn, "SELECT * FROM contacts ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | ReadToRest</title>
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary: #6c63ff;
            --secondary: #ff6b6b;
            --accent: #f39c12;
            --bg: #fdfbff;
            --white: #ffffff;
            --text: #2d2d2d;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
        }

        .admin-header {
            margin-bottom: 40px;
            padding: 20px 0;
        }

        .admin-header h1 {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 5px;
        }

        /* Dashboard Stats Grid */
        .dashboard-grid .card {
            background: var(--white);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(108, 99, 255, 0.08);
            transition: all 0.3s ease;
            text-align: center;
        }

        .dashboard-grid .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(108, 99, 255, 0.15);
        }

        .card h3 {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #888;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            color: #333;
        }

        /* Table Styles */
        .management-section {
            background: var(--white);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            margin-bottom: 40px;
        }

        .admin-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .admin-table thead th {
            padding: 15px;
            color: #aaa;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.75rem;
            text-align: left;
        }

        .admin-table tbody tr {
            background: #fcfcfc;
            transition: 0.2s;
        }

        .admin-table td {
            padding: 15px;
            vertical-align: middle;
        }

        .admin-table td:first-child { border-radius: 12px 0 0 12px; }
        .admin-table td:last-child { border-radius: 0 12px 12px 0; }

        .btn-delete {
            background: rgba(255, 107, 107, 0.1);
            color: var(--secondary);
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .btn-delete:hover {
            background: var(--secondary);
            color: white;
        }

        .msg-text {
            font-size: 0.9rem;
            color: #555;
            max-width: 400px;
            display: block;
        }
    </style>
</head>
<body>

    <header class="navbar">
        <div class="logo">ReadTo<span>Rest</span> Admin</div>
        <nav>
            <a href="feed.php">Library</a>
            <a href="write.php">Write</a>
            <a href="contact.php">Contact</a>
            <a href="logout.php" class="btn-login">Logout</a>
        </nav>
    </header>

    <main class="container">
        <div class="admin-header">
            <h1>Librarian's Dashboard</h1>
            <p>Managing the whispers and stories of <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>.</p>
        </div>

        <div class="dashboard-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 50px;">
            <div class="card" style="border-bottom: 6px solid var(--primary);">
                <h3>Members</h3>
                <p><?php echo $userData['total']; ?></p>
            </div>
            <div class="card" style="border-bottom: 6px solid var(--secondary);">
                <h3>Stories</h3>
                <p><?php echo $storyData['total']; ?></p>
            </div>
            <div class="card" style="border-bottom: 6px solid var(--accent);">
                <h3>Whispers</h3>
                <p><?php echo $feedbackData['total']; ?></p>
            </div>
        </div>

        <section class="management-section">
            <h2>Recent Stories 📖</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($stories)): ?>
                    <tr>
                        <td style="font-weight: 600;"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td>@<?php echo htmlspecialchars($row['username']); ?></td>
                        <td style="text-align: right;">
                            <a href="admin.php?delete_story=<?php echo $row['id']; ?>" 
                               class="btn-delete" 
                               onclick="return confirm('Delete this story?')">Remove</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <section class="management-section">
            <h2>User Whispers ✨</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>Whisper</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($feedbacks) > 0): ?>
                        <?php while($fb = mysqli_fetch_assoc($feedbacks)): ?>
                        <tr>
                            <td style="font-weight: 600;"><?php echo htmlspecialchars($fb['name']); ?></td>
                            <td><span class="msg-text"><?php echo htmlspecialchars($fb['message']); ?></span></td>
                            <td style="text-align: right;">
                                <a href="admin.php?delete_feedback=<?php echo $fb['id']; ?>" 
                                   class="btn-delete" 
                                   onclick="return confirm('Dismiss this whisper?')">Dismiss</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" style="text-align:center; color:#999;">No whispers have reached the library yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

</body>
</html>