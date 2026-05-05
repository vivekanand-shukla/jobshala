<?php
// job detail page
// shows full details of a job and lets seeker apply

include '../includes/db.php';
include '../includes/auth.php';

// get job id from url
$jobId = 0;
if (isset($_GET['id'])) {
    $jobId = intval($_GET['id']);
}

// if no job id redirect to browse
if ($jobId == 0) {
    header('Location: /jobshala/seeker/browse.php');
    exit();
}

// get job details from database
$jobQuery  = "SELECT jobs.*, users.company_name, users.full_name AS provider_name FROM jobs JOIN users ON jobs.provider_id = users.id WHERE jobs.id = $jobId AND jobs.status = 'active'";
$jobResult = mysqli_query($conn, $jobQuery);

// if job not found redirect
if (mysqli_num_rows($jobResult) == 0) {
    header('Location: /jobshala/seeker/browse.php');
    exit();
}

$job       = mysqli_fetch_assoc($jobResult);
$pageTitle = $job['title'];

// check if user already applied for this job
$alreadyApplied = false;
if (isLoggedIn() && userRole() == 'seeker') {
    $userId      = userId();
    $checkQuery  = "SELECT id FROM applications WHERE job_id = $jobId AND seeker_id = $userId";
    $checkResult = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        $alreadyApplied = true;
    }
}

// handle application form submission
$applyError   = '';
$applySuccess = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // check if user is logged in
    if (!isLoggedIn()) {
        header('Location: /jobshala/login.php');
        exit();
    }

    // only seekers can apply
    if (userRole() != 'seeker') {
        $applyError = 'Only job seekers can apply for jobs.';
    } else if ($alreadyApplied) {
        $applyError = 'You have already applied for this job.';
    } else {
        // get form values
        $resumeLink = '';
        $portfolio  = '';
        $contact    = '';
        $hasExp     = 0;

        if (isset($_POST['resume_link']))   $resumeLink = trim($_POST['resume_link']);
        if (isset($_POST['portfolio_link'])) $portfolio = trim($_POST['portfolio_link']);
        if (isset($_POST['contact_info']))  $contact    = trim($_POST['contact_info']);
        if (isset($_POST['has_experience'])) $hasExp    = 1;

        $seekerId = userId();

        // insert application into database
        $insertQuery = "INSERT INTO applications (job_id, seeker_id, resume_link, portfolio_link, contact_info, has_experience) VALUES ($jobId, $seekerId, '$resumeLink', '$portfolio', '$contact', $hasExp)";
        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
            $applySuccess   = 'Application submitted successfully!';
            $alreadyApplied = true;
        } else {
            $applyError = 'Failed to submit application. Please try again.';
        }
    }
}

// split skills by comma into array
$skillsList = array();
if ($job['skills_required'] != '') {
    $skillsList = explode(',', $job['skills_required']);
}

// get company name
$companyName = $job['company_name'];
if ($companyName == '') {
    $companyName = $job['provider_name'];
}

include '../includes/header.php';
?>

