// Admin Layout JS — sidebar toggle and theme management
(function () {
    var container = document.getElementById('admin-container');
    var overlay   = document.getElementById('sidebar-overlay');

    // Sidebar toggle for mobile
    window.toggleAdminSidebar = function () {
        if (!container) return;
        container.classList.toggle('sidebar-open');
        if (overlay) {
            overlay.classList.toggle('hidden');
            overlay.classList.toggle('opacity-0');
        }
    };

    if (overlay) {
        overlay.addEventListener('click', function () {
            container.classList.remove('sidebar-open');
            overlay.classList.add('hidden');
            overlay.classList.add('opacity-0');
        });
    }

    // Dark mode toggle
    window.toggleAdminTheme = function () {
        var isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('admin-theme', isDark ? 'dark' : 'light');
    };
})();
