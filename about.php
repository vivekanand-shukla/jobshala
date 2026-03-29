<?php
// about us page
// tells the story and mission of jobshala

include 'includes/db.php';
include 'includes/auth.php';

$pageTitle = 'About Us';

// get live stats from database to show on page
$totalUsersResult  = mysqli_query($conn, "SELECT COUNT(*) AS c FROM users");
$totalUsersRow     = mysqli_fetch_assoc($totalUsersResult);
$totalUsers        = $totalUsersRow['c'];

$totalJobsResult   = mysqli_query($conn, "SELECT COUNT(*) AS c FROM jobs WHERE status = 'active'");
$totalJobsRow      = mysqli_fetch_assoc($totalJobsResult);
$totalJobs         = $totalJobsRow['c'];

$totalAppsResult   = mysqli_query($conn, "SELECT COUNT(*) AS c FROM applications");
$totalAppsRow      = mysqli_fetch_assoc($totalAppsResult);
$totalApplications = $totalAppsRow['c'];

include 'includes/header.php';
?>

<!-- hero banner -->
<section id="about-hero" style="background:linear-gradient(135deg,#0a3d2e 0%,#0d6e4f 60%,#10b981 100%);padding:64px 0 72px;position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;opacity:.04;background-image:radial-gradient(#fff 1px,transparent 1px);background-size:28px 28px;"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:100px;padding:6px 18px;margin-bottom:20px;">
      <i class="ph ph-info" style="color:#34d399;font-size:.9rem;"></i>
      <span style="color:rgba(255,255,255,.85);font-size:.82rem;font-weight:600;letter-spacing:.4px;">OUR STORY</span>
    </div>
    <h1 id="about-hero-title" style="font-family:var(--font-head);font-size:clamp(1.9rem,4vw,2.9rem);font-weight:800;color:#fff;margin-bottom:14px;line-height:1.2;">
      We Are Building the Future<br>of Job Search in India
    </h1>
    <p id="about-hero-sub" style="color:rgba(255,255,255,.75);font-size:1.05rem;max-width:540px;margin:0 auto;">
      Jobshala was created with one simple mission — to make finding a job or hiring talent as easy and free as possible for everyone.
    </p>
  </div>
</section>

<!-- live stats from database -->
<section id="about-stats" style="padding:48px 0;background:#fff;border-bottom:1px solid var(--border);">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:24px;text-align:center;" id="about-stats-grid">

      <div id="astat-users">
        <div style="font-family:var(--font-head);font-size:2.4rem;font-weight:800;color:var(--brand-teal);">
          <?php echo $totalUsers; ?>+
        </div>
        <div style="color:var(--text-secondary);font-weight:600;margin-top:4px;">Registered Users</div>
      </div>

      <div id="astat-jobs">
        <div style="font-family:var(--font-head);font-size:2.4rem;font-weight:800;color:var(--brand-teal);">
          <?php echo $totalJobs; ?>+
        </div>
        <div style="color:var(--text-secondary);font-weight:600;margin-top:4px;">Active Jobs</div>
      </div>

      <div id="astat-apps">
        <div style="font-family:var(--font-head);font-size:2.4rem;font-weight:800;color:var(--brand-teal);">
          <?php echo $totalApplications; ?>+
        </div>
        <div style="color:var(--text-secondary);font-weight:600;margin-top:4px;">Applications Sent</div>
      </div>

      <div id="astat-free">
        <div style="font-family:var(--font-head);font-size:2.4rem;font-weight:800;color:var(--brand-teal);">
          100%
        </div>
        <div style="color:var(--text-secondary);font-weight:600;margin-top:4px;">Free to Use</div>
      </div>

    </div>
  </div>
</section>

