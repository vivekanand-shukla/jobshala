<?php
// header file - included on every page
// shows navigation bar at top of page

// start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// get current page name to highlight active link
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    <?php
    // show page title if set otherwise show default title
    if (isset($pageTitle)) {
        echo $pageTitle . " - Jobshala";
    } else {
        echo "Jobshala - Find Your Dream Career";
    }
    ?>
  </title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css" />
  <link rel="stylesheet" href="/jobshala/css/style.css" />
</head>
<body>

<header id="main-header">
  <div class="container header-inner">

    <!-- logo -->
    <a href="/jobshala/index.php" class="logo" id="header-logo">
      <div class="logo-icon">J</div>
      Jobshala
    </a>

    <!-- navigation links -->
    <nav id="header-nav">
      <a href="/jobshala/index.php" <?php if ($currentPage == 'index') echo 'class="active"'; ?>>Home</a>
      <a href="/jobshala/seeker/browse.php" <?php if ($currentPage == 'browse') echo 'class="active"'; ?>>Jobs</a>
      
      <!-- <a href="/jobshala/seeker/browse.php?type=internship">Freshers</a> -->
      <!-- <a href="#courses">Courses</a> -->
    </nav>

    <!-- login logout buttons -->
    <div class="header-actions" id="header-actions">
      <?php
      // check if user is logged in
      if (isset($_SESSION['user_id'])) {
          // show different buttons based on role
          if ($_SESSION['role'] == 'provider') {
              echo '<a href="/jobshala/provider/dashboard.php" class="btn btn-outline btn-sm" id="header-dashboard-btn">Dashboard</a>';
          } else {
              echo '<a href="/jobshala/seeker/applied.php" class="btn btn-outline btn-sm" id="header-applied-btn">My Applications</a>';
          }
          echo '<a href="/jobshala/php/logout.php" class="btn btn-primary btn-sm" id="header-logout-btn">Logout</a>';
      } else {
          // user not logged in show login signup buttons
      ?>
      <div class="header-right-stack">
        <div style="display:flex;gap:8px;">
          <a href="/jobshala/login.php" class="btn btn-outline btn-sm" id="header-login-btn">Login</a>
          <a href="/jobshala/signup.php" class="btn btn-primary btn-sm" id="header-signup-btn">Signup</a>
        </div>
        <a href="/jobshala/admin/login.php" class="admin-link" id="header-admin-link">Admin Login</a>
      </div>
      <?php
      } // end if else
      ?>
    </div>

  </div>
</header>
