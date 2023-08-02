<?php
// Start the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['MM_Username'])) {
    header("Location: login.php");
    exit;
}
?>
