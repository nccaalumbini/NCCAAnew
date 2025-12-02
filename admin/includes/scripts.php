    <script>
        // Professional Sidebar Logic
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const overlay = document.getElementById('overlay');
        const mainContent = document.getElementById('main-content');

        function toggleSidebar() {
            const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
            
            if (isCollapsed) {
                sidebar.classList.remove('sidebar-collapsed');
                mainContent.classList.remove('ml-[70px]');
                mainContent.classList.add('ml-64');
                toggleBtn.innerHTML = '<i class="fas fa-chevron-left text-sm"></i>';
            } else {
                sidebar.classList.add('sidebar-collapsed');
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-[70px]');
                toggleBtn.innerHTML = '<i class="fas fa-chevron-right text-sm"></i>';
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleSidebar);
        }
        
        if (mobileBtn) {
            mobileBtn.addEventListener('click', () => {
                sidebar.classList.add('open');
                overlay.classList.remove('hidden');
            });
        }
        
        if (overlay) {
            overlay.addEventListener('click', () => {
                sidebar.classList.remove('open');
                overlay.classList.add('hidden');
            });
        }

        // Handle responsive behavior
        function handleResize() {
            if (window.innerWidth < 1024) {
                sidebar.classList.remove('sidebar-collapsed');
                mainContent.classList.remove('ml-64', 'ml-[70px]');
                mainContent.classList.add('ml-0');
            } else {
                mainContent.classList.remove('ml-0');
                if (sidebar.classList.contains('sidebar-collapsed')) {
                    mainContent.classList.add('ml-[70px]');
                } else {
                    mainContent.classList.add('ml-64');
                }
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize();
    </script>