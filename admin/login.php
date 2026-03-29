<?php
// admin login page
// admin needs email, password AND master key to login

include '../includes/db.php';

// start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = 'Admin Login';
$error     = '';

// handle login form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email     = trim($_POST['email']);
    $password  = $_POST['password'];
    $masterKey = $_POST['master_key'];

    // all three fields are required
    if ($email == '' || $password == '' || $masterKey == '') {
        $error = 'All three fields are required.';
    } else {
        // find admin by email
        $query  = "SELECT * FROM admin WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $admin = mysqli_fetch_assoc($result);

            // check both password and master key
            $passwordOk  = password_verify($password, $admin['password']);
            $masterKeyOk = password_verify($masterKey, $admin['master_key']);

            if ($passwordOk && $masterKeyOk) {
                // both correct, create admin session
                $_SESSION['admin_id']    = $admin['id'];
                $_SESSION['admin_email'] = $admin['email'];

                header('Location: /jobshala/admin/dashboard.php');
                exit();
            } else {
                $error = 'Invalid credentials or master key.';
            }
        } else {
            $error = 'No admin found with this email.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login - Jobshala</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css" />
  <link rel="stylesheet" href="/jobshala/css/style.css" />
</head>
<body>

<div id="auth-page" style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);">
  <div class="auth-card" id="admin-login-card">

    <div class="auth-logo" id="admin-login-logo">
      <div class="logo-icon">J</div>
      Jobshala
    </div>

    <h2 id="admin-login-heading">Admin Login</h2>
    <p class="auth-subtitle" id="admin-login-subtitle">Restricted access - admins only</p>

    <?php
    // show error message if login failed
    if ($error != '') {
        echo '<div class="alert alert-error" id="admin-login-error" data-autohide>' . $error . '</div>';
    }
    ?>

    <form method="POST" id="admin-login-form" action="login.php">

      <!-- email field -->
      <div class="form-group" id="fg-admin-email">
        <label class="form-label" for="admin-email">Admin Email</label>
        <div class="input-icon-wrap">
          <i class="ph ph-envelope input-icon"></i>
          <input type="email" class="form-control" id="admin-email" name="email"
                 placeholder="admin@jobshala.com" required
                 value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" />
        </div>
      </div>

      <!-- password field -->
      <div class="form-group" id="fg-admin-password">
        <label class="form-label" for="admin-password">Password</label>
        <div class="input-icon-wrap">
          <i class="ph ph-lock input-icon"></i>
          <input type="password" class="form-control" id="admin-password" name="password"
                 placeholder="Admin password" required />
          <button type="button" class="toggle-pw" id="admin-pw-toggle">
            <i class="ph ph-eye"></i>
          </button>
        </div>
      </div>

      <!-- master key field (second password) -->
      <div class="form-group" id="fg-master-key">
        <label class="form-label" for="master-key">Master Key (Second Password)</label>
        <div class="input-icon-wrap">
          <i class="ph ph-key input-icon"></i>
          <input type="password" class="form-control" id="master-key" name="master_key"
                 placeholder="Master key" required />
          <button type="button" class="toggle-pw" id="master-pw-toggle">
            <i class="ph ph-eye"></i>
          </button>
        </div>
        <span class="form-hint">Both password and master key are required.</span>
      </div>

      <button type="submit" class="btn btn-primary btn-full btn-lg" id="admin-login-btn">
        Login as Admin
      </button>
    </form>

    <div class="auth-footer" id="admin-back-footer">
      <a href="/jobshala/index.php" style="color:var(--text-muted);">&larr; Back to Jobshala</a>
    </div>

  </div>
</div>

<script src="/jobshala/js/main.js"></script>
</body>
</html>
