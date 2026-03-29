<?php
// provider settings page
// providers can change company name and password

include '../includes/db.php';
include '../includes/auth.php';

requireLogin('provider');

$pageTitle = 'Provider Settings';
$userId    = userId();

// get user info
$userQuery  = "SELECT * FROM users WHERE id = $userId";
$userResult = mysqli_query($conn, $userQuery);
$user       = mysqli_fetch_assoc($userResult);

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // update company name form
    if (isset($_POST['update_company'])) {
        $companyName = trim($_POST['company_name']);

        if ($companyName == '') {
            $err = 'Company name cannot be empty.';
        } else {
            $updateQuery = "UPDATE users SET company_name = '$companyName' WHERE id = $userId";
            mysqli_query($conn, $updateQuery);
            $user['company_name'] = $companyName;
            $msg = 'Company name updated successfully.';
        }
    }

    // change password form
    if (isset($_POST['change_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword     = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if (!password_verify($currentPassword, $user['password'])) {
            $err = 'Current password is incorrect.';
        } else if (strlen($newPassword) < 6) {
            $err = 'New password must be at least 6 characters.';
        } else if ($newPassword != $confirmPassword) {
            $err = 'Passwords do not match.';
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateQuery    = "UPDATE users SET password = '$hashedPassword' WHERE id = $userId";
            mysqli_query($conn, $updateQuery);
            $msg = 'Password changed successfully.';
        }
    }
}

include '../includes/header.php';
?>

<div id="dashboard-layout">
  <aside id="dashboard-sidebar">
    <div class="sidebar-logo"><div class="logo-icon">J</div> Jobshala</div>
    <nav>
      <a href="/jobshala/provider/dashboard.php"><i class="ph ph-house"></i> Home</a>
      <a href="/jobshala/provider/add-job.php"><i class="ph ph-plus-circle"></i> Add Job</a>
      <a href="/jobshala/provider/settings.php" class="active"><i class="ph ph-gear"></i> Settings</a>
      <a href="/jobshala/php/logout.php"><i class="ph ph-sign-out"></i> Logout</a>
    </nav>
  </aside>

  <div id="dashboard-content">
    <h1 class="dash-page-title" id="provider-settings-title">Settings</h1>

    <?php
    if ($msg != '') echo '<div class="alert alert-success" data-autohide>' . $msg . '</div>';
    if ($err != '') echo '<div class="alert alert-error" data-autohide>' . $err . '</div>';
    ?>

    <!-- profile display -->
    <div class="settings-section" id="provider-profile-section">
      <div class="settings-item">
        <div><div class="si-label">Name</div><div class="si-sub"><?php echo $user['full_name']; ?></div></div>
      </div>
      <div class="settings-item">
        <div><div class="si-label">Email</div><div class="si-sub"><?php echo $user['email']; ?></div></div>
      </div>
      <div class="settings-item">
        <div><div class="si-label">Account Type</div><div class="si-sub">Job Provider</div></div>
      </div>
    </div>

    <!-- update company name -->
    <div class="card card-pad mt-16" id="company-name-card">
      <h3 style="font-family:var(--font-head);font-size:1rem;font-weight:700;margin-bottom:16px;" id="company-name-heading">
        Edit Company Name
      </h3>
      <form method="POST" id="company-name-form" style="max-width:400px;">
        <input type="hidden" name="update_company" value="1" />
        <div class="form-group">
          <label class="form-label" for="company_name">Company Name</label>
          <input type="text" class="form-control" id="company_name" name="company_name"
                 value="<?php echo $user['company_name']; ?>" required />
        </div>
        <button type="submit" class="btn btn-primary" id="update-company-btn">Update</button>
      </form>
    </div>

    <!-- change password -->
    <div class="card card-pad mt-16" id="provider-pw-card">
      <h3 style="font-family:var(--font-head);font-size:1rem;font-weight:700;margin-bottom:16px;" id="provider-pw-heading">
        Change Password
      </h3>
      <form method="POST" id="provider-pw-form" style="max-width:400px;">
        <input type="hidden" name="change_password" value="1" />
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
        <button type="submit" class="btn btn-primary" id="provider-pw-btn">Change Password</button>
      </form>
    </div>

  </div>
</div>

<?php include '../includes/footer.php'; ?>
