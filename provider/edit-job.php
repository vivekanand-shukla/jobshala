<?php
// edit job page
// providers can edit their existing jobs here

include '../includes/db.php';
include '../includes/auth.php';

// only providers can access this
requireLogin('provider');

$pageTitle = 'Edit Job';
$userId    = userId();

// get job id from url
$jobId = 0;
if (isset($_GET['id'])) {
    $jobId = intval($_GET['id']);
}

// get job from database and make sure it belongs to this provider
$jobQuery  = "SELECT * FROM jobs WHERE id = $jobId AND provider_id = $userId";
$jobResult = mysqli_query($conn, $jobQuery);

// if job not found go back to dashboard
if (mysqli_num_rows($jobResult) == 0) {
    header('Location: /jobshala/provider/dashboard.php');
    exit();
}

$job = mysqli_fetch_assoc($jobResult);
$err = '';

// handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $skills      = trim($_POST['skills_required']);
    $qual        = trim($_POST['qualification']);
    $salMin      = intval($_POST['salary_min']);
    $salMax      = intval($_POST['salary_max']);
    $location    = trim($_POST['location']);
    $jobType     = $_POST['job_type'];
    $workMode    = $_POST['work_mode'];
    $category    = trim($_POST['category']);
    $status      = $_POST['status'];

    if ($title == '' || $description == '') {
        $err = 'Title and description are required.';
    } else {
        // update job in database
        $updateQuery = "UPDATE jobs SET title='$title', description='$description', skills_required='$skills', qualification='$qual', salary_min=$salMin, salary_max=$salMax, location='$location', job_type='$jobType', work_mode='$workMode', category='$category', status='$status' WHERE id=$jobId AND provider_id=$userId";
        $result = mysqli_query($conn, $updateQuery);

        if ($result) {
            $_SESSION['flash'] = 'Job updated successfully!';
            header('Location: /jobshala/provider/dashboard.php');
            exit();
        } else {
            $err = 'Update failed. Please try again.';
        }
    }

    // if there was an error keep the posted values
    if ($err != '') {
        $job['title']           = $_POST['title'];
        $job['description']     = $_POST['description'];
        $job['skills_required'] = $_POST['skills_required'];
        $job['qualification']   = $_POST['qualification'];
        $job['salary_min']      = $_POST['salary_min'];
        $job['salary_max']      = $_POST['salary_max'];
        $job['location']        = $_POST['location'];
        $job['job_type']        = $_POST['job_type'];
        $job['work_mode']       = $_POST['work_mode'];
        $job['category']        = $_POST['category'];
        $job['status']          = $_POST['status'];
    }
}

$categories = array('Tech', 'Marketing', 'Design', 'Finance', 'Operations', 'Media', 'Legal', 'Healthcare');

include '../includes/header.php';
?>

<div id="dashboard-layout">
  <aside id="dashboard-sidebar">
    <div class="sidebar-logo"><div class="logo-icon">J</div> Jobshala</div>
    <nav>
      <a href="/jobshala/provider/dashboard.php" class="active"><i class="ph ph-house"></i> Home</a>
      <a href="/jobshala/provider/add-job.php"><i class="ph ph-plus-circle"></i> Add Job</a>
      <a href="/jobshala/provider/settings.php"><i class="ph ph-gear"></i> Settings</a>
      <a href="/jobshala/php/logout.php"><i class="ph ph-sign-out"></i> Logout</a>
    </nav>
  </aside>

  <div id="dashboard-content">
    <h1 class="dash-page-title" id="edit-job-title">Edit Job</h1>

    <?php
    if ($err != '') {
        echo '<div class="alert alert-error" data-autohide>' . $err . '</div>';
    }
    ?>

    <div class="card card-pad" id="edit-job-card" style="max-width:780px;">
      <form method="POST" id="edit-job-form" action="">

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div class="form-group">
            <label class="form-label" for="etitle">Job Title *</label>
            <input type="text" class="form-control" id="etitle" name="title" required
                   value="<?php echo $job['title']; ?>" />
          </div>
          <div class="form-group">
            <label class="form-label" for="ecategory">Category</label>
            <select class="form-control form-select" id="ecategory" name="category">
              <option value="">Select category</option>
              <?php
              foreach ($categories as $cat) {
                  $catLower = strtolower($cat);
                  $selected = '';
                  if ($job['category'] == $catLower) {
                      $selected = 'selected';
                  }
                  echo '<option value="' . $catLower . '" ' . $selected . '>' . $cat . '</option>';
              }
              ?>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="edesc">Description *</label>
          <textarea class="form-control" id="edesc" name="description" rows="5" required><?php echo $job['description']; ?></textarea>
        </div>

        <div class="form-group">
          <label class="form-label" for="eskills">Skills (comma separated)</label>
          <input type="text" class="form-control" id="eskills" name="skills_required"
                 value="<?php echo $job['skills_required']; ?>" />
        </div>

        <div class="form-group">
          <label class="form-label" for="equal">Qualification</label>
          <input type="text" class="form-control" id="equal" name="qualification"
                 value="<?php echo $job['qualification']; ?>" />
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div class="form-group">
            <label class="form-label">Salary Min (&#8377;)</label>
            <input type="number" class="form-control" name="salary_min" value="<?php echo $job['salary_min']; ?>" />
          </div>
          <div class="form-group">
            <label class="form-label">Salary Max (&#8377;)</label>
            <input type="number" class="form-control" name="salary_max" value="<?php echo $job['salary_max']; ?>" />
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="eloc">Location</label>
          <input type="text" class="form-control" id="eloc" name="location"
                 value="<?php echo $job['location']; ?>" />
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
          <div class="form-group">
            <label class="form-label">Job Type</label>
            <select class="form-control form-select" name="job_type">
              <?php
              $types = array('full-time', 'part-time', 'internship', 'freelance');
              foreach ($types as $t) {
                  $sel = '';
                  if ($job['job_type'] == $t) $sel = 'selected';
                  echo '<option value="' . $t . '" ' . $sel . '>' . ucfirst($t) . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Work Mode</label>
            <select class="form-control form-select" name="work_mode">
              <?php
              $modes = array('on-site', 'remote', 'hybrid');
              foreach ($modes as $m) {
                  $sel = '';
                  if ($job['work_mode'] == $m) $sel = 'selected';
                  echo '<option value="' . $m . '" ' . $sel . '>' . ucfirst($m) . '</option>';
              }
              ?>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Status</label>
            <select class="form-control form-select" name="status">
              <option value="active"   <?php if ($job['status'] == 'active')   echo 'selected'; ?>>Active</option>
              <option value="inactive" <?php if ($job['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
            </select>
          </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:8px;">
          <button type="submit" class="btn btn-primary btn-lg" id="update-job-btn">Save Changes</button>
          <a href="/jobshala/provider/dashboard.php" class="btn btn-outline btn-lg">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
