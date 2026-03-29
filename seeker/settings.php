<?php
// seeker settings page
// lets seeker change their password

include '../includes/db.php';
include '../includes/auth.php';

// only seekers can access this page
requireLogin('seeker');

$pageTitle = 'Settings';
$userId    = userId();

// get user info from database
$userQuery  = "SELECT * FROM users WHERE id = $userId";
$userResult = mysqli_query($conn, $userQuery);
$user       = mysqli_fetch_assoc($userResult);

$msg = '';
$err = '';

// handle password change form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $currentPassword = $_POST['current_password'];
    $newPassword     = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // check current password
    if (!password_verify($currentPassword, $user['password'])) {
        $err = 'Current password is incorrect.';
    } else if (strlen($newPassword) < 6) {
        $err = 'New password must be at least 6 characters.';
    } else if ($newPassword != $confirmPassword) {
        $err = 'Passwords do not match.';
    } else {
        // hash new password and save
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateQuery    = "UPDATE users SET password = '$hashedPassword' WHERE id = $userId";
        mysqli_query($conn, $updateQuery);
        $msg = 'Password changed successfully.';
    }
}

include '../includes/header.php';
?>

<div id="dashboard-layout">
  <aside id="dashboard-sidebar">
    <div class="sidebar-logo"><div class="logo-icon">J</div> Jobshala</div>
    <nav>
      <a href="/jobshala/seeker/browse.php"><i class="ph ph-magnifying-glass"></i> Browse Jobs</a>
      <a href="/jobshala/seeker/applied.php"><i class="ph ph-file-text"></i> My Applications</a>
      <a href="/jobshala/seeker/settings.php" class="active"><i class="ph ph-gear"></i> Settings</a>
      <a href="/jobshala/php/logout.php"><i class="ph ph-sign-out"></i> Logout</a>
    </nav>
  </aside>

  <div id="dashboard-content">
    <h1 class="dash-page-title" id="settings-title">Settings</h1>

    <?php
    if ($msg != '') echo '<div class="alert alert-success" data-autohide>' . $msg . '</div>';
    if ($err != '') echo '<div class="alert alert-error" data-autohide>' . $err . '</div>';
    ?>

    <!-- profile info display -->
    <div class="settings-section" id="settings-profile">
      <div class="settings-item" id="si-name">
        <div><div class="si-label">Name</div><div class="si-sub"><?php echo $user['full_name']; ?></div></div>
      </div>
      <div class="settings-item" id="si-email">
        <div><div class="si-label">Email</div><div class="si-sub"><?php echo $user['email']; ?></div></div>
      </div>
      <div class="settings-item" id="si-role">
        <div><div class="si-label">Account Type</div><div class="si-sub">Job Seeker</div></div>
      </div>
    </div>

    <!-- change password form -->
    <div class="card card-pad mt-16" id="change-password-card">
      <h3 id="cp-heading" style="font-family:var(--font-head);font-size:1rem;font-weight:700;margin-bottom:16px;">Change Password</h3>
      <form method="POST" id="change-password-form" style="max-width:400px;">
        <div class="form-group">
          <label class="form-label">Current Password</label>
          <input type="password" class="form-control" name="current_password" required />
        </div>
        <div class="form-group">
          <label class="form-label">New Password</label>
          <input type="password" class="form-control" name="new_password" required />
        </div>
        <div class="form-group">
          <label class="form-label">Confirm New Password</label>
          <input type="password" class="form-control" name="confirm_password" required />
        </div>
        <button type="submit" class="btn btn-primary" id="cp-submit-btn">Update Password</button>
      </form>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
