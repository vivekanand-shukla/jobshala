<?php
// my applications page
// shows all jobs a seeker has applied for

include '../includes/db.php';
include '../includes/auth.php';

// check if seeker is logged in
requireLogin('seeker');

$pageTitle = 'My Applications';
$userId    = userId();

// get filter from url
$filter = 'all';
if (isset($_GET['status'])) {
    $filter = $_GET['status'];
}

// build query based on status filter
$whereSQL = "WHERE applications.seeker_id = $userId";

if ($filter == 'pending' || $filter == 'selected' || $filter == 'rejected') {
    $whereSQL = $whereSQL . " AND applications.status = '$filter'";
}

// get all applications of this user
$query  = "SELECT applications.*, jobs.title, jobs.location, jobs.job_type, jobs.salary_min, jobs.salary_max, users.company_name FROM applications JOIN jobs ON applications.job_id = jobs.id JOIN users ON jobs.provider_id = users.id $whereSQL ORDER BY applications.applied_at DESC";
$result = mysqli_query($conn, $query);

include '../includes/header.php';
?>

<div id="dashboard-layout">

  <!-- sidebar -->
  <aside id="dashboard-sidebar">
    <div class="sidebar-logo"><div class="logo-icon">J</div> Jobshala</div>
    <nav id="seeker-sidebar-nav">
      <a href="/jobshala/seeker/browse.php" id="snav-browse"><i class="ph ph-magnifying-glass"></i> Browse Jobs</a>
      <a href="/jobshala/seeker/applied.php" id="snav-applied" class="active"><i class="ph ph-file-text"></i> My Applications</a>
      <a href="/jobshala/seeker/settings.php" id="snav-settings"><i class="ph ph-gear"></i> Settings</a>
      <a href="/jobshala/php/logout.php" id="snav-logout"><i class="ph ph-sign-out"></i> Logout</a>
    </nav>
  </aside>

  <div id="dashboard-content">
    <h1 class="dash-page-title" id="applied-title">My Applications</h1>

    <!-- filter tabs -->
    <div id="status-tabs" style="display:flex;gap:8px;margin-bottom:24px;">
      <?php
      // show tabs for each status
      $tabs = array('all' => 'All', 'pending' => 'Pending', 'selected' => 'Selected', 'rejected' => 'Rejected');
      foreach ($tabs as $val => $label) {
          $btnClass = 'btn-outline';
          if ($filter == $val) {
              $btnClass = 'btn-primary';
          }
          echo '<a href="?status=' . $val . '" id="tab-' . $val . '" class="btn btn-sm ' . $btnClass . '">' . $label . '</a>';
      }
      ?>
    </div>

    <?php
    if (mysqli_num_rows($result) > 0) {
    ?>
    <div class="card" id="applied-table-card">
      <div class="data-table-wrap">
        <table class="data-table" id="applied-table">
          <thead>
            <tr>
              <th>Job</th>
              <th>Company</th>
              <th>Location</th>
              <th>Salary</th>
              <th>Applied On</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="applied-table-body">
            <?php
            while ($app = mysqli_fetch_assoc($result)) {
                // decide badge color based on status
                $badgeClass = 'badge-gray';
                if ($app['status'] == 'pending')  $badgeClass = 'badge-yellow';
                if ($app['status'] == 'selected') $badgeClass = 'badge-green';
                if ($app['status'] == 'rejected') $badgeClass = 'badge-red';

                $jobLocation = $app['location'];
                if ($jobLocation == '') {
                    $jobLocation = 'Remote';
                }
            ?>
            <tr id="app-row-<?php echo $app['id']; ?>">
              <td>
                <a href="/jobshala/seeker/job-detail.php?id=<?php echo $app['job_id']; ?>"
                   style="font-weight:600;color:var(--brand-mid);" id="appjob-<?php echo $app['id']; ?>">
                  <?php echo $app['title']; ?>
                </a>
                <div style="font-size:.78rem;color:var(--text-muted);"><?php echo ucfirst($app['job_type']); ?></div>
              </td>
              <td id="appcmp-<?php echo $app['id']; ?>"><?php echo $app['company_name']; ?></td>
              <td id="apploc-<?php echo $app['id']; ?>"><?php echo $jobLocation; ?></td>
              <td id="appsal-<?php echo $app['id']; ?>">
                &#8377;<?php echo number_format($app['salary_min']); ?>-<?php echo number_format($app['salary_max']); ?>
              </td>
              <td id="appdate-<?php echo $app['id']; ?>"><?php echo date('d M Y', strtotime($app['applied_at'])); ?></td>
              <td id="appstatus-<?php echo $app['id']; ?>">
                <span class="badge <?php echo $badgeClass; ?>">
                  <span class="status-dot <?php echo $app['status']; ?>"></span>
                  <?php echo ucfirst($app['status']); ?>
                </span>
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
        // no applications found
        echo '<div class="empty-state" id="applied-empty">';
        echo '<div class="empty-icon">📄</div>';
        echo '<h3>No applications yet</h3>';
        echo '<p>Browse jobs and start applying!</p>';
        echo '<a href="/jobshala/seeker/browse.php" class="btn btn-primary mt-16">Browse Jobs</a>';
        echo '</div>';
    }
    ?>
  </div>
</div>
<?php include '../includes/footer.php'; ?>
