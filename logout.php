<?php
session_start();
session_destroy(); // Clears the "VIP Pass"
header("Location: login.php"); // Sends you back to login
exit();
?>