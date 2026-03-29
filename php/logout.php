<?php
// logout page
// destroys session and redirects to home page

// start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// destroy all session data
session_destroy();

// redirect to home page
header('Location: /jobshala/index.php');
exit();
?>
