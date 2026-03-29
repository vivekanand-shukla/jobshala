<?php
// admin jobs page
// admin can view, activate/deactivate and delete all jobs

include '../includes/db.php';
include 'includes/admin_header.php';

requireAdmin();

$pageTitle = 'Manage Jobs';
$msg       = '';

// handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // delete a job
    if (isset($_POST['delete_job'])) {
        $deleteId = intval($_POST['job_id']);
        if ($deleteId > 0) {
            $deleteQuery = "DELETE FROM jobs WHERE id = $deleteId";
            mysqli_query($conn, $deleteQuery);
            $msg = 'Job deleted successfully.';
        }
    }

    // toggle job status active/inactive
    if (isset($_POST['toggle_status'])) {
        $jobId        = intval($_POST['job_id']);
        $currentStatus = $_POST['current_status'];

        // flip the status
        if ($currentStatus == 'active') {
            $newStatus = 'inactive';
        } else {
            $newStatus = 'active';
        }

        $updateQuery = "UPDATE jobs SET status = '$newStatus' WHERE id = $jobId";
        mysqli_query($conn, $updateQuery);
        $msg = 'Job status changed to ' . $newStatus . '.';
    }
}

// get all jobs with company name and application count
$jobsQuery  = "SELECT jobs.*, users.company_name, (SELECT COUNT(*) FROM applications WHERE job_id = jobs.id) AS app_count FROM jobs JOIN users ON jobs.provider_id = users.id ORDER BY jobs.created_at DESC";
$jobsResult = mysqli_query($conn, $jobsQuery);
?>

    <div class="flex-between mb-24" id="admin-jobs-header">
      <h1 class="dash-page-title" style="margin:0;" id="admin-jobs-title">Manage Jobs</h1>
    </div>

    <?php
    if ($msg != '') {
        echo '<div class="alert alert-success mb-16" data-autohide>' . $msg . '</div>';
    }
    ?>

    <div class="card" id="admin-jobs-card">
      <div class="data-table-wrap">
        <table class="data-table" id="admin-jobs-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Company</th>
              <th>Type</th>
              <th>Location</th>
              <th>Applicants</th>
              <th>Status</th>
              <th>Posted</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="admin-jobs-tbody">
            <?php
            if (mysqli_num_rows($jobsResult) == 0) {
                echo '<tr><td colspan="9" style="text-align:center;color:var(--text-muted);padding:32px;">No jobs found.</td></tr>';
            } else {
                $i = 1;
                while ($j = mysqli_fetch_assoc($jobsResult)) {
                    $statusBadge = 'badge-gray';
                    if ($j['status'] == 'active') {
                        $statusBadge = 'badge-green';
                    }
                    $location = $j['location'];
                    if ($location == '') $location = '—';
            ?>
            <tr id="ajob-row-<?php echo $j['id']; ?>">
              <td><?php echo $i; $i++; ?></td>
              <td>
                <div style="font-weight:600;" id="ajob-title-<?php echo $j['id']; ?>"><?php echo $j['title']; ?></div>
                <?php
                if ($j['category'] != '') {
                    echo '<span class="badge badge-teal" style="margin-top:3px;">' . $j['category'] . '</span>';
                }
                ?>
              </td>
              <td id="ajob-cmp-<?php echo $j['id']; ?>" style="font-size:.85rem;"><?php echo $j['company_name']; ?></td>
              <td id="ajob-type-<?php echo $j['id']; ?>" style="font-size:.85rem;"><?php echo ucfirst($j['job_type']); ?></td>
              <td id="ajob-loc-<?php echo $j['id']; ?>" style="font-size:.82rem;"><?php echo $location; ?></td>
              <td id="ajob-apps-<?php echo $j['id']; ?>" style="font-weight:700;color:var(--brand-mid);">
                <?php echo $j['app_count']; ?>
              </td>
              <td>
                <span class="badge <?php echo $statusBadge; ?>" id="ajob-status-<?php echo $j['id']; ?>">
                  <?php echo ucfirst($j['status']); ?>
                </span>
              </td>
              <td id="ajob-date-<?php echo $j['id']; ?>" style="font-size:.8rem;">
                <?php echo date('d M Y', strtotime($j['created_at'])); ?>
              </td>
              <td>
                <div style="display:flex;gap:6px;flex-wrap:wrap;" id="ajob-actions-<?php echo $j['id']; ?>">

                  <!-- toggle status button -->
                  <form method="POST" id="toggle-form-<?php echo $j['id']; ?>">
                    <input type="hidden" name="toggle_status" value="1" />
                    <input type="hidden" name="job_id" value="<?php echo $j['id']; ?>" />
                    <input type="hidden" name="current_status" value="<?php echo $j['status']; ?>" />
                    <button type="submit" class="btn btn-outline btn-sm" id="ajob-toggle-<?php echo $j['id']; ?>">
                      <?php
                      if ($j['status'] == 'active') {
                          echo 'Deactivate';
                      } else {
                          echo 'Activate';
                      }
                      ?>
                    </button>
                  </form>

                  <!-- delete button -->
                  <form method="POST" id="adel-job-form-<?php echo $j['id']; ?>"
                        onsubmit="confirmDelete(this); return false;">
                    <input type="hidden" name="delete_job" value="1" />
                    <input type="hidden" name="job_id" value="<?php echo $j['id']; ?>" />
                    <button type="submit" class="btn btn-danger btn-sm" id="ajob-del-<?php echo $j['id']; ?>">
                      <i class="ph ph-trash"></i>
                    </button>
                  </form>

                </div>
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
