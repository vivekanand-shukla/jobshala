<?php
// applicants page
// provider can see all applicants for a job and change their status

include '../includes/db.php';
include '../includes/auth.php';

// only providers can access this
requireLogin('provider');

$pageTitle = 'Job Applicants';
$userId    = userId();

// get job id from url
$jobId = 0;
if (isset($_GET['job_id'])) {
    $jobId = intval($_GET['job_id']);
}

// verify this job belongs to this provider
$jobQuery  = "SELECT * FROM jobs WHERE id = $jobId AND provider_id = $userId";
$jobResult = mysqli_query($conn, $jobQuery);

if (mysqli_num_rows($jobResult) == 0) {
    header('Location: /jobshala/provider/dashboard.php');
    exit();
}

$job = mysqli_fetch_assoc($jobResult);

// handle status change form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appId     = intval($_POST['app_id']);
    $newStatus = $_POST['status'];

    // make sure status is valid
    if ($newStatus == 'pending' || $newStatus == 'selected' || $newStatus == 'rejected') {
        $updateQuery = "UPDATE applications SET status = '$newStatus' WHERE id = $appId AND job_id = $jobId";
        mysqli_query($conn, $updateQuery);
    }

    // refresh page to show updated status
    header('Location: /jobshala/provider/applicants.php?job_id=' . $jobId . '&updated=1');
    exit();
}
// get all applications for this job
$appsQuery  = "SELECT applications.*, users.full_name, users.email FROM applications JOIN users ON applications.seeker_id = users.id WHERE applications.job_id = $jobId ORDER BY applications.applied_at DESC";
$appsResult = mysqli_query($conn, $appsQuery);

// check for flash message
$flash = '';
if (isset($_GET['updated']) && $_GET['updated'] == 1) {
    $flash = 'Applicant status updated successfully.';
}

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

    <?php
    if ($flash != '') {
        echo '<div class="alert alert-success mb-24" data-autohide id="applicants-flash">' . $flash . '</div>';
    }
    ?>

    <!-- back link -->
    <a href="/jobshala/provider/dashboard.php" id="back-to-dash"
       style="display:inline-flex;align-items:center;gap:6px;color:var(--text-secondary);font-size:.88rem;margin-bottom:16px;">
      <i class="ph ph-arrow-left"></i> Back to Dashboard
    </a>

    <div class="flex-between mb-24" id="applicants-header">
      <div>
        <h1 class="dash-page-title" style="margin-bottom:4px;" id="applicants-title">Applicants</h1>
        <p style="color:var(--text-muted);font-size:.9rem;" id="applicants-job-name">
          For: <strong><?php echo $job['title']; ?></strong>
          &nbsp;&middot;&nbsp; <?php echo mysqli_num_rows($appsResult); ?> applicant<?php if (mysqli_num_rows($appsResult) != 1) echo 's'; ?>
        </p>
      </div>
    </div>

    <?php
    if (mysqli_num_rows($appsResult) > 0) {
    ?>
    <div class="card" id="applicants-card">
      <div class="data-table-wrap">
        <table class="data-table" id="applicants-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Applicant</th>
              <th>Contact</th>
              <th>Resume</th>
              <th>Portfolio</th>
              <th>Experience</th>
              <th>Applied</th>
              <th>Status</th>
              <th>Change Status</th>
            </tr>
          </thead>
          <tbody id="applicants-tbody">
            <?php
            $i = 1;
            while ($app = mysqli_fetch_assoc($appsResult)) {

                // get badge color for status
                $badgeClass = 'badge-gray';
                if ($app['status'] == 'pending')  $badgeClass = 'badge-yellow';
                if ($app['status'] == 'selected') $badgeClass = 'badge-green';
                if ($app['status'] == 'rejected') $badgeClass = 'badge-red';

                $contact = $app['contact_info'];
                if ($contact == '') $contact = '—';
            ?>
            <tr id="app-row-<?php echo $app['id']; ?>">
              <td><?php echo $i; $i++; ?></td>
              <td>
                <div id="app-name-<?php echo $app['id']; ?>" style="font-weight:600;"><?php echo $app['full_name']; ?></div>
                <div style="font-size:.78rem;color:var(--text-muted);"><?php echo $app['email']; ?></div>
              </td>
              <td id="app-contact-<?php echo $app['id']; ?>" style="font-size:.85rem;"><?php echo $contact; ?></td>
              <td id="app-resume-<?php echo $app['id']; ?>">
                <?php
                if ($app['resume_link'] != '') {
                    echo '<a href="' . $app['resume_link'] . '" target="_blank" class="btn btn-outline btn-sm"><i class="ph ph-file-text"></i> View</a>';
                } else {
                    echo '—';
                }
                ?>
              </td>
              <td id="app-portfolio-<?php echo $app['id']; ?>">
                <?php
                if ($app['portfolio_link'] != '') {
                    echo '<a href="' . $app['portfolio_link'] . '" target="_blank" class="btn btn-outline btn-sm"><i class="ph ph-link"></i> View</a>';
                } else {
                    echo '—';
                }
                ?>
              </td>
              <td id="app-exp-<?php echo $app['id']; ?>">
                <?php
                if ($app['has_experience'] == 1) {
                    echo '<span class="badge badge-green">Yes</span>';
                } else {
                    echo '<span class="badge badge-gray">No</span>';
                }
                ?>
              </td>
              <td id="app-date-<?php echo $app['id']; ?>" style="font-size:.82rem;">
                <?php echo date('d M Y', strtotime($app['applied_at'])); ?>
              </td>
              <td id="app-status-<?php echo $app['id']; ?>">
                <span class="badge <?php echo $badgeClass; ?>">
                  <span class="status-dot <?php echo $app['status']; ?>"></span>
                  <?php echo ucfirst($app['status']); ?>
                </span>
              </td>
              <td id="app-change-<?php echo $app['id']; ?>">
                <form method="POST" id="status-form-<?php echo $app['id']; ?>"
                      action="/jobshala/provider/applicants.php?job_id=<?php echo $jobId; ?>">
                  <input type="hidden" name="app_id" value="<?php echo $app['id']; ?>" />
                  <div style="display:flex;gap:4px;flex-wrap:wrap;">
                    <?php
                    $statuses = array('pending', 'selected', 'rejected');
                    foreach ($statuses as $s) {
                        $btnClass = 'btn-outline';
                        if ($app['status'] == $s) {
                            $btnClass = 'btn-primary';
                        }
                        echo '<button type="submit" name="status" value="' . $s . '" id="status-' . $s . '-' . $app['id'] . '" class="btn btn-sm ' . $btnClass . '">' . ucfirst($s) . '</button>';
                    }
                    ?>
                  </div>
                </form>
              </td>
            </tr>
            <?php
            } // end while
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php
    } else {
        echo '<div class="empty-state" id="no-applicants">';
        echo '<div class="empty-icon">👥</div>';
        echo '<h3>No applicants yet</h3>';
        echo '<p>Share your job posting to attract candidates.</p>';
        echo '</div>';
    }
    ?>

  </div>
</div>

<?php include '../includes/footer.php'; ?>
