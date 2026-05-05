
<?php
// auth helper functions
// these functions help check if user is logged in

// start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// check if user is logged in, if not redirect to login page
function requireLogin($role = null) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /jobshala/login.php');
        exit();
    }
    // if role is given, check if user has that role
    if ($role != null && $_SESSION['role'] != $role) {
        header('Location: /jobshala/index.php');
        exit();
    }
}

// check if admin is logged in
function requireAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: /jobshala/admin/login.php');
        exit();
    }
}

// return true if user is logged in
function isLoggedIn() {
    if (isset($_SESSION['user_id'])) {
        return true;
    }
    return false;
}

// return true if admin is logged in
function isAdmin() {
    if (isset($_SESSION['admin_id'])) {
        return true;
    }
    return false;
}

// get role of logged in user
function userRole() {
    if (isset($_SESSION['role'])) {
        return $_SESSION['role'];
    }
    return null;
}

// get id of logged in user
function userId() {
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    }
    return null;
}

// get name of logged in user
function userName() {
    if (isset($_SESSION['user_name'])) {
        return $_SESSION['user_name'];
    }
    return '';
}
