<?php
// contact us page
// lets users send a message to jobshala team

include 'includes/db.php';
include 'includes/auth.php';

$pageTitle = 'Contact Us';

$msg = '';
$err = '';

// handle contact form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    // simple validation
    if ($name == '' || $email == '' || $subject == '' || $message == '') {
        $err = 'All fields are required.';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err = 'Please enter a valid email address.';
    } else {
        // in a real project you would send email here using mail()
        // for now we just show success message
        $msg = 'Thank you ' . $name . '! Your message has been received. We will get back to you soon.';
    }
}

include 'includes/header.php';
?>

<!-- hero banner -->
<section id="contact-hero" style="background:linear-gradient(135deg,#0a3d2e 0%,#0d6e4f 60%,#10b981 100%);padding:64px 0 72px;position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;opacity:.04;background-image:radial-gradient(#fff 1px,transparent 1px);background-size:28px 28px;"></div>
  <div class="container" style="position:relative;z-index:1;text-align:center;">
    <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);border-radius:100px;padding:6px 18px;margin-bottom:20px;">
      <i class="ph ph-chat-circle" style="color:#34d399;font-size:.9rem;"></i>
      <span style="color:rgba(255,255,255,.85);font-size:.82rem;font-weight:600;letter-spacing:.4px;">GET IN TOUCH</span>
    </div>
    <h1 id="contact-hero-title" style="font-family:var(--font-head);font-size:clamp(1.9rem,4vw,2.9rem);font-weight:800;color:#fff;margin-bottom:14px;line-height:1.2;">
      We Would Love to<br>Hear From You
    </h1>
    <p id="contact-hero-sub" style="color:rgba(255,255,255,.75);font-size:1.05rem;max-width:480px;margin:0 auto;">
      Have a question, suggestion or feedback? Our team is always happy to help. Drop us a message below.
    </p>
  </div>
</section>

<!-- contact info + form section -->
<section id="contact-main" style="padding:64px 0 60px;">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1.6fr;gap:40px;align-items:start;" id="contact-grid">

      <!-- left side contact info cards -->
      <div id="contact-info-col">

        <h2 style="font-family:var(--font-head);font-size:1.3rem;font-weight:800;margin-bottom:6px;" id="contact-info-title">Contact Information</h2>
        <p style="color:var(--text-secondary);font-size:.9rem;margin-bottom:28px;" id="contact-info-sub">
          Reach us through any of the channels below and we will respond within 24 hours.
        </p>

        <div style="display:flex;flex-direction:column;gap:16px;" id="contact-info-cards">

          <div id="ci-email" style="display:flex;align-items:flex-start;gap:14px;background:#f0fdf9;border:1.5px solid #a7f3d0;border-radius:var(--radius-md);padding:18px;">
            <div style="width:42px;height:42px;background:#d1fae5;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="ph ph-envelope" style="color:#065f46;font-size:1.2rem;"></i>
            </div>
            <div>
              <div style="font-weight:700;font-size:.9rem;margin-bottom:3px;">Email Us</div>
              <div style="font-size:.85rem;color:var(--text-secondary);">support@jobshala.com</div>
              <div style="font-size:.8rem;color:var(--text-muted);margin-top:2px;">We reply within 24 hours</div>
            </div>
          </div>

          <div id="ci-phone" style="display:flex;align-items:flex-start;gap:14px;background:#f0fdf9;border:1.5px solid #a7f3d0;border-radius:var(--radius-md);padding:18px;">
            <div style="width:42px;height:42px;background:#d1fae5;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="ph ph-phone" style="color:#065f46;font-size:1.2rem;"></i>
            </div>
            <div>
              <div style="font-weight:700;font-size:.9rem;margin-bottom:3px;">Call Us</div>
              <div style="font-size:.85rem;color:var(--text-secondary);">+91 98765 43210</div>
              <div style="font-size:.8rem;color:var(--text-muted);margin-top:2px;">Mon - Sat, 10am to 6pm</div>
            </div>
          </div>

          <div id="ci-location" style="display:flex;align-items:flex-start;gap:14px;background:#f0fdf9;border:1.5px solid #a7f3d0;border-radius:var(--radius-md);padding:18px;">
            <div style="width:42px;height:42px;background:#d1fae5;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="ph ph-map-pin" style="color:#065f46;font-size:1.2rem;"></i>
            </div>
            <div>
              <div style="font-weight:700;font-size:.9rem;margin-bottom:3px;">Our Office</div>
              <div style="font-size:.85rem;color:var(--text-secondary);">Jobshala HQ, Tech Park</div>
              <div style="font-size:.8rem;color:var(--text-muted);margin-top:2px;">Jabalpur, Madhya Pradesh, India</div>
            </div>
          </div>

          <div id="ci-hours" style="display:flex;align-items:flex-start;gap:14px;background:#f0fdf9;border:1.5px solid #a7f3d0;border-radius:var(--radius-md);padding:18px;">
            <div style="width:42px;height:42px;background:#d1fae5;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="ph ph-clock" style="color:#065f46;font-size:1.2rem;"></i>
            </div>
            <div>
              <div style="font-weight:700;font-size:.9rem;margin-bottom:3px;">Working Hours</div>
              <div style="font-size:.85rem;color:var(--text-secondary);">Monday to Saturday</div>
              <div style="font-size:.8rem;color:var(--text-muted);margin-top:2px;">10:00 AM to 6:00 PM IST</div>
            </div>
          </div>

        </div>
      </div>

      <!-- right side contact form -->
      <div id="contact-form-col">
        <div class="card card-pad" id="contact-form-card">

          <h2 style="font-family:var(--font-head);font-size:1.2rem;font-weight:800;margin-bottom:6px;" id="form-title">Send Us a Message</h2>
          <p style="color:var(--text-secondary);font-size:.88rem;margin-bottom:22px;" id="form-sub">Fill the form below and we will get back to you</p>

          <?php
          if ($msg != '') {
              echo '<div class="alert alert-success" data-autohide id="contact-success">' . $msg . '</div>';
          }
          if ($err != '') {
              echo '<div class="alert alert-error" data-autohide id="contact-error">' . $err . '</div>';
          }
          ?>

          <form method="POST" id="contact-form" action="contact.php">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;" id="contact-row-1">
              <div class="form-group" id="fg-contact-name">
                <label class="form-label" for="contact-name">Your Name</label>
                <div class="input-icon-wrap">
                  <i class="ph ph-user input-icon"></i>
                  <input type="text" class="form-control" id="contact-name" name="name"
                         placeholder="Full name" required
                         value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" />
                </div>
              </div>
              <div class="form-group" id="fg-contact-email">
                <label class="form-label" for="contact-email">Email Address</label>
                <div class="input-icon-wrap">
                  <i class="ph ph-envelope input-icon"></i>
                  <input type="email" class="form-control" id="contact-email" name="email"
                         placeholder="you@email.com" required
                         value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" />
                </div>
              </div>
            </div>

            <div class="form-group" id="fg-contact-subject">
              <label class="form-label" for="contact-subject">Subject</label>
              <div class="input-icon-wrap">
                <i class="ph ph-chat-dots input-icon"></i>
                <input type="text" class="form-control" id="contact-subject" name="subject"
                       placeholder="What is this about?"
                       value="<?php if (isset($_POST['subject'])) echo $_POST['subject']; ?>" required />
              </div>
            </div>

            <div class="form-group" id="fg-contact-message">
              <label class="form-label" for="contact-message">Your Message</label>
              <textarea class="form-control" id="contact-message" name="message"
                        rows="5" placeholder="Write your message here..." required><?php
                if (isset($_POST['message'])) echo $_POST['message'];
              ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-full btn-lg" id="contact-submit-btn">
              <i class="ph ph-paper-plane-tilt"></i> Send Message
            </button>

          </form>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- faq section -->
