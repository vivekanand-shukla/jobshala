<?php
// home page of jobshala
// shows hero section, categories and recent jobs

// include database and auth files
include 'includes/db.php';
include 'includes/auth.php';

$pageTitle = 'Find Your Dream Career';

// get recent jobs from database
$jobsQuery  = "SELECT jobs.*, users.company_name, users.full_name AS provider_name FROM jobs JOIN users ON jobs.provider_id = users.id WHERE jobs.status = 'active' ORDER BY jobs.created_at DESC LIMIT 6";
$jobsResult = mysqli_query($conn, $jobsQuery);

// categories array
$categories = array(
    array('icon' => '💻', 'label' => 'Tech',       'color' => '#dbeafe', 'q' => 'tech'),
    array('icon' => '📢', 'label' => 'Marketing',  'color' => '#fce7f3', 'q' => 'marketing'),
    array('icon' => '🎨', 'label' => 'Design',     'color' => '#ede9fe', 'q' => 'design'),
    array('icon' => '📦', 'label' => 'Operations', 'color' => '#fef9c3', 'q' => 'operations'),
    array('icon' => '🎥', 'label' => 'Media',      'color' => '#fee2e2', 'q' => 'media'),
    array('icon' => '📊', 'label' => 'Finance',    'color' => '#d1fae5', 'q' => 'finance'),
    array('icon' => '⚖️', 'label' => 'Legal',     'color' => '#e0f2fe', 'q' => 'legal'),
    array('icon' => '🏥', 'label' => 'Healthcare', 'color' => '#fce7f3', 'q' => 'healthcare'),
);

// include header
include 'includes/header.php';
?>

<!-- hero section -->
<section id="hero">
  <div class="container hero-content">
    <h1 id="hero-title">Find Your Dream Career</h1>
    <p class="hero-sub" id="hero-sub">Internship or Job Portal — Your next opportunity is one search away</p>

    <!-- search form -->
    <form class="search-bar" id="hero-search-form" action="/jobshala/seeker/browse.php" method="GET">
      <i class="ph ph-magnifying-glass" style="color:#94a3b8;font-size:1.1rem;flex-shrink:0;"></i>
      <input type="text" name="q" id="hero-search-input" placeholder="Title, Skill, Company" />
      <div class="search-divider"></div>
      <div class="location-input">
        <i class="ph ph-map-pin" style="font-size:1rem;"></i>
        <input type="text" name="location" id="hero-location-input" placeholder="Location" />
      </div>
      <button type="submit" class="btn btn-primary" id="hero-find-btn">Find Jobs</button>
    </form>
  </div>
</section>

<!-- categories section -->
<section id="categories">
  <div class="container">
    <h2 id="categories-title">Popular Categories</h2>
    <div class="category-grid" id="category-grid">
      <?php
      // loop through categories array and display each one
      for ($i = 0; $i < count($categories); $i++) {
          $cat = $categories[$i];
          echo '<a href="/jobshala/seeker/browse.php?category=' . $cat['q'] . '" class="category-card" id="cat-' . $cat['q'] . '">';
          echo '<div class="cat-icon" style="background:' . $cat['color'] . ';">' . $cat['icon'] . '</div>';
          echo '<span>' . $cat['label'] . '</span>';
          echo '</a>';
      }
      ?>
    </div>
  </div>
</section>

<!-- recent jobs section -->
<section id="recent-jobs" style="padding:0 0 60px;">
  <div class="container">
    <div class="flex-between mb-24" id="recent-jobs-header">
      <h2 style="font-family:var(--font-head);font-size:1.4rem;font-weight:700;" id="recent-jobs-title">Latest Job Openings</h2>
      <a href="/jobshala/seeker/browse.php" class="btn btn-outline btn-sm" id="view-all-btn">View All</a>
    </div>

    <?php
    // check if there are jobs to show
    if (mysqli_num_rows($jobsResult) > 0) {
        echo '<div class="jobs-grid" id="recent-jobs-grid">';

        // loop through all jobs
        while ($job = mysqli_fetch_assoc($jobsResult)) {
            // get first letter of company name for logo
            $companyName = $job['company_name'];
            if ($companyName == '') {
                $companyName = $job['provider_name'];
            }
            $firstLetter = strtoupper(substr($companyName, 0, 1));
    ?>
        <a href="/jobshala/seeker/job-detail.php?id=<?php echo $job['id']; ?>"
           class="job-card card-hover" id="job-card-<?php echo $job['id']; ?>">
          <div class="jc-top">
            <div class="company-logo" id="logo-<?php echo $job['id']; ?>">
              <?php echo $firstLetter; ?>
            </div>
            <div class="jc-info">
              <h3 id="jt-<?php echo $job['id']; ?>"><?php echo $job['title']; ?></h3>
              <div class="company-name"><?php echo $companyName; ?></div>
            </div>
          </div>
          <div class="jc-meta" id="jm-<?php echo $job['id']; ?>">
            <span><i class="ph ph-map-pin"></i>
              <?php
              if ($job['location'] != '') {
                  echo $job['location'];
              } else {
                  echo 'Remote';
              }
              ?>
            </span>
            <span><i class="ph ph-briefcase"></i><?php echo ucfirst($job['job_type']); ?></span>
            <span><i class="ph ph-buildings"></i><?php echo ucfirst($job['work_mode']); ?></span>
          </div>
          <div class="jc-footer">
            <span class="salary" id="sal-<?php echo $job['id']; ?>">
              &#8377;<?php echo number_format($job['salary_min']); ?>-<?php echo number_format($job['salary_max']); ?>
            </span>
            <span class="btn btn-primary btn-sm">Apply Now</span>
          </div>
        </a>
    <?php
        } // end while loop

        echo '</div>'; // end jobs-grid
    } else {
        // no jobs found
        echo '<div class="empty-state" id="no-jobs-state">';
        echo '<div class="empty-icon">💼</div>';
        echo '<h3>No jobs posted yet</h3>';
        echo '<p>Check back soon for new opportunities!</p>';
        echo '</div>';
    }
    ?>

  </div>
</section>

<!-- advertisement banner -->
<section id="ad-banner" style="padding:0 0 60px;">
  <div class="container">
    <div id="ad-banner-inner" class="brand-gradient" style="border-radius:var(--radius-xl);padding:48px 40px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:24px;">
      <div>
        <h2 style="font-family:var(--font-head);color:#fff;font-size:1.8rem;font-weight:800;margin-bottom:8px;" id="ad-title">Are you a recruiter?</h2>
        <p style="color:rgba(255,255,255,.8);" id="ad-sub">Post jobs for free and reach thousands of job seekers</p>
      </div>
      <a href="/jobshala/signup.php" class="btn btn-white btn-lg" id="ad-cta-btn">Post a Job &rarr;</a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
