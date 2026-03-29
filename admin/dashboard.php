<?php
// admin dashboard page
// shows website stats and recent activity

include '../includes/db.php';
include 'includes/admin_header.php';

// check admin is logged in
requireAdmin();

$pageTitle = 'Admin Dashboard';

// count total users
$totalUsersResult   = mysqli_query($conn, "SELECT COUNT(*) AS c FROM users");
$totalUsersRow      = mysqli_fetch_assoc($totalUsersResult);
$totalUsers         = $totalUsersRow['c'];

// count seekers only
$seekersResult      = mysqli_query($conn, "SELECT COUNT(*) AS c FROM users WHERE role = 'seeker'");
$seekersRow         = mysqli_fetch_assoc($seekersResult);
$totalSeekers       = $seekersRow['c'];

// count providers only
$providersResult    = mysqli_query($conn, "SELECT COUNT(*) AS c FROM users WHERE role = 'provider'");
$providersRow       = mysqli_fetch_assoc($providersResult);
$totalProviders     = $providersRow['c'];

// count total jobs
$totalJobsResult    = mysqli_query($conn, "SELECT COUNT(*) AS c FROM jobs");
$totalJobsRow       = mysqli_fetch_assoc($totalJobsResult);
$totalJobs          = $totalJobsRow['c'];

// count active jobs only
$activeJobsResult   = mysqli_query($conn, "SELECT COUNT(*) AS c FROM jobs WHERE status = 'active'");
$activeJobsRow      = mysqli_fetch_assoc($activeJobsResult);
$activeJobs         = $activeJobsRow['c'];

// count total applications
$totalAppsResult    = mysqli_query($conn, "SELECT COUNT(*) AS c FROM applications");
$totalAppsRow       = mysqli_fetch_assoc($totalAppsResult);
$totalApplications  = $totalAppsRow['c'];

// get 5 most recent users
$recentUsersResult  = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC LIMIT 5");

// get 5 most recent jobs
$recentJobsResult   = mysqli_query($conn, "SELECT jobs.*, users.company_name FROM jobs JOIN users ON jobs.provider_id = users.id ORDER BY jobs.created_at DESC LIMIT 5");
?>

    <h1 class="dash-page-title" id="admin-dash-title">Dashboard</h1>

    <!-- stats cards -->
    <div class="stats-grid" id="admin-stats-grid">

      <div class="stat-card" id="astat-users">
        <div class="stat-icon" style="background:#dbeafe;">
          <i class="ph ph-users" style="color:#1d4ed8;font-size:1.3rem;"></i>
        </div>
        <div>
          <div class="stat-val"><?php echo $totalUsers; ?></div>
          <div class="stat-label">Total Users</div>
        </div>
      </div>

      <div class="stat-card" id="astat-seekers">
        <div class="stat-icon" style="background:#d1fae5;">
          <i class="ph ph-person" style="color:#065f46;font-size:1.3rem;"></i>
        </div>
        <div>
          <div class="stat-val"><?php echo $totalSeekers; ?></div>
          <div class="stat-label">Job Seekers</div>
        </div>
      </div>

      <div class="stat-card" id="astat-providers">
        <div class="stat-icon" style="background:#fef3c7;">
          <i class="ph ph-buildings" style="color:#92400e;font-size:1.3rem;"></i>
        </div>
        <div>
          <div class="stat-val"><?php echo $totalProviders; ?></div>
          <div class="stat-label">Providers</div>
        </div>
      </div>

      <div class="stat-card" id="astat-jobs">
        <div class="stat-icon" style="background:#ede9fe;">
          <i class="ph ph-briefcase" style="color:#5b21b6;font-size:1.3rem;"></i>
        </div>
        <div>
          <div class="stat-val"><?php echo $totalJobs; ?></div>
          <div class="stat-label">Total Jobs</div>
        </div>
      </div>

      <div class="stat-card" id="astat-active-jobs">
        <div class="stat-icon" style="background:#ccfbf1;">
          <i class="ph ph-check-circle" style="color:#0f766e;font-size:1.3rem;"></i>
        </div>
        <div>
          <div class="stat-val"><?php echo $activeJobs; ?></div>
          <div class="stat-label">Active Jobs</div>
        </div>
      </div>

      <div class="stat-card" id="astat-apps">
        <div class="stat-icon" style="background:#fee2e2;">
          <i class="ph ph-file-text" style="color:#991b1b;font-size:1.3rem;"></i>
        </div>
        <div>
          <div class="stat-val"><?php echo $totalApplications; ?></div>
          <div class="stat-label">Applications</div>
        </div>
      </div>

    </div>

    <!-- recent users and jobs tables -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;" id="admin-dash-grid">

      <!-- recent users table -->
      <div class="card" id="admin-recent-users-card">
        <div class="card-pad" style="border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
          <h3 style="font-family:var(--font-head);font-weight:700;font-size:1rem;" id="recent-users-heading">Recent Users</h3>
          <a href="/jobshala/admin/users.php" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="data-table-wrap">
          <table class="data-table" id="admin-recent-users-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Role</th>
                <th>Joined</th>
              </tr>
            </thead>
            <tbody>
              <?php
              while ($u = mysqli_fetch_assoc($recentUsersResult)) {
                  $roleBadge = 'badge-green';
                  if ($u['role'] == 'provider') {
                      $roleBadge = 'badge-blue';
                  }
              ?>
              <tr id="ru-<?php echo $u['id']; ?>">
                <td>
                  <div style="font-weight:600;"><?php echo $u['full_name']; ?></div>
                  <div style="font-size:.75rem;color:var(--text-muted);"><?php echo $u['email']; ?></div>
                </td>
                <td>
                  <span class="badge <?php echo $roleBadge; ?>">
                    <?php echo ucfirst($u['role']); ?>
                  </span>
                </td>
                <td style="font-size:.8rem;"><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
              </tr>
              <?php
              } // end while
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- recent jobs table -->
      <div class="card" id="admin-recent-jobs-card">
        <div class="card-pad" style="border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
          <h3 style="font-family:var(--font-head);font-weight:700;font-size:1rem;" id="recent-jobs-heading-admin">Recent Jobs</h3>
          <a href="/jobshala/admin/jobs.php" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="data-table-wrap">
          <table class="data-table" id="admin-recent-jobs-table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Company</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              while ($j = mysqli_fetch_assoc($recentJobsResult)) {
                  $statusBadge = 'badge-gray';
                  if ($j['status'] == 'active') {
                      $statusBadge = 'badge-green';
                  }
              ?>
              <tr id="rj-<?php echo $j['id']; ?>">
                <td style="font-weight:600;"><?php echo $j['title']; ?></td>
                <td style="font-size:.82rem;color:var(--text-muted);"><?php echo $j['company_name']; ?></td>
                <td>
                  <span class="badge <?php echo $statusBadge; ?>">
                    <?php echo ucfirst($j['status']); ?>
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

    </div>

<?php include 'includes/admin_footer.php'; ?>
