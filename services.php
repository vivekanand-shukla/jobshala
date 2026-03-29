<?php
// services page
// describes all services jobshala provides

include 'includes/db.php';
include 'includes/auth.php';

$pageTitle = 'Our Services';

include 'includes/header.php';
?>

<!-- hero banner for services page -->
<section id="services-hero" style="background:linear-gradient(135deg,#0a3d2e 0%,#0d6e4f 60%,#10b981 100%);padding:64px 0 72px;position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;opacity:.04;background-image:radial-gradient(#fff 1px,transparent 1px);background-size:28px 28px;"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:100px;padding:6px 18px;margin-bottom:20px;">
      <i class="ph ph-star" style="color:#34d399;font-size:.9rem;"></i>
      <span style="color:rgba(255,255,255,.85);font-size:.82rem;font-weight:600;letter-spacing:.4px;">WHAT WE OFFER</span>
    </div>
    <h1 id="services-hero-title" style="font-family:var(--font-head);font-size:clamp(1.9rem,4vw,2.9rem);font-weight:800;color:#fff;margin-bottom:14px;line-height:1.2;">
      Everything You Need to<br>Grow Your Career
    </h1>
    <p id="services-hero-sub" style="color:rgba(255,255,255,.75);font-size:1.05rem;max-width:520px;margin:0 auto 28px;">
      Jobshala is not just a job portal. We provide complete tools for job seekers and employers to connect, grow and succeed.
    </p>
    <a href="/jobshala/signup.php" class="btn btn-white btn-lg" id="services-cta-btn">Get Started for Free</a>
  </div>
</section>

