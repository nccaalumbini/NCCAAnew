<?php
// Get current page for active menu highlighting
$currentPage = basename($_SERVER['PHP_SELF']);

// Get stats for sidebar
$totalForms = (int)$pdo->query("SELECT COUNT(*) FROM cadet_forms")->fetchColumn();
$totalNotices = (int)$pdo->query("SELECT COUNT(*) FROM notices WHERE is_active = 1")->fetchColumn();
?>

<!-- Mobile Overlay -->
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-40 z-40 hidden lg:hidden"></div>

<!-- Professional Sidebar -->
<aside id="sidebar" class="sidebar fixed lg:translate-x-0 h-screen bg-sidebar-bg text-sidebar-text w-64 flex flex-col z-50">
    <div class="p-4 border-b border-sidebar-border flex items-center justify-between">
        <div class="flex items-center overflow-hidden">
            <img src="../public/images/hero.png" alt="NCCAA" class="h-9 w-auto rounded-sm flex-shrink-0">
            <span class="organization-name ml-3 font-bold text-base whitespace-nowrap">NCCAA प्रशासन</span>
        </div>
        <button id="sidebar-toggle" class="sidebar-toggle-btn lg:block hidden text-sidebar-text hover:text-white p-2 rounded-lg bg-sidebar-hover hover:bg-opacity-80">
            <i class="fas fa-chevron-left text-sm"></i>
        </button>
    </div>
    
    <div class="stats-section p-4 border-b border-sidebar-border">
        <div class="bg-sidebar-hover rounded-lg p-3">
            <div class="sidebar-text flex justify-between mb-2">
                <span class="text-sm">कुल आवेदन:</span>
                <span class="font-semibold text-white"><?= number_format($totalForms) ?></span>
            </div>
            <div class="sidebar-text flex justify-between">
                <span class="text-sm">सक्रिय सूचना:</span>
                <span class="font-semibold text-white"><?= $totalNotices ?></span>
            </div>
        </div>
    </div>
    
    <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
        <a href="dashboard.php" class="nav-item <?= $currentPage === 'dashboard.php' ? 'active' : 'text-sidebar-text hover:text-white' ?>">
            <i class="fas fa-home nav-icon"></i>
            <span class="sidebar-text">ड्यासबोर्ड</span>
            <div class="tooltip">ड्यासबोर्ड</div>
        </a>
        <a href="notices.php" class="nav-item <?= $currentPage === 'notices.php' ? 'active' : 'text-sidebar-text hover:text-white' ?>">
            <i class="fas fa-bullhorn nav-icon"></i>
            <span class="sidebar-text">सूचनाहरू</span>
            <div class="tooltip">सूचनाहरू</div>
        </a>
        <a href="forms.php" class="nav-item <?= $currentPage === 'forms.php' ? 'active' : 'text-sidebar-text hover:text-white' ?>">
            <i class="fas fa-file-contract nav-icon"></i>
            <span class="sidebar-text">आवेदनहरू</span>
            <div class="tooltip">आवेदनहरू</div>
        </a>
        <a href="view_form.php" class="nav-item <?= $currentPage === 'view_form.php' ? 'active' : 'text-sidebar-text hover:text-white' ?>" style="display: <?= $currentPage === 'view_form.php' ? 'flex' : 'none' ?>">
            <i class="fas fa-eye nav-icon"></i>
            <span class="sidebar-text">आवेदन विवरण</span>
            <div class="tooltip">आवेदन विवरण</div>
        </a>
        <a href="reports.php" class="nav-item <?= $currentPage === 'reports.php' ? 'active' : 'text-sidebar-text hover:text-white' ?>">
            <i class="fas fa-chart-bar nav-icon"></i>
            <span class="sidebar-text">रिपोर्टहरू</span>
            <div class="tooltip">रिपोर्टहरू</div>
        </a>
        <a href="users.php" class="nav-item <?= $currentPage === 'users.php' ? 'active' : 'text-sidebar-text hover:text-white' ?>">
            <i class="fas fa-users nav-icon"></i>
            <span class="sidebar-text">प्रयोगकर्ताहरू</span>
            <div class="tooltip">प्रयोगकर्ताहरू</div>
        </a>
        <a href="settings.php" class="nav-item <?= $currentPage === 'settings.php' ? 'active' : 'text-sidebar-text hover:text-white' ?>">
            <i class="fas fa-cog nav-icon"></i>
            <span class="sidebar-text">सेटिङहरू</span>
            <div class="tooltip">सेटिङहरू</div>
        </a>
        <div class="border-t border-sidebar-border my-2"></div>
        <a href="logout.php" class="nav-item text-red-300 hover:text-red-100 hover:bg-red-600/20">
            <i class="fas fa-sign-out-alt nav-icon"></i>
            <span class="sidebar-text">लगआउट</span>
            <div class="tooltip">लगआउट</div>
        </a>
    </nav>
    
    <div class="user-section p-4 border-t border-sidebar-border">
        <div class="flex items-center">
            <div class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold flex-shrink-0">
                A
            </div>
            <div class="ml-3 sidebar-text overflow-hidden">
                <p class="text-sm font-medium truncate">प्रशासक</p>
                <p class="text-xs text-sidebar-text/80 truncate">NCCAA Admin</p>
            </div>
        </div>
    </div>
</aside>