// Cash Flow Class — main.js

// Sidebar toggle
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('sidebarToggle');
    const toggleTop = document.getElementById('sidebarToggleTop');
    const wrapper   = document.getElementById('wrapper');

    function toggleSidebar() {
        wrapper?.classList.toggle('sidebar-toggled');
        document.querySelector('.sidebar')?.classList.toggle('toggled');
    }

    toggleBtn?.addEventListener('click', toggleSidebar);
    toggleTop?.addEventListener('click', toggleSidebar);

    // Auto-dismiss alerts after 4 seconds
    document.querySelectorAll('.alert-dismissible').forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert?.close();
        }, 4000);
    });

    // Confirm delete on all .btn-hapus
    document.querySelectorAll('[data-confirm]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm || 'Yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });
});
