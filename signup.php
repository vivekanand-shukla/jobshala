<?php
// signup page
// this page is used to create a new account

// include database connection and session
include 'includes/db.php';

// start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = 'Create Account';

// variables to store error and success messages
$error = '';
$success = '';

// check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // get form data using $_POST
    $name     = $_POST['full_name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];
    $role     = $_POST['role'];
    $company  = $_POST['company_name'];

    // remove extra spaces from inputs
    $name    = trim($name);
    $email   = trim($email);
    $company = trim($company);

    // validate form inputs
    if ($name == '' || $email == '' || $password == '' || $confirm == '') {
        $error = 'All fields are required.';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else if (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else if ($password != $confirm) {
        $error = 'Passwords do not match.';
    } else if ($role == 'provider' && $company == '') {
        $error = 'Company name is required for job providers.';
    } else {

        // check if email already exists in database
        $checkQuery = "SELECT id FROM users WHERE email = '$email'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            $error = 'An account with this email already exists.';
        } else {
            // hash the password before saving
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // insert new user into database
            $insertQuery = "INSERT INTO users (full_name, email, password, role, company_name) VALUES ('$name', '$email', '$hashedPassword', '$role', '$company')";
            $result = mysqli_query($conn, $insertQuery);

            if ($result) {
                $success = 'Account created successfully! <a href="/jobshala/login.php">Login now</a>';
            } else {
                $error = 'Something went wrong. Please try again.';
            }
        }
    }
}

// include header
include 'includes/header.php';
?>

<div id="auth-page">
  <div class="auth-card" id="signup-card">

    <div class="auth-logo" id="signup-logo">
      <div class="logo-icon">J</div>
      Jobshala
    </div>

    <h2 id="signup-heading">Join Jobshala</h2>
    <p class="auth-subtitle" id="signup-subtitle">Create your free account</p>

    <?php
    // show error message if there is one
    if ($error != '') {
        echo '<div class="alert alert-error" id="signup-error" data-autohide>' . $error . '</div>';
    }
    // show success message if registration was successful
    if ($success != '') {
        echo '<div class="alert alert-success" id="signup-success">' . $success . '</div>';
    }
    ?>

    <form method="POST" id="signup-form" action="signup.php">

      <!-- role toggle buttons -->
      <div class="role-toggle" id="role-toggle">
        <button type="button" class="active" data-role="seeker" id="role-seeker-btn">Job Seeker</button>
        <button type="button" data-role="provider" id="role-provider-btn">Job Provider</button>
      </div>
      <input type="hidden" name="role" id="role-input" value="seeker" />

      <!-- full name field -->
      <div class="form-group" id="fg-fullname">
        <label class="form-label" for="full_name">Full Name</label>
        <div class="input-icon-wrap">
          <i class="ph ph-user input-icon"></i>
          <input type="text" class="form-control" id="full_name" name="full_name"
                 placeholder="Your full name"
                 value="<?php if (isset($_POST['full_name'])) echo $_POST['full_name']; ?>" required />
        </div>
      </div>
 <!-- company name - only shown for providers -->
      <div class="form-group" id="provider-fields" style="display:none;">
        <label class="form-label" for="company_name">Company Name</label>
        <div class="input-icon-wrap">
          <i class="ph ph-buildings input-icon"></i>
          <input type="text" class="form-control" id="company_name" name="company_name"
                 placeholder="Your company name"
                 value="<?php if (isset($_POST['company_name'])) echo $_POST['company_name']; ?>" />
        </div>
      </div>

      <!-- email field -->
      <div class="form-group" id="fg-email">
        <label class="form-label" for="email">Email</label>
        <div class="input-icon-wrap">
          <i class="ph ph-envelope input-icon"></i>
          <input type="email" class="form-control" id="email" name="email"
                 placeholder="you@example.com"
                 value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" required />
        </div>
      </div>

      <!-- password field -->
      <div class="form-group" id="fg-password">
        <label class="form-label" for="password">Password</label>
        <div class="input-icon-wrap">
          <i class="ph ph-lock input-icon"></i>
          <input type="password" class="form-control" id="password" name="password"
                 placeholder="Min. 6 characters" required />
          <button type="button" class="toggle-pw" id="pw-toggle">
            <i class="ph ph-eye"></i>
          </button>
        </div>
      </div>

      <!-- confirm password field -->
      <div class="form-group" id="fg-confirm">
        <label class="form-label" for="confirm_password">Confirm Password</label>
        <div class="input-icon-wrap">
          <i class="ph ph-lock input-icon"></i>
          <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                 placeholder="Re-enter password" required />
          <button type="button" class="toggle-pw" id="cpw-toggle">
            <i class="ph ph-eye"></i>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-full btn-lg" id="signup-submit-btn">Create Account</button>
    </form>

    <div class="auth-footer" id="signup-footer">
      Already have an account? <a href="/jobshala/login.php" id="login-link">Login</a>
    </div>

  </div>
</div>

<?php include 'includes/footer.php'; ?>