<!-- core services section -->
<section id="services-main" style="padding:64px 0 40px;">
  <div class="container">

    <div style="text-align:center;margin-bottom:48px;" id="services-intro">
      <h2 id="services-section-title" style="font-family:var(--font-head);font-size:1.7rem;font-weight:800;margin-bottom:10px;">Our Core Services</h2>
      <p style="color:var(--text-secondary);max-width:480px;margin:0 auto;">
        Designed to connect the right talent with the right opportunity, faster and smarter.
      </p>
    </div>

    <!-- services cards grid -->
    <div id="services-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:24px;">

      <!-- service 1 - job search -->
      <div class="card card-pad" id="service-job-search" style="border-top:3px solid #10b981;">
        <div style="width:52px;height:52px;background:#d1fae5;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
          <i class="ph ph-magnifying-glass" style="font-size:1.5rem;color:#065f46;"></i>
        </div>
        <h3 style="font-family:var(--font-head);font-size:1.1rem;font-weight:700;margin-bottom:8px;" id="s1-title">Smart Job Search</h3>
        <p style="color:var(--text-secondary);font-size:.9rem;line-height:1.7;" id="s1-desc">
          Search thousands of jobs using keywords, location, salary range, job type and work mode filters. Find the perfect match within seconds using our powerful search system.
        </p>
        <div style="margin-top:16px;display:flex;flex-direction:column;gap:7px;" id="s1-list">
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Filter by category, salary, location
          </div>
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Search full-time, part-time, internships
          </div>
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Remote, hybrid and on-site options
          </div>
        </div>
      </div>

      <!-- service 2 - job posting -->
      <div class="card card-pad" id="service-job-posting" style="border-top:3px solid #06b6d4;">
        <div style="width:52px;height:52px;background:#e0f2fe;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
          <i class="ph ph-plus-circle" style="font-size:1.5rem;color:#0369a1;"></i>
        </div>
        <h3 style="font-family:var(--font-head);font-size:1.1rem;font-weight:700;margin-bottom:8px;" id="s2-title">Free Job Posting</h3>
        <p style="color:var(--text-secondary);font-size:.9rem;line-height:1.7;" id="s2-desc">
          Employers and companies can post job openings completely free of charge. Reach thousands of active job seekers and interns across India with just a few clicks.
        </p>
        <div style="margin-top:16px;display:flex;flex-direction:column;gap:7px;" id="s2-list">
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Post unlimited jobs for free
          </div>
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Set salary, skills and qualifications
          </div>
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Edit or remove job anytime
          </div>
        </div>
      </div>

      <!-- service 3 - applicant tracking -->
      <div class="card card-pad" id="service-tracking" style="border-top:3px solid #8b5cf6;">
        <div style="width:52px;height:52px;background:#ede9fe;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
          <i class="ph ph-users" style="font-size:1.5rem;color:#5b21b6;"></i>
        </div>
        <h3 style="font-family:var(--font-head);font-size:1.1rem;font-weight:700;margin-bottom:8px;" id="s3-title">Applicant Management</h3>
        <p style="color:var(--text-secondary);font-size:.9rem;line-height:1.7;" id="s3-desc">
          Employers can view all applicants for every job, check their resumes and portfolios, and update their status to selected, pending or rejected in real time.
        </p>
        <div style="margin-top:16px;display:flex;flex-direction:column;gap:7px;" id="s3-list">
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> View all applicants per job
          </div>
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Check resume and portfolio links
          </div>
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Change status: selected / rejected
          </div>
        </div>
      </div>

      <!-- service 4 - application tracking for seeker -->
      <div class="card card-pad" id="service-application" style="border-top:3px solid #f59e0b;">
        <div style="width:52px;height:52px;background:#fef3c7;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
          <i class="ph ph-file-text" style="font-size:1.5rem;color:#92400e;"></i>
        </div>
        <h3 style="font-family:var(--font-head);font-size:1.1rem;font-weight:700;margin-bottom:8px;" id="s4-title">Application Tracking</h3>
        <p style="color:var(--text-secondary);font-size:.9rem;line-height:1.7;" id="s4-desc">
          Job seekers can track all their job applications from one place. See which applications are pending, selected or rejected and never lose track of your progress.
        </p>
        <div style="margin-top:16px;display:flex;flex-direction:column;gap:7px;" id="s4-list">
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> See all applied jobs in one dashboard
          </div>
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Real-time status updates
          </div>
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Filter by pending, selected, rejected
          </div>
        </div>
      </div>

      <!-- service 5 - internships -->
      <div class="card card-pad" id="service-internship" style="border-top:3px solid #10b981;">
        <div style="width:52px;height:52px;background:#d1fae5;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
          <i class="ph ph-student" style="font-size:1.5rem;color:#065f46;"></i>
        </div>
        <h3 style="font-family:var(--font-head);font-size:1.1rem;font-weight:700;margin-bottom:8px;" id="s5-title">Internship Portal</h3>
        <p style="color:var(--text-secondary);font-size:.9rem;line-height:1.7;" id="s5-desc">
          Specially designed for freshers and students looking for their first opportunity. Browse internships from top companies and build your career from day one.
        </p>
        <div style="margin-top:16px;display:flex;flex-direction:column;gap:7px;" id="s5-list">
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Internships for all streams
          </div>
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Stipend-based and unpaid listings
          </div>
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Work from home options available
          </div>
        </div>
      </div>

      <!-- service 6 - admin control -->
      <div class="card card-pad" id="service-admin" style="border-top:3px solid #ef4444;">
        <div style="width:52px;height:52px;background:#fee2e2;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
          <i class="ph ph-shield-check" style="font-size:1.5rem;color:#991b1b;"></i>
        </div>
        <h3 style="font-family:var(--font-head);font-size:1.1rem;font-weight:700;margin-bottom:8px;" id="s6-title">Admin Control Panel</h3>
        <p style="color:var(--text-secondary);font-size:.9rem;line-height:1.7;" id="s6-desc">
          A secure admin panel with dual password protection to manage all users, jobs and applications across the entire platform. Full control in one place.
        </p>
        <div style="margin-top:16px;display:flex;flex-direction:column;gap:7px;" id="s6-list">
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Manage all users and jobs
          </div>
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Dual password security login
          </div>
          <div style="display:flex;align-items:center;gap:8px;font-size:.85rem;color:var(--text-secondary);">
            <i class="ph ph-check-circle" style="color:#10b981;font-size:1rem;flex-shrink:0;"></i> Activate or deactivate job listings
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- why choose us section -->
<section id="why-us" style="background:#f0fdf9;padding:60px 0;">
  <div class="container">
    <div style="text-align:center;margin-bottom:40px;">
      <h2 style="font-family:var(--font-head);font-size:1.6rem;font-weight:800;margin-bottom:10px;" id="why-us-title">Why Choose Jobshala?</h2>
      <p style="color:var(--text-secondary);" id="why-us-sub">We are built for India's growing workforce</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px;" id="why-us-grid">

      <div id="wu-1" style="text-align:center;padding:28px 20px;background:#fff;border-radius:var(--radius-md);border:1.5px solid var(--border);">
        <div style="font-size:2.2rem;margin-bottom:12px;">&#x1F193;</div>
        <div style="font-family:var(--font-head);font-weight:700;margin-bottom:6px;">100% Free</div>
        <div style="font-size:.85rem;color:var(--text-secondary);">No charges for job seekers or employers. Ever.</div>
      </div>

      <div id="wu-2" style="text-align:center;padding:28px 20px;background:#fff;border-radius:var(--radius-md);border:1.5px solid var(--border);">
        <div style="font-size:2.2rem;margin-bottom:12px;">&#x26A1;</div>
        <div style="font-family:var(--font-head);font-weight:700;margin-bottom:6px;">Fast &amp; Simple</div>
        <div style="font-size:.85rem;color:var(--text-secondary);">Apply to any job in under 2 minutes. No complicated steps.</div>
      </div>

      <div id="wu-3" style="text-align:center;padding:28px 20px;background:#fff;border-radius:var(--radius-md);border:1.5px solid var(--border);">
        <div style="font-size:2.2rem;margin-bottom:12px;">&#x1F512;</div>
        <div style="font-family:var(--font-head);font-weight:700;margin-bottom:6px;">Secure Platform</div>
        <div style="font-size:.85rem;color:var(--text-secondary);">Your data is safe with encrypted passwords and secure sessions.</div>
      </div>

      <div id="wu-4" style="text-align:center;padding:28px 20px;background:#fff;border-radius:var(--radius-md);border:1.5px solid var(--border);">
        <div style="font-size:2.2rem;margin-bottom:12px;">&#x1F1EE;&#x1F1F3;</div>
        <div style="font-family:var(--font-head);font-weight:700;margin-bottom:6px;">Made for India</div>
        <div style="font-size:.85rem;color:var(--text-secondary);">Salaries in INR, jobs across all Indian cities and remote.</div>
      </div>

    </div>
  </div>
</section>

<!-- call to action banner -->
<section id="services-cta" style="padding:60px 0;">
  <div class="container">
    <div style="background:linear-gradient(135deg,#0a3d2e,#10b981);border-radius:var(--radius-xl);padding:48px 40px;text-align:center;" id="services-cta-box">
      <h2 style="font-family:var(--font-head);color:#fff;font-size:1.8rem;font-weight:800;margin-bottom:10px;" id="cta-title">Ready to Get Started?</h2>
      <p style="color:rgba(255,255,255,.8);margin-bottom:28px;" id="cta-sub">Join thousands of job seekers and employers already using Jobshala</p>
      <div style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
        <a href="/jobshala/signup.php" class="btn btn-white btn-lg" id="cta-signup-btn">Create Free Account</a>
        <a href="/jobshala/seeker/browse.php" class="btn btn-lg" style="border:2px solid rgba(255,255,255,.5);color:#fff;background:transparent;" id="cta-browse-btn">Browse Jobs</a>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>