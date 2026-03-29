<?php
// admin applications page
// admin can see all applications submitted on the site

include '../includes/db.php';
include 'includes/admin_header.php';

requireAdmin();

$pageTitle = 'All Applications';

// get status filter from url
$filter = 'all';
if (isset($_GET['status'])) {
    $filter = $_GET['status'];
}

// build query based on filter
$whereSQL = '';
if ($filter == 'pending' || $filter == 'selected' || $filter == 'rejected') {
    $whereSQL = "WHERE applications.status = '$filter'";
}

// get all applications with job and user info
$query  = "SELECT applications.*, jobs.title AS job_title, users.full_name AS seeker_name, providers.company_name FROM applications JOIN jobs ON applications.job_id = jobs.id JOIN users ON applications.seeker_id = users.id JOIN users AS providers ON jobs.provider_id = providers.id $whereSQL ORDER BY applications.applied_at DESC";
$result = mysqli_query($conn, $query);
?>

    <h1 class="dash-page-title" id="apps-admin-title">All Applications</h1>

    <!-- status filter tabs -->
    <div style="display:flex;gap:8px;margin-bottom:20px;" id="apps-status-tabs">
      <?php
      $tabs = array('all' => 'All', 'pending' => 'Pending', 'selected' => 'Selected', 'rejected' => 'Rejected');
      foreach ($tabs as $val => $label) {
          $btnClass = 'btn-outline';
          if ($filter == $val) {
              $btnClass = 'btn-primary';
          }
          echo '<a href="?status=' . $val . '" class="btn btn-sm ' . $btnClass . '" id="atab-' . $val . '">' . $label . '</a>';
      }
      ?>
    </div>

    <div class="card" id="apps-admin-card">
      <div class="data-table-wrap">
        <table class="data-table" id="apps-admin-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Job</th>
              <th>Company</th>
              <th>Applicant</th>
              <th>Resume</th>
              <th>Applied</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="apps-admin-tbody">
            <?php
            if (mysqli_num_rows($result) == 0) {
                echo '<tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:32px;">No applications found.</td></tr>';
            } else {
                $i = 1;
                while ($a = mysqli_fetch_assoc($result)) {
                    // pick badge color based on status
                    $badgeClass = 'badge-gray';
                    if ($a['status'] == 'pending')  $badgeClass = 'badge-yellow';
                    if ($a['status'] == 'selected') $badgeClass = 'badge-green';
                    if ($a['status'] == 'rejected') $badgeClass = 'badge-red';

                    $contact = $a['contact_info'];
                    if ($contact == '') $contact = '';
            ?>
            <tr id="aapp-row-<?php echo $a['id']; ?>">
              <td><?php echo $i; $i++; ?></td>
              <td id="aapp-job-<?php echo $a['id']; ?>" style="font-weight:600;"><?php echo $a['job_title']; ?></td>
              <td id="aapp-cmp-<?php echo $a['id']; ?>" style="font-size:.85rem;"><?php echo $a['company_name']; ?></td>
              <td id="aapp-seeker-<?php echo $a['id']; ?>">
                <div style="font-weight:600;"><?php echo $a['seeker_name']; ?></div>
                <div style="font-size:.75rem;color:var(--text-muted);"><?php echo $contact; ?></div>
              </td>
              <td id="aapp-resume-<?php echo $a['id']; ?>">
                <?php
                if ($a['resume_link'] != '') {
                    echo '<a href="' . $a['resume_link'] . '" target="_blank" class="btn btn-outline btn-sm">View</a>';
                } else {
                    echo '—';
                }
                ?>
              </td>
              <td id="aapp-date-<?php echo $a['id']; ?>" style="font-size:.8rem;">
                <?php echo date('d M Y', strtotime($a['applied_at'])); ?>
              </td>
              <td id="aapp-status-<?php echo $a['id']; ?>">
                <span class="badge <?php echo $badgeClass; ?>">
                  <span class="status-dot <?php echo $a['status']; ?>"></span>
                  <?php echo ucfirst($a['status']); ?>
                </span>
              </td>
            </tr>
            <?php
                } // end while
            } // end if else
            ?>
          </tbody>
        </table>
      </div>
    </div>

<?php include 'includes/admin_footer.php'; ?>
