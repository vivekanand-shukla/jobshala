<?php
// provider dashboard page
// shows posted jobs and stats

include '../includes/db.php';
include '../includes/auth.php';

// check if provider is logged in
requireLogin('provider');

$pageTitle = 'Provider Dashboard';
$userId    = userId();

// get provider info
$userQuery  = "SELECT * FROM users WHERE id = $userId";
$userResult = mysqli_query($conn, $userQuery);
$user       = mysqli_fetch_assoc($userResult);

// get all jobs posted by this provider
$jobsQuery  = "SELECT jobs.*, (SELECT COUNT(*) FROM applications WHERE job_id = jobs.id) AS app_count FROM jobs WHERE jobs.provider_id = $userId ORDER BY jobs.created_at DESC";
$jobsResult = mysqli_query($conn, $jobsQuery);

// count totals for stats
$totalJobs = mysqli_num_rows($jobsResult);
$totalApps = 0;

// store all jobs in array for display
$allJobs = array();
while ($j = mysqli_fetch_assoc($jobsResult)) {
    $allJobs[]  = $j;
    $totalApps += $j['app_count'];
}

// get flash message if any
$flash = '';
if (isset($_SESSION['flash'])) {
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
}

include '../includes/header.php';
?>

<div id="dashboard-layout">

  <!-- sidebar navigation -->
  <aside id="dashboard-sidebar">
    <div class="sidebar-logo"><div class="logo-icon">J</div> Jobshala</div>
    <nav id="provider-sidebar-nav">
      <a href="/jobshala/provider/dashboard.php" class="active" id="pnav-home"><i class="ph ph-house"></i> Home</a>
      <a href="/jobshala/provider/add-job.php" id="pnav-addjob"><i class="ph ph-plus-circle"></i> Add Job</a>
      <a href="/jobshala/provider/settings.php" id="pnav-settings"><i class="ph ph-gear"></i> Settings</a>
      <a href="/jobshala/php/logout.php" id="pnav-logout"><i class="ph ph-sign-out"></i> Logout</a>
    </nav>
  </aside>

  <div id="dashboard-content">

    <?php
    // show flash message if any
    if ($flash != '') {
        echo '<div class="alert alert-success mb-24" data-autohide>' . $flash . '</div>';
    }
    ?>

    <div class="flex-between mb-24" id="dash-header-row">
      <h1 class="dash-page-title" style="margin:0;" id="dash-title">Dashboard</h1>
      <a href="/jobshala/provider/add-job.php" class="btn btn-primary" id="post-new-job-btn">
        <i class="ph ph-plus"></i> Post New Job
      </a>
    </div>

    <!-- stats cards -->
    <div class="stats-grid" id="dash-stats">
      <div class="stat-card" id="stat-jobs">
        <div class="stat-icon" style="background:#dbeafe;"><i class="ph ph-briefcase" style="color:#1d4ed8;font-size:1.3rem;"></i></div>
        <div><div class="stat-val"><?php echo $totalJobs; ?></div><div class="stat-label">Posted Jobs</div></div>
      </div>
      <div class="stat-card" id="stat-apps">
        <div class="stat-icon" style="background:#d1fae5;"><i class="ph ph-users" style="color:#065f46;font-size:1.3rem;"></i></div>
        <div><div class="stat-val"><?php echo $totalApps; ?></div><div class="stat-label">Total Applicants</div></div>
      </div>
      <div class="stat-card" id="stat-company">
        <div class="stat-icon" style="background:#fef3c7;"><i class="ph ph-buildings" style="color:#92400e;font-size:1.3rem;"></i></div>
        <div>
          <div class="stat-val" style="font-size:1rem;">
            <?php
            $cname = $user['company_name'];
            if ($cname == '') $cname = 'N/A';
            echo substr($cname, 0, 14);
            ?>
          </div>
          <div class="stat-label">Company</div>
        </div>
      </div>
    </div>

    <!-- my posted jobs table -->
    <div class="card" id="posted-jobs-card">
      <div class="card-pad" style="border-bottom:1px solid var(--border);">
        <h3 id="posted-jobs-heading" style="font-family:var(--font-head);font-weight:700;">My Posted Jobs</h3>
      </div>

      <?php
      if (count($allJobs) == 0) {
      ?>
        <div class="empty-state" id="no-posted-jobs">
          <div class="empty-icon">📋</div>
          <h3>No jobs posted yet</h3>
          <a href="/jobshala/provider/add-job.php" class="btn btn-primary mt-16">Post Your First Job</a>
        </div>
      <?php
      } else {
      ?>
        <div class="data-table-wrap">
          <table class="data-table" id="posted-jobs-table">
            <thead>
              <tr>
                <th>Job Title</th>
                <th>Category</th>
                <th>Type</th>
                <th>Applicants</th>
                <th>Status</th>
                <th>Posted</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="posted-jobs-tbody">
              <?php
              // loop through jobs and display each row
              foreach ($allJobs as $job) {
                  $statusBadge = 'badge-gray';
                  if ($job['status'] == 'active') {
                      $statusBadge = 'badge-green';
                  }
                  $category = $job['category'];
                  if ($category == '') $category = '—';
              ?>
              <tr id="pjob-row-<?php echo $job['id']; ?>">
                <td>
                  <span id="pjob-title-<?php echo $job['id']; ?>" style="font-weight:600;">
                    <?php echo $job['title']; ?>
                  </span>
                </td>
                <td id="pjob-cat-<?php echo $job['id']; ?>"><?php echo $category; ?></td>
                <td id="pjob-type-<?php echo $job['id']; ?>"><?php echo ucfirst($job['job_type']); ?></td>
                <td>
                  <a href="/jobshala/provider/applicants.php?job_id=<?php echo $job['id']; ?>"
                     id="pjob-apps-<?php echo $job['id']; ?>"
                     style="font-weight:700;color:var(--brand-mid);">
                    <?php echo $job['app_count']; ?> applicant<?php if ($job['app_count'] != 1) echo 's'; ?>
                  </a>
                </td>
                <td>
                  <span class="badge <?php echo $statusBadge; ?>" id="pjob-status-<?php echo $job['id']; ?>">
                    <?php echo ucfirst($job['status']); ?>
                  </span>
                </td>
                <td id="pjob-date-<?php echo $job['id']; ?>"><?php echo date('d M Y', strtotime($job['created_at'])); ?></td>
                <td>
                  <div style="display:flex;gap:6px;" id="pjob-actions-<?php echo $job['id']; ?>">
                    <a href="/jobshala/provider/edit-job.php?id=<?php echo $job['id']; ?>"
                       class="btn btn-outline btn-sm" id="pjob-edit-<?php echo $job['id']; ?>">
                      <i class="ph ph-pencil"></i> Edit
                    </a>
                    <form method="POST" action="/jobshala/php/delete-job.php"
                          onsubmit="confirmDelete(this); return false;"
                          id="delete-form-<?php echo $job['id']; ?>">
                      <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>" />
                      <button type="submit" class="btn btn-danger btn-sm" id="pjob-del-<?php echo $job['id']; ?>">
                        <i class="ph ph-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              <?php
              } // end foreach
              ?>
            </tbody>
          </table>
        </div>
      <?php
      } // end if else
      ?>
    </div>

  </div>
</div>

<?php include '../includes/footer.php'; ?>