<!-- our mission section -->
<section id="about-mission" style="padding:64px 0;">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;" id="about-mission-grid">

      <!-- left text -->
      <div id="about-mission-text">
        <div style="display:inline-flex;align-items:center;gap:6px;background:#d1fae5;border-radius:100px;padding:5px 14px;margin-bottom:16px;">
          <i class="ph ph-target" style="color:#065f46;font-size:.85rem;"></i>
          <span style="color:#065f46;font-size:.8rem;font-weight:700;">OUR MISSION</span>
        </div>
        <h2 style="font-family:var(--font-head);font-size:1.6rem;font-weight:800;margin-bottom:16px;line-height:1.3;" id="mission-title">
          Bridging the Gap Between Talent and Opportunity
        </h2>
        <p style="color:var(--text-secondary);line-height:1.8;margin-bottom:16px;" id="mission-p1">
          In India, millions of fresh graduates and experienced professionals struggle to find jobs while thousands of companies struggle to find the right candidates. Jobshala solves both problems from one platform.
        </p>
        <p style="color:var(--text-secondary);line-height:1.8;" id="mission-p2">
          We believe that your next big career move should not depend on who you know but on what you can do. Our platform gives equal opportunity to every job seeker regardless of background.
        </p>
        <div style="margin-top:24px;display:flex;flex-direction:column;gap:10px;" id="mission-points">
          <div style="display:flex;align-items:flex-start;gap:10px;">
            <div style="width:28px;height:28px;background:#d1fae5;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">
              <i class="ph ph-check" style="color:#065f46;font-size:.9rem;"></i>
            </div>
            <div>
              <div style="font-weight:600;font-size:.9rem;">Free for Everyone</div>
              <div style="font-size:.82rem;color:var(--text-muted);">No hidden charges for seekers or employers</div>
            </div>
          </div>
          <div style="display:flex;align-items:flex-start;gap:10px;">
            <div style="width:28px;height:28px;background:#d1fae5;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">
              <i class="ph ph-check" style="color:#065f46;font-size:.9rem;"></i>
            </div>
            <div>
              <div style="font-weight:600;font-size:.9rem;">Transparent Process</div>
              <div style="font-size:.82rem;color:var(--text-muted);">Applicants see real-time status of their applications</div>
            </div>
          </div>
          <div style="display:flex;align-items:flex-start;gap:10px;">
            <div style="width:28px;height:28px;background:#d1fae5;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:1px;">
              <i class="ph ph-check" style="color:#065f46;font-size:.9rem;"></i>
            </div>
            <div>
              <div style="font-weight:600;font-size:.9rem;">Built for Freshers Too</div>
              <div style="font-size:.82rem;color:var(--text-muted);">Internships and entry-level roles always available</div>
            </div>
          </div>
        </div>
      </div>

      <!-- right visual cards -->
      <div id="about-mission-visual" style="background:linear-gradient(135deg,#f0fdf9,#d1fae5);border-radius:var(--radius-xl);padding:40px;border:1.5px solid #a7f3d0;display:flex;flex-direction:column;gap:16px;">
        <div style="background:#fff;border-radius:var(--radius-md);padding:20px;border:1px solid var(--border);display:flex;align-items:center;gap:14px;" id="mv-card-1">
          <div style="width:44px;height:44px;background:#d1fae5;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="ph ph-briefcase" style="color:#065f46;font-size:1.2rem;"></i>
          </div>
          <div>
            <div style="font-weight:700;font-size:.9rem;">Post a Job in Minutes</div>
            <div style="font-size:.8rem;color:var(--text-muted);">Employers can go live with a job posting in under 5 minutes</div>
          </div>
        </div>
        <div style="background:#fff;border-radius:var(--radius-md);padding:20px;border:1px solid var(--border);display:flex;align-items:center;gap:14px;" id="mv-card-2">
          <div style="width:44px;height:44px;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="ph ph-magnifying-glass" style="color:#1d4ed8;font-size:1.2rem;"></i>
          </div>
          <div>
            <div style="font-weight:700;font-size:.9rem;">Find Jobs Instantly</div>
            <div style="font-size:.8rem;color:var(--text-muted);">Powerful search and filters to find your dream job fast</div>
          </div>
        </div>
        <div style="background:#fff;border-radius:var(--radius-md);padding:20px;border:1px solid var(--border);display:flex;align-items:center;gap:14px;" id="mv-card-3">
          <div style="width:44px;height:44px;background:#fef3c7;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="ph ph-check-circle" style="color:#92400e;font-size:1.2rem;"></i>
          </div>
          <div>
            <div style="font-weight:700;font-size:.9rem;">Track Every Application</div>
            <div style="font-size:.8rem;color:var(--text-muted);">Always know where you stand in the hiring process</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- our values section -->