<div id="job-detail">
  <div class="container">

    <!-- back link -->
    <a href="/jobshala/seeker/browse.php" id="back-to-browse"
       style="display:inline-flex;align-items:center;gap:6px;color:var(--text-secondary);font-size:.88rem;margin-bottom:20px;">
      <i class="ph ph-arrow-left"></i> Back to Jobs
    </a>

    <!-- job header -->
    <div class="jd-header" id="jd-header-card">
      <div style="display:flex;align-items:flex-start;gap:16px;">
        <div class="company-logo" id="jd-company-logo" style="width:60px;height:60px;border-radius:14px;font-size:1.4rem;">
          <?php echo strtoupper(substr($companyName, 0, 1)); ?>
        </div>
        <div style="flex:1;">
          <h1 id="jd-title" style="font-family:var(--font-head);font-size:1.5rem;font-weight:800;margin-bottom:4px;">
            <?php echo $job['title']; ?>
          </h1>
          <div id="jd-company" style="font-size:1rem;color:var(--text-secondary);font-weight:600;">
            <?php echo $companyName; ?>
          </div>
        </div>
        <div id="jd-salary-badge"
             style="background:#f0fdf9;border:1.5px solid #a7f3d0;border-radius:12px;padding:10px 18px;text-align:center;flex-shrink:0;">
          <div style="font-size:.75rem;color:var(--text-muted);">Salary (&#8377;/mo)</div>
          <div style="font-weight:800;font-size:1rem;color:var(--brand-mid);">
            &#8377;<?php echo number_format($job['salary_min']); ?>-<?php echo number_format($job['salary_max']); ?>
          </div>
        </div>
      </div>

      <div class="jd-meta-row" id="jd-meta-row">
        <div class="jd-meta-item">
          <i class="ph ph-map-pin"></i>
          <?php
          if ($job['location'] != '') {
              echo $job['location'];
          } else {
              echo 'Remote';
          }
          ?>
        </div>
        <div class="jd-meta-item"><i class="ph ph-briefcase"></i><?php echo ucfirst($job['job_type']); ?></div>
        <div class="jd-meta-item"><i class="ph ph-buildings"></i><?php echo ucfirst($job['work_mode']); ?></div>
        <?php
        if ($job['category'] != '') {
            echo '<div class="jd-meta-item"><span class="badge badge-teal">' . $job['category'] . '</span></div>';
        }
        ?>
        <div class="jd-meta-item">
          <i class="ph ph-calendar"></i><?php echo date('d M Y', strtotime($job['created_at'])); ?>
        </div>
      </div>
    </div>

    <!-- body section -->
    <div class="jd-body" id="jd-body">

      <!-- left side job details -->
      <div id="jd-left">
        <div class="jd-section" id="jd-description">
          <h3>Job Description</h3>
          <div id="jd-desc-text" style="color:var(--text-secondary);line-height:1.8;">
            <?php echo nl2br($job['description']); ?>
          </div>
        </div>

        <?php
        if ($job['qualification'] != '') {
        ?>
        <div class="jd-section" id="jd-qualification">
          <h3>Qualifications Required</h3>
          <p id="jd-qual-text" style="color:var(--text-secondary);"><?php echo $job['qualification']; ?></p>
        </div>
        <?php
        }
        ?>

        <?php
        if (count($skillsList) > 0) {
        ?>
        <div class="jd-section" id="jd-skills">
          <h3>Skills Required</h3>
          <div id="jd-skills-list">
            <?php
            // show each skill as a chip
            foreach ($skillsList as $skill) {
                $skill = trim($skill);
                if ($skill != '') {
                    echo '<span class="skill-chip">' . $skill . '</span>';
                }
            }
            ?>
          </div>
        </div>
        <?php
        }
        ?>
      </div>

      <!-- right side apply form -->
      <div id="jd-right">
        <div id="apply-form-wrap">
          <h3 id="apply-heading">Apply for this Job</h3>

          <?php
          // show messages
          if ($applySuccess != '') {
              echo '<div class="alert alert-success" id="apply-success-msg">' . $applySuccess . '</div>';
          }
          if ($applyError != '') {
              echo '<div class="alert alert-error" id="apply-error-msg" data-autohide>' . $applyError . '</div>';
          }
          ?>

          <?php
          if ($alreadyApplied && $applyError == '') {
          ?>
            <div class="alert alert-info" id="already-applied-msg">
              <i class="ph ph-check-circle"></i> You have already applied for this job.
              <a href="/jobshala/seeker/applied.php" style="font-weight:600;color:var(--brand-mid);">View status</a>
            </div>

          <?php
          } else if (!isLoggedIn()) {
          ?>
            <p style="color:var(--text-secondary);font-size:.9rem;margin-bottom:16px;" id="login-to-apply-text">
              You must be logged in to apply.
            </p>
            <a href="/jobshala/login.php" class="btn btn-primary btn-full" id="login-to-apply-btn">Login to Apply</a>

          <?php
          } else if (userRole() == 'provider') {
          ?>
            <div class="alert alert-info">Job providers cannot apply for jobs.</div>

          <?php
          } else {
          ?>
            <form method="POST" id="apply-form" action="">

              <!-- experience checkbox -->
              <label class="checkbox-row" id="exp-check-row" for="has_experience">
                <input type="checkbox" name="has_experience" id="has_experience" value="1" />
                I have 2+ years of experience
              </label>

              <!-- resume link -->
              <div class="form-group" id="fg-resume">
                <label class="form-label" for="resume_link">Portfolio / Resume Link</label>
                <input type="url" class="form-control" id="resume_link" name="resume_link"
                       placeholder="https://drive.google.com/..." />
              </div>

              <!-- portfolio link -->
              <div class="form-group" id="fg-portfolio">
                <label class="form-label" for="portfolio_link">Portfolio Link (optional)</label>
                <input type="url" class="form-control" id="portfolio_link" name="portfolio_link"
                       placeholder="https://yourportfolio.com" />
              </div>

              <!-- contact info -->
              <div class="form-group" id="fg-contact">
                <label class="form-label" for="contact_info">Contact Info</label>
                <input type="text" class="form-control" id="contact_info" name="contact_info"
                       placeholder="Phone number or email" />
              </div>

              <button type="submit" class="btn btn-primary btn-full btn-lg" id="apply-submit-btn">
                Submit Application
              </button>
            </form>
          <?php
          } // end if else
          ?>

        </div>
      </div>

    </div>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
