<?php
// login page
// this page lets users login to their account

// include database connection
include 'includes/db.php';

// start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = 'Login';
$error = '';

// check if login form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // get email and password from form
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // remove extra spaces
    $email = trim($email);

    // check if fields are empty
    if ($email == '' || $password == '') {
        $error = 'Email and password are required.';
    } else {
        // search user in database by email
        $query  = "SELECT id, full_name, password, role FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            // user found, now check password
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                // password is correct, save user info in session
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['role']      = $user['role'];

                // redirect based on role
                if ($user['role'] == 'provider') {
                    header('Location: /jobshala/provider/dashboard.php');
                    exit();
                } else {
                    header('Location: /jobshala/seeker/browse.php');
                    exit();
                }
            } else {
                $error = 'Incorrect password.';
            }
        } else {
            $error = 'No account found with this email.';
        }
    }
}

// include header
include 'includes/header.php';
?>

<div id="auth-page">
  <div class="auth-card" id="login-card">

    <div class="auth-logo" id="login-logo">
      <div class="logo-icon">J</div>
      Jobshala
    </div>

    <h2 id="login-heading">Welcome Back</h2>
    <p class="auth-subtitle" id="login-subtitle">Login to your account</p>

    <?php
    // show error if login failed
    if ($error != '') {
        echo '<div class="alert alert-error" id="login-error" data-autohide>' . $error . '</div>';
    }
    ?>

    <form method="POST" id="login-form" action="login.php">

      <!-- email input -->
      <div class="form-group" id="fg-login-email">
        <label class="form-label" for="login-email">Email</label>
        <div class="input-icon-wrap">
          <i class="ph ph-envelope input-icon"></i>
          <input type="email" class="form-control" id="login-email" name="email"
                 placeholder="you@example.com"
                 value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" required />
        </div>
      </div>

      <!-- password input -->
      <div class="form-group" id="fg-login-password">
        <label class="form-label" for="login-password">Password</label>
        <div class="input-icon-wrap">
          <i class="ph ph-lock input-icon"></i>
          <input type="password" class="form-control" id="login-password" name="password"
                 placeholder="Your password" required />
          <button type="button" class="toggle-pw" id="login-pw-toggle">
            <i class="ph ph-eye"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-full btn-lg" id="login-submit-btn" style="margin-top:4px;">
        Login
      </button>
    </form>

    <div class="auth-footer" id="login-footer">
      Don't have an account? <a href="/jobshala/signup.php" id="signup-link">Sign up</a>
    </div>

  </div>
</div>

<?php include 'includes/footer.php'; ?>
