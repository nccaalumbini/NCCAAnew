<?php
require_once '../includes/config.php';
requireLogin();

// Fetch core stats
$totalForms = (int)$pdo->query("SELECT COUNT(*) FROM cadet_forms")->fetchColumn();
$totalNotices = (int)$pdo->query("SELECT COUNT(*) FROM notices WHERE is_active = 1")->fetchColumn();
$maleCount = (int)$pdo->query("SELECT COUNT(*) FROM cadet_forms WHERE gender = 'पुरुष'")->fetchColumn();
$femaleCount = (int)$pdo->query("SELECT COUNT(*) FROM cadet_forms WHERE gender = 'महिला'")->fetchColumn();

// District stats (top 5)
$districtStats = $pdo->query("
    SELECT district, COUNT(*) as count 
    FROM cadet_forms 
    GROUP BY district 
    ORDER BY count DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Position stats
$postStats = $pdo->query("
    SELECT nccaa_position_applied AS post, COUNT(*) as count 
    FROM cadet_forms 
    GROUP BY nccaa_position_applied 
    ORDER BY count DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Recent forms (for table and activity simulation)
$recentForms = $pdo->query("
    SELECT id, full_name, nccaa_position_applied, district, created_at 
    FROM cadet_forms 
    ORDER BY created_at DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Prepare data for charts (PHP → JS)
$districtLabels = json_encode(array_column($districtStats, 'district'));
$districtCounts = json_encode(array_column($districtStats, 'count'));

$postLabels = json_encode(array_column($postStats, 'post'));
$postCounts = json_encode(array_column($postStats, 'count'));

// Age groups (simulate or fetch if you have age column — here we simulate for demo)
// Replace this logic if you have real age data
$ageGroups = ['१८-२५', '२६-३५', '३६-४५', '४६-५५', '५५+'];
$ageCounts = [320, 450, 280, 150, 47]; // ← replace with real query if needed

// Application status (simulate or query if you have status column)
// Assuming you add a `status` field later, for now simulate
$approved = 620;
$pending = 350;
$rejected = 150;
$canceled = 127;
?>
<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <title>NCCAA Admin - Professional Dashboard</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Nepali:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Libraries -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/countup.js@2.0.8/dist/countUp.umd.js"></script>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                        },
                        accent: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                        },
                        sidebar: { 
                            bg: '#1e293b', 
                            hover: '#334155', 
                            active: '#0ea5e9', 
                            border: '#374151',
                            text: '#f8fafc'
                        },
                        card: { 
                            bg: '#FFFFFF', 
                            shadow: 'rgba(0, 0, 0, 0.05)',
                            border: '#e2e8f0'
                        },
                        table: {
                            header: '#f8fafc',
                            row: '#ffffff',
                            hover: '#f1f5f9',
                            border: '#e2e8f0'
                        }
                    },
                    fontFamily: {
                        sans: ['Noto Sans Nepali', 'system-ui', 'sans-serif']
                    },
                    boxShadow: {
                        'card': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                        'sidebar': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                        'table': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)'
                    },
                    borderRadius: {
                        'card': '8px',
                        'button': '6px'
                    }
                }
            }
        }
    </script>
    <style>
        /* ... (keep all your existing CSS as is) ... */
        body {
            font-family: 'Noto Sans Nepali', system-ui, sans-serif;
            background-color: #f8fafc;
            color: #334155;
        }
        .sidebar {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 50;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .sidebar-collapsed {
            width: 70px !important;
        }
        .sidebar-collapsed .sidebar-text {
            opacity: 0;
            transform: translateX(-10px);
            pointer-events: none;
        }
        .sidebar-collapsed .organization-name {
            opacity: 0;
            transform: translateX(-10px);
        }
        .sidebar-collapsed .stats-section {
            opacity: 0;
            transform: translateX(-10px);
            pointer-events: none;
        }
        .sidebar-collapsed .user-section {
            opacity: 0;
            transform: translateX(-10px);
            pointer-events: none;
        }
        .nav-item {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.25rem;
            position: relative;
            overflow: hidden;
        }
        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(4px);
        }
        .nav-item.active {
            background: linear-gradient(135deg, rgba(14, 165, 233, 0.2), rgba(14, 165, 233, 0.1));
            color: #0ea5e9;
            font-weight: 600;
            border-left: 3px solid #0ea5e9;
        }
        .nav-icon {
            width: 1.25rem;
            text-align: center;
            flex-shrink: 0;
        }
        .sidebar-text {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
        }
        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .chart-container { 
            height: 260px; 
            width: 100%; 
        }
        .table-container {
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .table-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .table-row {
            transition: background-color 0.15s ease;
        }
        .table-row:hover {
            background-color: #f1f5f9;
        }
        .action-btn {
            transition: all 0.2s ease;
            padding: 0.375rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 8px;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
        }
        .sidebar-toggle-btn {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-collapsed .sidebar-toggle-btn {
            transform: rotate(180deg);
        }
        .tooltip {
            position: absolute;
            left: 70px;
            background: #1f2937;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.2s ease;
            z-index: 1000;
        }
        .sidebar-collapsed .nav-item:hover .tooltip {
            opacity: 1;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col lg:flex-row bg-gray-50">
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
            <a href="dashboard.php" class="nav-item active">
                <i class="fas fa-home nav-icon"></i>
                <span class="sidebar-text">ड्यासबोर्ड</span>
                <div class="tooltip">ड्यासबोर्ड</div>
            </a>
            <a href="notices.php" class="nav-item text-sidebar-text hover:text-white">
                <i class="fas fa-bullhorn nav-icon"></i>
                <span class="sidebar-text">सूचनाहरू</span>
                <div class="tooltip">सूचनाहरू</div>
            </a>
            <a href="forms.php" class="nav-item text-sidebar-text hover:text-white">
                <i class="fas fa-file-contract nav-icon"></i>
                <span class="sidebar-text">आवेदनहरू</span>
                <div class="tooltip">आवेदनहरू</div>
            </a>
            <a href="reports.php" class="nav-item text-sidebar-text hover:text-white">
                <i class="fas fa-chart-bar nav-icon"></i>
                <span class="sidebar-text">रिपोर्टहरू</span>
                <div class="tooltip">रिपोर्टहरू</div>
            </a>
            <a href="users.php" class="nav-item text-sidebar-text hover:text-white">
                <i class="fas fa-users nav-icon"></i>
                <span class="sidebar-text">प्रयोगकर्ताहरू</span>
                <div class="tooltip">प्रयोगकर्ताहरू</div>
            </a>
            <a href="settings.php" class="nav-item text-sidebar-text hover:text-white">
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

    <!-- Main Content -->
    <main id="main-content" class="flex-1 ml-64 p-4 lg:p-6 transition-all duration-300">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">प्रशासन ड्यासबोर्ड</h1>
                <p class="text-gray-500 text-sm">National Cadet Corps Alumni Association – Lumbini Province</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" placeholder="खोज्नुहोस्..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <div class="relative">
                    <button class="p-2 rounded-full bg-white shadow-card text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-bell"></i>
                    </button>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                </div>
                <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-md bg-white shadow-card text-gray-700">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-7" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card bg-white rounded-card p-5 shadow-card border border-card-border">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">कुल आवेदनहरू</p>
                        <p id="stat-total" class="text-2xl font-bold mt-1"><?= number_format($totalForms) ?></p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">
                                <i class="fas fa-arrow-up mr-1"></i> —
                            </span>
                        </div>
                    </div>
                    <div class="bg-primary-50 text-primary-500 p-3 rounded-lg">
                        <i class="fas fa-file-invoice text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card bg-white rounded-card p-5 shadow-card border border-card-border">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">सक्रिय सूचनाहरू</p>
                        <p id="stat-notices" class="text-2xl font-bold mt-1"><?= $totalNotices ?></p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">
                                <i class="fas fa-bullhorn mr-1"></i> —
                            </span>
                        </div>
                    </div>
                    <div class="bg-amber-50 text-amber-500 p-3 rounded-lg">
                        <i class="fas fa-bell text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card bg-white rounded-card p-5 shadow-card border border-card-border">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">पुरुष आवेदक</p>
                        <p id="stat-male" class="text-2xl font-bold mt-1"><?= $maleCount ?></p>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: <?= $totalForms ? round(($maleCount / $totalForms) * 100) : 0 ?>%"></div>
                        </div>
                    </div>
                    <div class="bg-blue-50 text-blue-500 p-3 rounded-lg">
                        <i class="fas fa-male text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="stat-card bg-white rounded-card p-5 shadow-card border border-card-border">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm">महिला आवेदक</p>
                        <p id="stat-female" class="text-2xl font-bold mt-1"><?= $femaleCount ?></p>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-2">
                            <div class="bg-purple-500 h-1.5 rounded-full" style="width: <?= $totalForms ? round(($femaleCount / $totalForms) * 100) : 0 ?>%"></div>
                        </div>
                    </div>
                    <div class="bg-purple-50 text-purple-500 p-3 rounded-lg">
                        <i class="fas fa-female text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gender Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-7">
            <div class="bg-white rounded-card p-5 shadow-card border border-card-border" data-aos="zoom-in">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">लिङ्ग अनुसार वितरण</h3>
                </div>
                <div class="chart-container"><canvas id="genderChart"></canvas></div>
            </div>
            <div class="bg-white rounded-card p-5 shadow-card border border-card-border" data-aos="zoom-in" data-aos-delay="150">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">शीर्ष ५ जिल्लाहरू</h3>
                    <a href="forms.php" class="text-xs text-primary-500 hover:underline">सबै हेर्नुहोस्</a>
                </div>
                <div class="chart-container"><canvas id="districtChart"></canvas></div>
            </div>
        </div>

        <!-- Position Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-7">
            <div class="bg-white rounded-card p-5 shadow-card border border-card-border" data-aos="fade-up">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">आवेदन प्रवृत्ति</h3>
                </div>
                <div class="chart-container"><canvas id="trendChart"></canvas></div>
            </div>
            <div class="bg-white rounded-card p-5 shadow-card border border-card-border" data-aos="fade-up" data-aos-delay="100">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">पद अनुसार आवेदन</h3>
                    <a href="forms.php" class="text-xs text-primary-500 hover:underline">सबै हेर्नुहोस्</a>
                </div>
                <div class="chart-container"><canvas id="positionChart"></canvas></div>
            </div>
            <div class="bg-white rounded-card p-5 shadow-card border border-card-border" data-aos="fade-up" data-aos-delay="200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">उमेर वितरण</h3>
                </div>
                <div class="chart-container"><canvas id="ageChart"></canvas></div>
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-7">
            <div class="lg:col-span-2 bg-white rounded-card p-5 shadow-card border border-card-border" data-aos="fade-up">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">हालैका आवेदनहरू</h3>
                    <a href="forms.php" class="text-sm text-primary-500 hover:underline flex items-center">
                        सबै हेर्नुहोस् <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                <div class="table-container">
                    <table class="min-w-full text-sm text-gray-700">
                        <thead class="table-header">
                            <tr>
                                <th class="py-3 px-4 text-left font-medium text-gray-700">नाम</th>
                                <th class="py-3 px-4 text-left font-medium text-gray-700">पद</th>
                                <th class="py-3 px-4 text-left font-medium text-gray-700">जिल्ला</th>
                                <th class="py-3 px-4 text-left font-medium text-gray-700">मिति</th>
                                <th class="py-3 px-4 text-left font-medium text-gray-700">कार्य</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-table-border">
                            <?php foreach ($recentForms as $form): ?>
                            <tr class="table-row">
                                <td class="py-3 px-4">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center text-sm font-medium mr-3">
                                            <?= mb_substr($form['full_name'] ?? 'A', 0, 1, 'UTF-8') ?>
                                        </div>
                                        <?= htmlspecialchars($form['full_name']) ?>
                                    </div>
                                </td>
                                <td class="py-3 px-4"><?= htmlspecialchars($form['nccaa_position_applied'] ?? '-') ?></td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?= htmlspecialchars($form['district'] ?? '-') ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4"><?= date('Y-m-d', strtotime($form['created_at'])) ?></td>
                                <td class="py-3 px-4">
                                    <a href="view_form.php?id=<?= $form['id'] ?>" class="action-btn inline-flex items-center bg-primary-500 text-white hover:bg-primary-600">
                                        <i class="fas fa-eye mr-1"></i> हेर्नुहोस्
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Feed (static for now, or generate from logs if available) -->
            <div class="bg-white rounded-card p-5 shadow-card border border-card-border" data-aos="fade-up" data-aos-delay="200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-800">गतिविधि फिड</h3>
                    <a href="#" class="text-xs text-primary-500 hover:underline">सबै हेर्नुहोस्</a>
                </div>
                <div class="space-y-4">
                    <?php foreach (array_slice($recentForms, 0, 3) as $i => $form): ?>
                    <div class="flex items-start space-x-3">
                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center mt-1">
                            <i class="fas fa-user-plus text-green-600 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm">नयाँ आवेदन दर्ता भयो</p>
                            <p class="text-xs text-gray-500"><?= htmlspecialchars($form['full_name']) ?> - हालै</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        AOS.init({ duration: 600, once: true });

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

        // CountUp (optional, but keeps animation)
        const stats = [
            { id: 'stat-total', value: <?= $totalForms ?> },
            { id: 'stat-notices', value: <?= $totalNotices ?> },
            { id: 'stat-male', value: <?= $maleCount ?> },
            { id: 'stat-female', value: <?= $femaleCount ?> }
        ];
        stats.forEach(({id, value}) => {
            const counter = new countUp.CountUp(id, value, {
                duration: 1.5, separator: ',', decimal: '.'
            });
            if (!counter.error) counter.start();
        });

        // Charts with real data
        document.addEventListener('DOMContentLoaded', () => {
            // Gender
            new Chart(document.getElementById('genderChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['पुरुष', 'महिला'],
                    datasets: [{
                        data: [<?= $maleCount ?>, <?= $femaleCount ?>],
                        backgroundColor: ['rgba(59, 130, 246, 0.85)', 'rgba(139, 92, 246, 0.85)'],
                        borderWidth: 0, borderRadius: 8, spacing: 6
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } }, cutout: '65%' }
            });

            // District
            new Chart(document.getElementById('districtChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: <?= $districtLabels ?>,
                    datasets: [{
                        label: 'आवेदन संख्या',
                        data: <?= $districtCounts ?>,
                        backgroundColor: [
                            'rgba(14, 165, 233, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(99, 102, 241, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(139, 92, 246, 0.8)'
                        ],
                        borderColor: ['rgb(14,165,233)', 'rgb(34,197,94)', 'rgb(99,102,241)', 'rgb(245,158,11)', 'rgb(139,92,246)'],
                        borderWidth: 1, borderRadius: 6, barPercentage: 0.7
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { precision: 0 } },
                        x: { grid: { display: false }, ticks: { autoSkip: false } }
                    }
                }
            });

            // Position
            new Chart(document.getElementById('positionChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: <?= $postLabels ?>,
                    datasets: [{
                        label: 'आवेदन संख्या',
                        data: <?= $postCounts ?>,
                        backgroundColor: [
                            'rgba(14, 165, 233, 0.8)',
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(99, 102, 241, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(139, 92, 246, 0.8)'
                        ],
                        borderWidth: 1, borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                        y: { grid: { display: false } }
                    }
                }
            });

            // Age (static unless you add real age logic)
            new Chart(document.getElementById('ageChart').getContext('2d'), {
                type: 'polarArea',
                data: {
                    labels: <?= json_encode($ageGroups) ?>,
                    datasets: [{
                        data: <?= json_encode($ageCounts) ?>,
                        backgroundColor: [
                            'rgba(14, 165, 233, 0.7)',
                            'rgba(34, 197, 94, 0.7)',
                            'rgba(99, 102, 241, 0.7)',
                            'rgba(245, 158, 11, 0.7)',
                            'rgba(139, 92, 246, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom' } }
                }
            });

            // Trend (use monthly data if available — for now static)
            const months = ['बैशाख', 'जेठ', 'असार', 'श्रावण', 'भाद्र', 'आश्विन', 'कार्तिक', 'मंसिर', 'पौष', 'माघ', 'फागुन', 'चैत्र'];
            const trendData = [85, 92, 78, 94, 112, 105, 130, 145, 128, 140, 155, 183];
            new Chart(document.getElementById('trendChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'आवेदन संख्या',
                        data: trendData,
                        borderColor: 'rgba(14, 165, 233, 1)',
                        backgroundColor: 'rgba(14, 165, 233, 0.1)',
                        tension: 0.3, fill: true,
                        pointBackgroundColor: 'rgba(14, 165, 233, 1)',
                        pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
</body>
</html>