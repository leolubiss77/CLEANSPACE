  </div><!-- /admin-body -->
</div><!-- /admin-main -->
</div><!-- /admin-wrap -->

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const sidebar  = document.querySelector('.admin-sidebar');
const overlay  = document.getElementById('sbOverlay');
const isMobile = () => window.innerWidth <= 768;

function toggleSidebar() {
  if (isMobile()) {
    sidebar.classList.toggle('sb-open');
    overlay.classList.toggle('active');
  } else {
    sidebar.classList.toggle('sb-collapsed');
    localStorage.setItem('sb', sidebar.classList.contains('sb-collapsed') ? '1' : '0');
  }
}

function closeSidebar() {
  sidebar.classList.remove('sb-open');
  overlay.classList.remove('active');
}

// Restore state desktop
if (!isMobile() && localStorage.getItem('sb') === '1') {
  sidebar.classList.add('sb-collapsed');
}

window.addEventListener('resize', () => {
  if (!isMobile()) {
    sidebar.classList.remove('sb-open');
    overlay.classList.remove('active');
  }
});
</script>
<?php if (isset($adminPageScript)): ?>
<script><?= $adminPageScript ?></script>
<?php endif; ?>
</body>
</html>
