<?php
// admin header file
// included on every admin page

// start session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// function to check if admin is logged in
function requireAdmin() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: /jobshala/admin/login.php');
        exit();
    }
}

// get current page for active nav highlighting
$adminCurrentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>
    <?php
    if (isset($pageTitle)) {
        echo $pageTitle . ' - Admin';
    } else {
        echo 'Admin - Jobshala';
    }
    ?>
  </title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css"/>
  <link rel="stylesheet" href="/jobshala/css/style.css"/>
</head>
<body>

<div id="admin-layout">

  <!-- admin sidebar -->
  <aside id="admin-sidebar">
    <div class="sidebar-brand">
      <div class="logo-text">
        <div class="logo-icon">J</div>
        Jobshala
      </div>
      <div class="admin-badge">Admin Panel</div>
    </div>

    <nav id="admin-nav">
      <div class="nav-section-label">Overview</div>
      <a href="/jobshala/admin/dashboard.php"
         <?php if ($adminCurrentPage == 'dashboard') echo 'class="active"'; ?>
         id="anav-dash">
        <i class="ph ph-gauge"></i> Dashboard
      </a>

      <div class="nav-section-label">Manage</div>
      <a href="/jobshala/admin/users.php"
         <?php if ($adminCurrentPage == 'users') echo 'class="active"'; ?>
         id="anav-users">
        <i class="ph ph-users"></i> Users
      </a>
      <a href="/jobshala/admin/jobs.php"
         <?php if ($adminCurrentPage == 'jobs') echo 'class="active"'; ?>
         id="anav-jobs">
        <i class="ph ph-briefcase"></i> Jobs
      </a>
      <a href="/jobshala/admin/applications.php"
         <?php if ($adminCurrentPage == 'applications') echo 'class="active"'; ?>
         id="anav-apps">
        <i class="ph ph-file-text"></i> Applications
      </a>

      <div class="nav-section-label">Config</div>
      <a href="/jobshala/admin/settings.php"
         <?php if ($adminCurrentPage == 'settings') echo 'class="active"'; ?>
         id="anav-settings">
        <i class="ph ph-gear"></i> Settings
      </a>
      <a href="/jobshala/admin/logout.php" id="anav-logout">
        <i class="ph ph-sign-out"></i> Logout
      </a>
    </nav>
  </aside>

  <!-- admin main content area -->
  <div id="admin-content">
