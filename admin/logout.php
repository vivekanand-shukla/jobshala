<?php
// admin logout
// clears admin session and redirects to admin login

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// remove admin session variables
unset($_SESSION['admin_id']);
unset($_SESSION['admin_email']);

// destroy session
session_destroy();

// go back to admin login page
header('Location: /jobshala/admin/login.php');
exit();
?>