<section id="contact-faq" style="background:#f0fdf9;padding:60px 0;">
  <div class="container">
    <div style="text-align:center;margin-bottom:36px;">
      <h2 style="font-family:var(--font-head);font-size:1.5rem;font-weight:800;margin-bottom:8px;" id="faq-title">Frequently Asked Questions</h2>
      <p style="color:var(--text-secondary);" id="faq-sub">Quick answers to common questions</p>
    </div>
    <div style="max-width:700px;margin:0 auto;display:flex;flex-direction:column;gap:14px;" id="faq-list">

      <div id="faq-1" style="background:#fff;border-radius:var(--radius-md);padding:20px 24px;border:1.5px solid var(--border);">
        <div style="font-weight:700;margin-bottom:8px;display:flex;align-items:center;gap:10px;">
          <span style="color:var(--brand-teal);">Q.</span> Is Jobshala completely free to use?
        </div>
        <div style="font-size:.9rem;color:var(--text-secondary);line-height:1.7;">
          Yes! Jobshala is 100% free for both job seekers and employers. You can post jobs, browse listings and apply without paying anything.
        </div>
      </div>

      <div id="faq-2" style="background:#fff;border-radius:var(--radius-md);padding:20px 24px;border:1.5px solid var(--border);">
        <div style="font-weight:700;margin-bottom:8px;display:flex;align-items:center;gap:10px;">
          <span style="color:var(--brand-teal);">Q.</span> How do I apply for a job on Jobshala?
        </div>
        <div style="font-size:.9rem;color:var(--text-secondary);line-height:1.7;">
          Create a free job seeker account, browse jobs, click on any job and hit Apply. Fill in your resume link and contact info and submit. It is that simple.
        </div>
      </div>

      <div id="faq-3" style="background:#fff;border-radius:var(--radius-md);padding:20px 24px;border:1.5px solid var(--border);">
        <div style="font-weight:700;margin-bottom:8px;display:flex;align-items:center;gap:10px;">
          <span style="color:var(--brand-teal);">Q.</span> How do I post a job as an employer?
        </div>
        <div style="font-size:.9rem;color:var(--text-secondary);line-height:1.7;">
          Register as a Job Provider, go to your dashboard and click Post New Job. Fill in the job details and it will go live immediately for seekers to find.
        </div>
      </div>

      <div id="faq-4" style="background:#fff;border-radius:var(--radius-md);padding:20px 24px;border:1.5px solid var(--border);">
        <div style="font-weight:700;margin-bottom:8px;display:flex;align-items:center;gap:10px;">
          <span style="color:var(--brand-teal);">Q.</span> Can I track the status of my application?
        </div>
        <div style="font-size:.9rem;color:var(--text-secondary);line-height:1.7;">
          Yes. Login to your seeker account and visit My Applications. You will see the status of every job you applied to — pending, selected or rejected.
        </div>
      </div>

    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>