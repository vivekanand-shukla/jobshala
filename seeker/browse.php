<?php
// browse jobs page
// job seekers can search and filter jobs here

include '../includes/db.php';
include '../includes/auth.php';

$pageTitle = 'Browse Jobs';

// get filter values from url using $_GET
$searchQuery = '';
$category    = '';
$jobType     = '';
$location    = '';
$workMode    = '';
$salMax      = 100000;

if (isset($_GET['q']))         $searchQuery = trim($_GET['q']);
if (isset($_GET['category']))  $category    = trim($_GET['category']);
if (isset($_GET['type']))      $jobType     = trim($_GET['type']);
if (isset($_GET['location']))  $location    = trim($_GET['location']);
if (isset($_GET['work_mode'])) $workMode    = trim($_GET['work_mode']);
if (isset($_GET['sal_max']))   $salMax      = intval($_GET['sal_max']);

// build sql query based on filters
$whereConditions = array();
$whereConditions[] = "jobs.status = 'active'";

if ($searchQuery != '') {
    $whereConditions[] = "(jobs.title LIKE '%$searchQuery%' OR jobs.description LIKE '%$searchQuery%' OR users.company_name LIKE '%$searchQuery%')";
}
if ($category != '') {
    $whereConditions[] = "jobs.category = '$category'";
}
if ($jobType != '') {
    $whereConditions[] = "jobs.job_type = '$jobType'";
}
if ($location != '') {
    $whereConditions[] = "jobs.location LIKE '%$location%'";
}
if ($workMode != '') {
    $whereConditions[] = "jobs.work_mode = '$workMode'";
}

// join all conditions with AND
$whereSQL = implode(' AND ', $whereConditions);

// run the query
$sql        = "SELECT jobs.*, users.company_name, users.full_name AS provider_name FROM jobs JOIN users ON jobs.provider_id = users.id WHERE $whereSQL ORDER BY jobs.created_at DESC";
$jobsResult = mysqli_query($conn, $sql);

// list of categories and job types for filter sidebar
$categories = array('Tech', 'Marketing', 'Design', 'Finance', 'Operations', 'Media', 'Legal', 'Healthcare');
$jobTypes   = array('full-time' => 'Full Time', 'part-time' => 'Part Time', 'internship' => 'Internship', 'freelance' => 'Freelance');
$workModes  = array('on-site' => 'On-Site', 'remote' => 'Remote', 'hybrid' => 'Hybrid');

include '../includes/header.php';
?>

