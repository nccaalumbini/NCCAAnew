<?php
require_once '../includes/config.php';
requireLogin();

// Get basic stats for reports
$totalForms = (int)$pdo->query("SELECT COUNT(*) FROM cadet_forms")->fetchColumn();
$maleCount = (int)$pdo->query("SELECT COUNT(*) FROM cadet_forms WHERE gender = 'पुरुष'")->fetchColumn();
$femaleCount = (int)$pdo->query("SELECT COUNT(*) FROM cadet_forms WHERE gender = 'महिला'")->fetchColumn();
$totalNotices = (int)$pdo->query("SELECT COUNT(*) FROM notices WHERE is_active = 1")->fetchColumn();

// District wise data
$districtData = $pdo->query("SELECT district, COUNT(*) as count FROM cadet_forms GROUP BY district ORDER BY count DESC LIMIT 10")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCCAA Admin - रिपोर्टहरू</title>
<?php include 'includes/styles.php'; ?>
</head>
<body class="min-h-screen flex flex-col lg:flex-row bg-gray-50">
<?php include 'includes/sidebar.php'; ?>

<main id="main-content" class="flex-1 ml-64 p-4 lg:p-6 transition-all duration-300">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">रिपोर्टहरू</h1>
            <p class="text-gray-500 text-sm">डाटा विश्लेषण र रिपोर्ट जेनेरेसन</p>
        </div>
        <div class="flex items-center space-x-4">
            <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-md bg-white shadow-card text-gray-700">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-card p-6 border border-card-border">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">कुल आवेदन</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $totalForms ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-card p-6 border border-card-border">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-male text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">पुरुष</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $maleCount ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-card p-6 border border-card-border">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-female text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">महिला</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $femaleCount ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-card p-6 border border-card-border">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-bullhorn text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">सूचनाहरू</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $totalNotices ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Application Report -->
        <div class="bg-white rounded-lg shadow-card p-6 border border-card-border hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-lg bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-chart-bar text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">आवेदन रिपोर्ट</h3>
            </div>
            <p class="text-gray-600 mb-4">सबै आवेदनहरूको विस्तृत रिपोर्ट</p>
            <button class="btn-primary w-full" onclick="generateReport('applications')">
                <i class="fas fa-download mr-2"></i>डाउनलोड गर्नुहोस्
            </button>
        </div>

        <!-- District Report -->
        <div class="bg-white rounded-lg shadow-card p-6 border border-card-border hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-lg bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-map-marker-alt text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">जिल्ला रिपोर्ट</h3>
            </div>
            <p class="text-gray-600 mb-4">जिल्ला अनुसार आवेदन वितरण</p>
            <button class="btn-primary w-full" onclick="generateReport('districts')">
                <i class="fas fa-download mr-2"></i>डाउनलोड गर्नुहोस्
            </button>
        </div>

        <!-- Gender Report -->
        <div class="bg-white rounded-lg shadow-card p-6 border border-card-border hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="p-3 rounded-lg bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-venus-mars text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">लिङ्ग रिपोर्ट</h3>
            </div>
            <p class="text-gray-600 mb-4">लिङ्ग अनुसार आवेदन विश्लेषण</p>
            <button class="btn-primary w-full" onclick="generateReport('gender')">
                <i class="fas fa-download mr-2"></i>डाउनलोड गर्नुहोस्
            </button>
        </div>
    </div>

    <!-- District Wise Table -->
    <div class="bg-white rounded-lg shadow-card border border-card-border">
        <div class="px-6 py-4 table-header border-b font-semibold text-gray-800 flex items-center">
            <i class="fas fa-table mr-2 text-primary-600"></i>जिल्ला अनुसार आवेदन वितरण
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="table-header">
                    <tr class="text-left text-gray-700">
                        <th class="px-6 py-4 font-semibold">क्र.सं.</th>
                        <th class="px-6 py-4 font-semibold">जिल्ला</th>
                        <th class="px-6 py-4 font-semibold">आवेदन संख्या</th>
                        <th class="px-6 py-4 font-semibold">प्रतिशत</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-table-border">
                    <?php foreach ($districtData as $index => $district): ?>
                    <tr class="table-row">
                        <td class="px-6 py-4 text-gray-600"><?= $index + 1 ?></td>
                        <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($district['district']) ?></td>
                        <td class="px-6 py-4 text-gray-700"><?= $district['count'] ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-primary-500 h-2 rounded-full" style="width: <?= $totalForms ? round(($district['count'] / $totalForms) * 100) : 0 ?>%"></div>
                                </div>
                                <span class="text-sm text-gray-600"><?= $totalForms ? round(($district['count'] / $totalForms) * 100, 1) : 0 ?>%</span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include 'includes/scripts.php'; ?>
<script>
function generateReport(type) {
    // Show loading state
    event.target.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>तयार गर्दै...';
    event.target.disabled = true;
    
    // Simulate report generation
    setTimeout(() => {
        alert('रिपोर्ट सुविधा छिट्टै उपलब्ध हुनेछ!');
        event.target.innerHTML = '<i class="fas fa-download mr-2"></i>डाउनलोड गर्नुहोस्';
        event.target.disabled = false;
    }, 2000);
}
</script>
</body>
</html>