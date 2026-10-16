<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- DEBUG SECTION ---
// If you still don't see the button, remove the "//" from the line below 
// and refresh the page. It will tell you exactly what your role is.
// echo "<div style='color:red; background:white;'>Debug: Role is [" . ($_SESSION['role'] ?? 'NOT SET') . "]</div>";
?>

<header class="navbar">
    <div class="logo">ReadTo<span>Rest</span></div>
    <nav>
        <a href="feed.php">Library</a>
        
        <?php 
        /* We check for 'admin' role OR 'admin' username.
           This ensures that even if 'role' fails, the 'username' catches it.
        */
        $isAdmin = false;
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $isAdmin = true;
        } elseif (isset($_SESSION['username']) && $_SESSION['username'] === 'admin') {
            $isAdmin = true;
        }

        if ($isAdmin): 
        ?>
            <a href="admin.php" class="btn-dashboard" style="color: #6c63ff; font-weight: bold;">Dashboard</a>
        <?php endif; ?>
        
        <a href="write.php">Write</a>
        <a href="logout.php" class="btn-login">Logout</a>
    </nav>
</header>