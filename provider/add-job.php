<?php
// add job page
// providers can post a new job from this page

include '../includes/db.php';
include '../includes/auth.php';

// only providers can access this
requireLogin('provider');

$pageTitle = 'Post a New Job';
$userId    = userId();

$err = '';

// handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // get all form values
    $title      = trim($_POST['title']);
    $description = trim($_POST['description']);
    $skills     = trim($_POST['skills_required']);
    $qual       = trim($_POST['qualification']);
    $salMin     = intval($_POST['salary_min']);
    $salMax     = intval($_POST['salary_max']);
    $location   = trim($_POST['location']);
    $jobType    = $_POST['job_type'];
    $workMode   = $_POST['work_mode'];
    $category   = trim($_POST['category']);

    // check required fields
    if ($title == '' || $description == '') {
        $err = 'Job title and description are required.';
    } else {
        // insert job into database
        $insertQuery = "INSERT INTO jobs (provider_id, title, description, skills_required, qualification, salary_min, salary_max, location, job_type, work_mode, category) VALUES ($userId, '$title', '$description', '$skills', '$qual', $salMin, $salMax, '$location', '$jobType', '$workMode', '$category')";
        $result = mysqli_query($conn, $insertQuery);

        if ($result) {
            $_SESSION['flash'] = 'Job posted successfully!';
            header('Location: /jobshala/provider/dashboard.php');
            exit();
        } else {
            $err = 'Failed to post job. Please try again.';
        }
    }
}

// categories list for dropdown
$categories = array('Tech', 'Marketing', 'Design', 'Finance', 'Operations', 'Media', 'Legal', 'Healthcare');

include '../includes/header.php';
?>

