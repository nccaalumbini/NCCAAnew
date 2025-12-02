<?php
require_once '../includes/config.php';
requireLogin();

// Get admin users (for now just show current user)
$users = $pdo->query("SELECT * FROM admin_users ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCCAA Admin - प्रयोगकर्ता व्यवस्थापन</title>
<?php include 'includes/styles.php'; ?>
</head>
<body class="min-h-screen flex flex-col lg:flex-row bg-gray-50">
<?php include 'includes/sidebar.php'; ?>

<main id="main-content" class="flex-1 ml-64 p-4 lg:p-6 transition-all duration-300">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">प्रयोगकर्ता व्यवस्थापन</h1>
            <p class="text-gray-500 text-sm">सिस्टम प्रयोगकर्ताहरूको व्यवस्थापन</p>
        </div>
        <div class="flex items-center space-x-4">
            <button class="btn-primary" onclick="showComingSoon()">
                <i class="fas fa-plus mr-2"></i>नयाँ प्रयोगकर्ता
            </button>
            <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-md bg-white shadow-card text-gray-700">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Users Table -->
    <div class="table-container bg-white shadow-card">
        <div class="px-6 py-4 table-header border-b font-semibold text-gray-800 flex items-center">
            <i class="fas fa-users mr-2 text-primary-600"></i>प्रयोगकर्ताहरूको सूची
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="table-header">
                    <tr class="text-left text-gray-700">
                        <th class="px-6 py-4 font-semibold">प्रयोगकर्ता</th>
                        <th class="px-6 py-4 font-semibold">भूमिका</th>
                        <th class="px-6 py-4 font-semibold">स्थिति</th>
                        <th class="px-6 py-4 font-semibold">अन्तिम लगइन</th>
                        <th class="px-6 py-4 font-semibold">कार्य</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-table-border">
                    <?php foreach ($users as $user): ?>
                    <tr class="table-row">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold mr-3">
                                    <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900"><?= htmlspecialchars($user['username']) ?></div>
                                    <div class="text-sm text-gray-500">प्रशासक</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-crown mr-1"></i>सुपर एडमिन
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-circle mr-1 text-xs"></i>सक्रिय
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            <div class="text-sm">आज</div>
                            <div class="text-xs text-gray-400"><?= date('H:i') ?></div>
                        </td>
                        <td class="px-6 py-4 space-x-2">
                            <button class="action-btn inline-flex items-center bg-primary-500 text-white hover:bg-primary-600" onclick="showComingSoon()">
                                <i class="fas fa-edit mr-1"></i>सम्पादन
                            </button>
                            <button class="action-btn inline-flex items-center bg-gray-500 text-white hover:bg-gray-600" onclick="showComingSoon()">
                                <i class="fas fa-key mr-1"></i>पासवर्ड
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Coming Soon Features -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-card p-6 border border-card-border text-center">
            <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-plus text-blue-600 text-xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">प्रयोगकर्ता थप्नुहोस्</h3>
            <p class="text-sm text-gray-600 mb-4">नयाँ एडमिन प्रयोगकर्ता सिर्जना गर्नुहोस्</p>
            <button class="text-primary-600 text-sm font-medium" onclick="showComingSoon()">छिट्टै आउँदै</button>
        </div>

        <div class="bg-white rounded-lg shadow-card p-6 border border-card-border text-center">
            <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shield-alt text-green-600 text-xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">अनुमति व्यवस्थापन</h3>
            <p class="text-sm text-gray-600 mb-4">प्रयोगकर्ता अधिकार नियन्त्रण</p>
            <button class="text-primary-600 text-sm font-medium" onclick="showComingSoon()">छिट्टै आउँदै</button>
        </div>

        <div class="bg-white rounded-lg shadow-card p-6 border border-card-border text-center">
            <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-history text-purple-600 text-xl"></i>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">गतिविधि लग</h3>
            <p class="text-sm text-gray-600 mb-4">प्रयोगकर्ता गतिविधि ट्र्याकिङ</p>
            <button class="text-primary-600 text-sm font-medium" onclick="showComingSoon()">छिट्टै आउँदै</button>
        </div>
    </div>
</main>

<?php include 'includes/scripts.php'; ?>
<script>
function showComingSoon() {
    alert('यो सुविधा छिट्टै उपलब्ध हुनेछ!');
}
</script>
</body>
</html>