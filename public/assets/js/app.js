document.addEventListener('DOMContentLoaded', function () {
    // Auto-dismiss alerts
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.4s';
            alert.style.opacity = '0';
            setTimeout(function () { alert.remove(); }, 400);
        }, 4000);
    });

    // Quantity buttons
    document.querySelectorAll('.qty-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = this.parentElement.querySelector('.qty-input');
            if (!input) return;
            var val = parseInt(input.value, 10) || 1;
            var min = parseInt(input.getAttribute('min'), 10) || 1;
            var max = parseInt(input.getAttribute('max'), 10) || 999;

            if (this.classList.contains('qty-minus')) {
                input.value = Math.max(min, val - 1);
            } else if (this.classList.contains('qty-plus')) {
                input.value = Math.min(max, val + 1);
            }
        });
    });

    // Sidebar toggle for mobile
    var toggleBtn = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('adminSidebar');
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
        });
    }
});