<div id="dashboard-layout">
  <aside id="dashboard-sidebar">
    <div class="sidebar-logo"><div class="logo-icon">J</div> Jobshala</div>
    <nav>
      <a href="/jobshala/provider/dashboard.php"><i class="ph ph-house"></i> Home</a>
      <a href="/jobshala/provider/add-job.php" class="active"><i class="ph ph-plus-circle"></i> Add Job</a>
      <a href="/jobshala/provider/settings.php"><i class="ph ph-gear"></i> Settings</a>
      <a href="/jobshala/php/logout.php"><i class="ph ph-sign-out"></i> Logout</a>
    </nav>
  </aside>

  <div id="dashboard-content">
    <h1 class="dash-page-title" id="add-job-title">Post a New Job</h1>

    <?php
    if ($err != '') {
        echo '<div class="alert alert-error" data-autohide>' . $err . '</div>';
    }
    ?>

    <div class="card card-pad" id="add-job-card" style="max-width:780px;">
      <form method="POST" id="add-job-form" action="">

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" id="add-job-grid-1">

          <!-- job title -->
          <div class="form-group" id="fg-title">
            <label class="form-label" for="title">Job Title *</label>
            <input type="text" class="form-control" id="title" name="title"
                   placeholder="e.g. Full Stack Developer" required
                   value="<?php if (isset($_POST['title'])) echo $_POST['title']; ?>" />
          </div>

          <!-- category dropdown -->
          <div class="form-group" id="fg-category">
            <label class="form-label" for="category">Category</label>
            <select class="form-control form-select" id="category" name="category">
              <option value="">Select category</option>
              <?php
              // show all categories in dropdown
              foreach ($categories as $cat) {
                  $catLower = strtolower($cat);
                  $selected = '';
                  if (isset($_POST['category']) && $_POST['category'] == $catLower) {
                      $selected = 'selected';
                  }
                  echo '<option value="' . $catLower . '" ' . $selected . '>' . $cat . '</option>';
              }
              ?>
            </select>
          </div>
        </div>

        <!-- description -->
        <div class="form-group" id="fg-desc">
          <label class="form-label" for="description">Job Description *</label>
          <textarea class="form-control" id="description" name="description"
                    rows="5" placeholder="Describe the role, responsibilities..." required><?php
            if (isset($_POST['description'])) echo $_POST['description'];
          ?></textarea>
        </div>

        <!-- skills -->
        <div class="form-group" id="fg-skills">
          <label class="form-label" for="skills_required">Skills Required (comma separated)</label>
          <input type="text" class="form-control" id="skills_required" name="skills_required"
                 placeholder="e.g. React, Node.js, MySQL"
                 value="<?php if (isset($_POST['skills_required'])) echo $_POST['skills_required']; ?>" />
        </div>

        <!-- qualification -->
        <div class="form-group" id="fg-qual">
          <label class="form-label" for="qualification">Qualification Required</label>
          <input type="text" class="form-control" id="qualification" name="qualification"
                 placeholder="e.g. B.Tech / BCA or equivalent"
                 value="<?php if (isset($_POST['qualification'])) echo $_POST['qualification']; ?>" />
        </div>

        <!-- salary min and max -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" id="add-job-grid-2">
          <div class="form-group" id="fg-sal-min">
            <label class="form-label" for="salary_min">Salary Min (&#8377;/month)</label>
            <input type="number" class="form-control" id="salary_min" name="salary_min" min="0"
                   value="<?php if (isset($_POST['salary_min'])) echo $_POST['salary_min']; else echo '0'; ?>" />
          </div>
          <div class="form-group" id="fg-sal-max">
            <label class="form-label" for="salary_max">Salary Max (&#8377;/month)</label>
            <input type="number" class="form-control" id="salary_max" name="salary_max" min="0"
                   value="<?php if (isset($_POST['salary_max'])) echo $_POST['salary_max']; else echo '0'; ?>" />
          </div>
        </div>

        <!-- location -->
        <div class="form-group" id="fg-location">
          <label class="form-label" for="location">Location</label>
          <input type="text" class="form-control" id="location" name="location"
                 placeholder="City, State or Remote"
                 value="<?php if (isset($_POST['location'])) echo $_POST['location']; ?>" />
        </div>

        <!-- job type and work mode -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" id="add-job-grid-3">
          <div class="form-group" id="fg-jtype">
            <label class="form-label" for="job_type">Job Type</label>
            <select class="form-control form-select" id="job_type" name="job_type">
              <option value="full-time"  <?php if (isset($_POST['job_type']) && $_POST['job_type'] == 'full-time')  echo 'selected'; ?>>Full Time</option>
              <option value="part-time"  <?php if (isset($_POST['job_type']) && $_POST['job_type'] == 'part-time')  echo 'selected'; ?>>Part Time</option>
              <option value="internship" <?php if (isset($_POST['job_type']) && $_POST['job_type'] == 'internship') echo 'selected'; ?>>Internship</option>
              <option value="freelance"  <?php if (isset($_POST['job_type']) && $_POST['job_type'] == 'freelance')  echo 'selected'; ?>>Freelance</option>
            </select>
          </div>
          <div class="form-group" id="fg-wmode">
            <label class="form-label" for="work_mode">Work Mode</label>
            <select class="form-control form-select" id="work_mode" name="work_mode">
              <option value="on-site" <?php if (isset($_POST['work_mode']) && $_POST['work_mode'] == 'on-site') echo 'selected'; ?>>On-Site</option>
              <option value="remote"  <?php if (isset($_POST['work_mode']) && $_POST['work_mode'] == 'remote')  echo 'selected'; ?>>Remote</option>
              <option value="hybrid"  <?php if (isset($_POST['work_mode']) && $_POST['work_mode'] == 'hybrid')  echo 'selected'; ?>>Hybrid</option>
            </select>
          </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:8px;" id="add-job-btns">
          <button type="submit" class="btn btn-primary btn-lg" id="post-job-submit-btn">Post Job</button>
          <a href="/jobshala/provider/dashboard.php" class="btn btn-outline btn-lg" id="cancel-post-btn">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
