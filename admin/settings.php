<?php
// admin settings page
// admin can change email, password and master key here

include '../includes/db.php';
include 'includes/admin_header.php';

requireAdmin();

$pageTitle = 'Admin Settings';

$adminId = $_SESSION['admin_id'];

// get admin info from database
$adminQuery  = "SELECT * FROM admin WHERE id = $adminId";
$adminResult = mysqli_query($conn, $adminQuery);
$admin       = mysqli_fetch_assoc($adminResult);

$msg = '';
$err = '';

// handle all three forms
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ---- update admin email ----
    if (isset($_POST['update_email'])) {
        $newEmail       = trim($_POST['new_email']);
        $verifyPassword = $_POST['verify_password'];

        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $err = 'Please enter a valid email address.';
        } else if (!password_verify($verifyPassword, $admin['password'])) {
            $err = 'Password verification failed.';
        } else {
            $updateQuery = "UPDATE admin SET email = '$newEmail' WHERE id = $adminId";
            mysqli_query($conn, $updateQuery);
            $_SESSION['admin_email'] = $newEmail;
            $admin['email'] = $newEmail;
            $msg = 'Admin email updated successfully.';
        }
    }

    // ---- change admin password ----
    if (isset($_POST['update_password'])) {
        $currentPassword = $_POST['current_password'];
        $newPassword     = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if (!password_verify($currentPassword, $admin['password'])) {
            $err = 'Current password is incorrect.';
        } else if (strlen($newPassword) < 6) {
            $err = 'New password must be at least 6 characters.';
        } else if ($newPassword != $confirmPassword) {
            $err = 'Passwords do not match.';
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateQuery    = "UPDATE admin SET password = '$hashedPassword' WHERE id = $adminId";
            mysqli_query($conn, $updateQuery);
            $admin['password'] = $hashedPassword;
            $msg = 'Admin password updated successfully.';
        }
    }

    // ---- change master key ----
    if (isset($_POST['update_master_key'])) {
        $currentMaster = $_POST['current_master_key'];
        $newMaster     = $_POST['new_master_key'];
        $confirmMaster = $_POST['confirm_master_key'];

        if (!password_verify($currentMaster, $admin['master_key'])) {
            $err = 'Current master key is incorrect.';
        } else if (strlen($newMaster) < 6) {
            $err = 'Master key must be at least 6 characters.';
        } else if ($newMaster != $confirmMaster) {
            $err = 'Master keys do not match.';
        } else {
            $hashedMaster = password_hash($newMaster, PASSWORD_DEFAULT);
            $updateQuery  = "UPDATE admin SET master_key = '$hashedMaster' WHERE id = $adminId";
            mysqli_query($conn, $updateQuery);
            $admin['master_key'] = $hashedMaster;
            $msg = 'Master key updated successfully.';
        }
    }
}
?>

    <h1 class="dash-page-title" id="admin-settings-title">Admin Settings</h1>
    <p style="color:var(--text-muted);margin-top:-16px;margin-bottom:24px;">
      Only admins can change these credentials.
    </p>

    <?php
    if ($msg != '') echo '<div class="alert alert-success mb-16" data-autohide>' . $msg . '</div>';
    if ($err != '') echo '<div class="alert alert-error mb-16" data-autohide>' . $err . '</div>';
    ?>

    <div style="max-width:560px;" id="admin-settings-wrap">

      <!-- show current info -->
      <div class="settings-section" id="admin-current-info">
        <div class="settings-item">
          <div>
            <div class="si-label">Current Email</div>
            <div class="si-sub"><?php echo $admin['email']; ?></div>
          </div>
        </div>
        <div class="settings-item">
          <div>
            <div class="si-label">Password</div>
            <div class="si-sub">&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;</div>
          </div>
        </div>
        <div class="settings-item">
          <div>
            <div class="si-label">Master Key (Second Password)</div>
            <div class="si-sub">&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;</div>
          </div>
        </div>
      </div>

      <!-- update email form -->
      <div class="card card-pad mt-16" id="admin-email-card">
        <h3 style="font-family:var(--font-head);font-weight:700;font-size:1rem;margin-bottom:16px;" id="admin-email-heading">
          Update Admin Email
        </h3>
        <form method="POST" id="admin-email-form">
          <input type="hidden" name="update_email" value="1" />
          <div class="form-group">
            <label class="form-label" for="new-admin-email">New Email</label>
            <input type="email" class="form-control" id="new-admin-email" name="new_email"
                   placeholder="new@jobshala.com" required />
          </div>
          <div class="form-group">
            <label class="form-label" for="verify-pw-email">Verify with Current Password</label>
            <div class="input-icon-wrap">
              <i class="ph ph-lock input-icon"></i>
              <input type="password" class="form-control" id="verify-pw-email"
                     name="verify_password" placeholder="Current password" required />
              <button type="button" class="toggle-pw"><i class="ph ph-eye"></i></button>
            </div>
          </div>
          <button type="submit" class="btn btn-primary" id="update-email-btn">Update Email</button>
        </form>
      </div>

      <!-- change password form -->
      <div class="card card-pad mt-16" id="admin-password-card">
        <h3 style="font-family:var(--font-head);font-weight:700;font-size:1rem;margin-bottom:16px;" id="admin-pw-heading">
          Change Password
        </h3>
        <form method="POST" id="admin-password-form">
          <input type="hidden" name="update_password" value="1" />
          <div class="form-group">
            <label class="form-label">Current Password</label>
            <div class="input-icon-wrap">
              <i class="ph ph-lock input-icon"></i>
              <input type="password" class="form-control" name="current_password" required />
              <button type="button" class="toggle-pw"><i class="ph ph-eye"></i></button>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">New Password</label>
            <div class="input-icon-wrap">
              <i class="ph ph-lock input-icon"></i>
              <input type="password" class="form-control" name="new_password" required />
              <button type="button" class="toggle-pw"><i class="ph ph-eye"></i></button>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" name="confirm_password" required />
          </div>
          <button type="submit" class="btn btn-primary" id="update-pw-btn">Change Password</button>
        </form>
      </div>

      <!-- change master key form -->
      <div class="card card-pad mt-16" id="admin-masterkey-card">
        <h3 style="font-family:var(--font-head);font-weight:700;font-size:1rem;margin-bottom:6px;" id="admin-mk-heading">
          Update Master Key (Second Password)
        </h3>
        <p style="font-size:.82rem;color:var(--text-muted);margin-bottom:16px;" id="admin-mk-note">
          The master key is needed along with password to login as admin. Keep it safe.
        </p>
        <form method="POST" id="admin-masterkey-form">
          <input type="hidden" name="update_master_key" value="1" />
          <div class="form-group">
            <label class="form-label">Current Master Key</label>
            <div class="input-icon-wrap">
              <i class="ph ph-key input-icon"></i>
              <input type="password" class="form-control" name="current_master_key" required />
              <button type="button" class="toggle-pw"><i class="ph ph-eye"></i></button>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">New Master Key</label>
            <div class="input-icon-wrap">
              <i class="ph ph-key input-icon"></i>
              <input type="password" class="form-control" name="new_master_key" required />
              <button type="button" class="toggle-pw"><i class="ph ph-eye"></i></button>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Confirm New Master Key</label>
            <input type="password" class="form-control" name="confirm_master_key" required />
          </div>
          <button type="submit" class="btn btn-primary" id="update-mk-btn">Update Master Key</button>
        </form>
      </div>

    </div>

<?php include 'includes/admin_footer.php'; ?>