<div id="browse-layout" class="container">

  <!-- sidebar with filters -->
  <aside id="browse-sidebar">

    <!-- category filter -->
    <div class="filter-card" id="filter-category">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
        <h4 style="margin:0;">Category</h4>
        <i class="ph ph-caret-up" style="color:var(--text-muted);"></i>
      </div>
      <?php
      // show each category as a radio button
      foreach ($categories as $cat) {
          $catValue = strtolower($cat);
          $checked  = '';
          if ($category == $catValue) {
              $checked = 'checked';
          }
          echo '<label class="filter-option" id="fcat-' . $catValue . '">';
          echo '<input type="radio" name="cat_filter" value="' . $catValue . '" ' . $checked . ' onchange="applyFilter(\'category\',\'' . $catValue . '\')" />';
          echo $cat;
          echo '</label>';
      }
      ?>
    </div>

    <!-- job type filter -->
    <div class="filter-card" id="filter-job-type">
      <h4>Job Type</h4>
      <?php
      foreach ($jobTypes as $val => $label) {
          $checked = '';
          if ($jobType == $val) {
              $checked = 'checked';
          }
          echo '<label class="filter-option" id="fjt-' . $val . '">';
          echo '<input type="radio" name="type_filter" value="' . $val . '" ' . $checked . ' onchange="applyFilter(\'type\',\'' . $val . '\')" />';
          echo $label;
          echo '</label>';
      }
      ?>
    </div>

    <!-- salary range filter -->
    <div class="filter-card" id="filter-salary">
      <h4>Salary Range (Max)</h4>
      <div class="salary-range-wrap">
        <input type="range" id="salary-range" min="0" max="100000"
               value="<?php echo $salMax; ?>" step="1000"
               onchange="applyFilter('sal_max',this.value)" />
        <div class="salary-labels">
          <span>&#8377;0</span>
          <span id="salary-display">&#8377;<?php echo number_format($salMax); ?></span>
          <span>&#8377;1L</span>
        </div>
      </div>
    </div>

    <!-- work mode filter -->
    <div class="filter-card" id="filter-work-mode">
      <h4>Work Mode</h4>
      <label class="filter-option" id="fwm-all">
        <input type="radio" name="wm_filter" value=""
               <?php if ($workMode == '') echo 'checked'; ?>
               onchange="applyFilter('work_mode','')" /> All
      </label>
      <?php
      foreach ($workModes as $val => $label) {
          $checked = '';
          if ($workMode == $val) {
              $checked = 'checked';
          }
          echo '<label class="filter-option" id="fwm-' . $val . '">';
          echo '<input type="radio" name="wm_filter" value="' . $val . '" ' . $checked . ' onchange="applyFilter(\'work_mode\',\'' . $val . '\')" />';
          echo $label;
          echo '</label>';
      }
      ?>
    </div>

    <a href="/jobshala/seeker/browse.php" class="btn btn-outline btn-sm w-full"
       id="clear-filters-btn" style="margin-top:4px;">Clear Filters</a>

  </aside>

  <!-- main jobs area -->
  <div id="browse-main">

    <div class="browse-top" id="browse-top">
      <h2 style="font-family:var(--font-head);font-size:1.2rem;font-weight:700;" id="browse-heading">
        Browse Jobs
        <span style="font-size:.85rem;color:var(--text-muted);font-weight:500;margin-left:8px;" id="job-count">
          (<?php echo mysqli_num_rows($jobsResult); ?> found)
        </span>
      </h2>
      <!-- search form -->
      <form id="browse-search-form" method="GET" action="/jobshala/seeker/browse.php"
            style="display:flex;gap:8px;align-items:center;">
        <div class="browse-search-bar" id="browse-search-bar">
          <i class="ph ph-magnifying-glass" style="color:var(--text-muted);"></i>
          <input type="text" name="q" id="browse-q" placeholder="Search jobs..."
                 value="<?php echo $searchQuery; ?>" />
        </div>
        <button type="submit" class="btn btn-primary btn-sm" id="browse-search-btn">Search</button>
      </form>
    </div>

    <?php
    if (mysqli_num_rows($jobsResult) > 0) {
        echo '<div class="jobs-grid" id="browse-jobs-grid">';

        while ($job = mysqli_fetch_assoc($jobsResult)) {
            $companyName = $job['company_name'];
            if ($companyName == '') {
                $companyName = $job['provider_name'];
            }
            $firstLetter = strtoupper(substr($companyName, 0, 1));

            $jobLocation = $job['location'];
            if ($jobLocation == '') {
                $jobLocation = 'Remote';
            }
    ?>
        <a href="/jobshala/seeker/job-detail.php?id=<?php echo $job['id']; ?>"
           class="job-card card-hover" id="bjob-<?php echo $job['id']; ?>">
          <div class="jc-top">
            <div class="company-logo" id="blogos-<?php echo $job['id']; ?>">
              <?php echo $firstLetter; ?>
            </div>
            <div class="jc-info">
              <h3 id="bjt-<?php echo $job['id']; ?>"><?php echo $job['title']; ?></h3>
              <div class="company-name"><?php echo $companyName; ?></div>
            </div>
          </div>
          <div class="jc-meta" id="bjm-<?php echo $job['id']; ?>">
            <span><i class="ph ph-map-pin"></i><?php echo $jobLocation; ?></span>
            <span><i class="ph ph-briefcase"></i><?php echo ucfirst($job['job_type']); ?></span>
            <?php
            if ($job['category'] != '') {
                echo '<span class="badge badge-teal">' . $job['category'] . '</span>';
            }
            ?>
          </div>
          <div class="jc-footer">
            <span class="salary" id="bsal-<?php echo $job['id']; ?>">
              &#8377;<?php echo number_format($job['salary_min']); ?>-<?php echo number_format($job['salary_max']); ?>
            </span>
            <span class="btn btn-primary btn-sm">Apply Now</span>
          </div>
        </a>
    <?php
        } // end while

        echo '</div>'; // end jobs-grid
    } else {
        echo '<div class="empty-state" id="browse-empty">';
        echo '<div class="empty-icon">🔍</div>';
        echo '<h3>No jobs found</h3>';
        echo '<p>Try different keywords or clear the filters</p>';
        echo '</div>';
    }
    ?>

  </div>
</div>

<script>
// function to update url when filter is changed
function applyFilter(key, value) {
    var url = new URL(window.location.href);
    if (value != '') {
        url.searchParams.set(key, value);
    } else {
        url.searchParams.delete(key);
    }
    window.location.href = url.toString();
}
</script>

<?php include '../includes/footer.php'; ?>