<section id="about-values" style="background:#f0fdf9;padding:60px 0;">
  <div class="container">
    <div style="text-align:center;margin-bottom:40px;">
      <h2 style="font-family:var(--font-head);font-size:1.6rem;font-weight:800;margin-bottom:10px;" id="values-title">Our Values</h2>
      <p style="color:var(--text-secondary);" id="values-sub">The principles that guide everything we build</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px;" id="values-grid">

      <div id="val-1" style="background:#fff;border-radius:var(--radius-md);padding:28px 20px;text-align:center;border:1.5px solid var(--border);">
        <div style="font-size:2rem;margin-bottom:12px;">&#x1F91D;</div>
        <div style="font-family:var(--font-head);font-weight:700;margin-bottom:8px;">Trust</div>
        <div style="font-size:.85rem;color:var(--text-secondary);line-height:1.6;">We build honest relationships between employers and job seekers.</div>
      </div>

      <div id="val-2" style="background:#fff;border-radius:var(--radius-md);padding:28px 20px;text-align:center;border:1.5px solid var(--border);">
        <div style="font-size:2rem;margin-bottom:12px;">&#x1F30D;</div>
        <div style="font-family:var(--font-head);font-weight:700;margin-bottom:8px;">Inclusion</div>
        <div style="font-size:.85rem;color:var(--text-secondary);line-height:1.6;">Every person deserves a fair chance at finding their dream career.</div>
      </div>

      <div id="val-3" style="background:#fff;border-radius:var(--radius-md);padding:28px 20px;text-align:center;border:1.5px solid var(--border);">
        <div style="font-size:2rem;margin-bottom:12px;">&#x1F4A1;</div>
        <div style="font-family:var(--font-head);font-weight:700;margin-bottom:8px;">Innovation</div>
        <div style="font-size:.85rem;color:var(--text-secondary);line-height:1.6;">We keep improving our platform to make hiring better for everyone.</div>
      </div>

      <div id="val-4" style="background:#fff;border-radius:var(--radius-md);padding:28px 20px;text-align:center;border:1.5px solid var(--border);">
        <div style="font-size:2rem;margin-bottom:12px;">&#x1F680;</div>
        <div style="font-family:var(--font-head);font-weight:700;margin-bottom:8px;">Growth</div>
        <div style="font-size:.85rem;color:var(--text-secondary);line-height:1.6;">We celebrate every person who lands a job or builds their team with us.</div>
      </div>

    </div>
  </div>
</section>

<!-- bottom cta -->
<section id="about-cta" style="padding:60px 0;">
  <div class="container">
    <div style="background:linear-gradient(135deg,#0a3d2e,#10b981);border-radius:var(--radius-xl);padding:48px 40px;text-align:center;" id="about-cta-box">
      <h2 style="font-family:var(--font-head);color:#fff;font-size:1.8rem;font-weight:800;margin-bottom:10px;" id="about-cta-title">Be Part of the Jobshala Family</h2>
      <p style="color:rgba(255,255,255,.8);margin-bottom:28px;" id="about-cta-sub">Whether you are a job seeker or an employer, there is a place for you here</p>
      <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
        <a href="/jobshala/signup.php" class="btn btn-white btn-lg" id="about-signup-btn">Join for Free</a>
        <a href="/jobshala/contact.php" class="btn btn-lg" style="border:2px solid rgba(255,255,255,.5);color:#fff;background:transparent;" id="about-contact-btn">Contact Us</a>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>