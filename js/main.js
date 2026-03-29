// js/main.js – Jobshala Global JS

// ── Toggle password visibility ──────────────────────────────
function initPasswordToggles() {
  document.querySelectorAll('.toggle-pw').forEach(btn => {
    btn.addEventListener('click', function () {
      const input = this.closest('.input-icon-wrap').querySelector('input');
      if (!input) return;
      const isText = input.type === 'text';
      input.type = isText ? 'password' : 'text';
      this.innerHTML = isText
        ? '<i class="ph ph-eye"></i>'
        : '<i class="ph ph-eye-slash"></i>';
    });
  });
}

// ── Role toggle on signup ───────────────────────────────────
function initRoleToggle() {
  const btns = document.querySelectorAll('.role-toggle button');
  const roleInput = document.getElementById('role-input');
  const providerFields = document.getElementById('provider-fields');

  if (!btns.length) return;
  btns.forEach(btn => {
    btn.addEventListener('click', function () {
      btns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      const role = this.dataset.role;
      if (roleInput) roleInput.value = role;
      if (providerFields) {
        providerFields.style.display = role === 'provider' ? 'block' : 'none';
      }
    });
  });
}

// ── Flash message auto-dismiss ──────────────────────────────
function initAlerts() {
  document.querySelectorAll('.alert[data-autohide]').forEach(el => {
    setTimeout(() => {
      el.style.transition = 'opacity .4s';
      el.style.opacity = '0';
      setTimeout(() => el.remove(), 400);
    }, 3500);
  });
}

// ── Modal helpers ───────────────────────────────────────────
function openModal(id) {
  const el = document.getElementById(id);
  if (el) el.classList.add('open');
}
function closeModal(id) {
  const el = document.getElementById(id);
  if (el) el.classList.remove('open');
}
function initModals() {
  document.querySelectorAll('[data-open-modal]').forEach(btn => {
    btn.addEventListener('click', () => openModal(btn.dataset.openModal));
  });
  document.querySelectorAll('[data-close-modal]').forEach(btn => {
    btn.addEventListener('click', () => closeModal(btn.dataset.closeModal));
  });
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function (e) {
      if (e.target === this) this.classList.remove('open');
    });
  });
}

// ── Salary range display ────────────────────────────────────
function initSalaryRange() {
  const range = document.getElementById('salary-range');
  const display = document.getElementById('salary-display');
  if (!range || !display) return;
  range.addEventListener('input', function () {
    display.textContent = '₹' + Number(this.value).toLocaleString('en-IN');
  });
}

// ── Active nav link highlight ────────────────────────────────
function highlightNavLink() {
  const path = window.location.pathname;
  document.querySelectorAll('#main-header nav a, #dashboard-sidebar nav a, #admin-sidebar nav a').forEach(a => {
    if (a.getAttribute('href') && path.endsWith(a.getAttribute('href'))) {
      a.classList.add('active');
    }
  });
}

// ── Confirm delete ───────────────────────────────────────────
function confirmDelete(form) {
  if (confirm('Are you sure you want to delete this? This action cannot be undone.')) {
    form.submit();
  }
}

// ── Init everything ─────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
  initPasswordToggles();
  initRoleToggle();
  initAlerts();
  initModals();
  initSalaryRange();
  highlightNavLink();
});
