<?php
// admin users page
// admin can view and delete all users

include '../includes/db.php';
include 'includes/admin_header.php';

requireAdmin();

$pageTitle = 'Manage Users';
$msg       = '';

// handle delete user
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_user'])) {
        $deleteId = intval($_POST['user_id']);
        if ($deleteId > 0) {
            $deleteQuery = "DELETE FROM users WHERE id = $deleteId";
            mysqli_query($conn, $deleteQuery);
            $msg = 'User deleted successfully.';
        }
    }
}

// get role filter from url
$filter = 'all';
if (isset($_GET['role'])) {
    $filter = $_GET['role'];
}

// build query based on filter
if ($filter == 'seeker') {
    $usersQuery = "SELECT * FROM users WHERE role = 'seeker' ORDER BY created_at DESC";
} else if ($filter == 'provider') {
    $usersQuery = "SELECT * FROM users WHERE role = 'provider' ORDER BY created_at DESC";
} else {
    $usersQuery = "SELECT * FROM users ORDER BY created_at DESC";
}

$usersResult = mysqli_query($conn, $usersQuery);
?>

    <div class="flex-between mb-24" id="users-header">
      <h1 class="dash-page-title" style="margin:0;" id="users-title">Manage Users</h1>
    </div>

    <?php
    if ($msg != '') {
        echo '<div class="alert alert-success mb-16" data-autohide>' . $msg . '</div>';
    }
    ?>

    <!-- filter tabs -->
    <div id="users-tabs" style="display:flex;gap:8px;margin-bottom:20px;">
      <?php
      $tabs = array('all' => 'All', 'seeker' => 'Seekers', 'provider' => 'Providers');
      foreach ($tabs as $val => $label) {
          $btnClass = 'btn-outline';
          if ($filter == $val) {
              $btnClass = 'btn-primary';
          }
          echo '<a href="?role=' . $val . '" class="btn btn-sm ' . $btnClass . '" id="utab-' . $val . '">' . $label . '</a>';
      }
      ?>
    </div>

    <div class="card" id="users-card">
      <div class="data-table-wrap">
        <table class="data-table" id="users-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Company</th>
              <th>Joined</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="users-tbody">
            <?php
            if (mysqli_num_rows($usersResult) == 0) {
                echo '<tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:32px;">No users found.</td></tr>';
            } else {
                $i = 1;
                while ($u = mysqli_fetch_assoc($usersResult)) {
                    $roleBadge = 'badge-green';
                    if ($u['role'] == 'provider') {
                        $roleBadge = 'badge-blue';
                    }
                    $company = $u['company_name'];
                    if ($company == '') $company = '—';
            ?>
            <tr id="user-row-<?php echo $u['id']; ?>">
              <td><?php echo $i; $i++; ?></td>
              <td>
                <div style="font-weight:600;" id="uname-<?php echo $u['id']; ?>"><?php echo $u['full_name']; ?></div>
              </td>
              <td id="uemail-<?php echo $u['id']; ?>" style="font-size:.85rem;"><?php echo $u['email']; ?></td>
              <td>
                <span class="badge <?php echo $roleBadge; ?>" id="urole-<?php echo $u['id']; ?>">
                  <?php echo ucfirst($u['role']); ?>
                </span>
              </td>
              <td id="ucompany-<?php echo $u['id']; ?>" style="font-size:.85rem;"><?php echo $company; ?></td>
              <td id="udate-<?php echo $u['id']; ?>" style="font-size:.82rem;"><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
              <td>
                <form method="POST" id="del-user-form-<?php echo $u['id']; ?>"
                      onsubmit="confirmDelete(this); return false;">
                  <input type="hidden" name="delete_user" value="1" />
                  <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>" />
                  <button type="submit" class="btn btn-danger btn-sm" id="del-user-btn-<?php echo $u['id']; ?>">
                    <i class="ph ph-trash"></i> Delete
                  </button>
                </form>
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
