<?php
require_once '../includes/config.php';
requireLogin();

// Get statistics
$totalForms = $pdo->query("SELECT COUNT(*) FROM cadet_forms")->fetchColumn();
$totalNotices = $pdo->query("SELECT COUNT(*) FROM notices WHERE is_active = 1")->fetchColumn();
$maleCount = $pdo->query("SELECT COUNT(*) FROM cadet_forms WHERE gender = 'पुरुष'")->fetchColumn();
$femaleCount = $pdo->query("SELECT COUNT(*) FROM cadet_forms WHERE gender = 'महिला'")->fetchColumn();

// District-wise statistics
$districtStats = $pdo->query("
    SELECT district, COUNT(*) as count 
    FROM cadet_forms 
    GROUP BY district 
    ORDER BY count DESC 
    LIMIT 5
")->fetchAll();

// Post-wise statistics
$postStats = $pdo->query("
    SELECT nccaa_position_applied AS post, COUNT(*) as count 
    FROM cadet_forms 
    GROUP BY nccaa_position_applied 
    ORDER BY count DESC
")->fetchAll();

// Recent forms
$recentForms = $pdo->query("
    SELECT * FROM cadet_forms 
    ORDER BY created_at DESC 
    LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ne">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>NCCAA Admin - ड्यासबोर्ड</title>
        <link rel="stylesheet" href="../public/css/style.css">
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: { nccaa: '#2E7A56' }
                    }
                }
            }
        </script>
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center space-x-4">
                    <img src="../public/images/hero.png" alt="NCCAA" class="h-10 w-auto">
                    <div>
                        <h1 class="text-lg font-semibold">NCCAA प्रशासक</h1>
                        <p class="text-xs text-gray-500">लुम्बिनी प्रदेश - ड्यासबोर्ड</p>
                    </div>
                </div>

                <nav class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-sm text-gray-700 hover:text-nccaa">ड्यासबोर्ड</a>
                    <a href="notices.php" class="text-sm text-gray-700 hover:text-nccaa">सूचनाहरू</a>
                    <a href="forms.php" class="text-sm text-gray-700 hover:text-nccaa">आवेदनहरू</a>
                    <a href="logout.php" class="text-sm text-gray-700 hover:text-nccaa">लगआउट</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">ड्यासबोर्ड</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-5">
                <div class="text-sm text-gray-500">कुल आवेदनहरू</div>
                <div class="text-2xl font-bold text-gray-800 mt-2"><?= $totalForms ?></div>
            </div>
            <div class="bg-white rounded-lg shadow p-5">
                <div class="text-sm text-gray-500">सक्रिय सूचनाहरू</div>
                <div class="text-2xl font-bold text-gray-800 mt-2"><?= $totalNotices ?></div>
            </div>
            <div class="bg-white rounded-lg shadow p-5">
                <div class="text-sm text-gray-500">पुरुष आवेदकहरू</div>
                <div class="text-2xl font-bold text-gray-800 mt-2"><?= $maleCount ?></div>
            </div>
            <div class="bg-white rounded-lg shadow p-5">
                <div class="text-sm text-gray-500">महिला आवेदकहरू</div>
                <div class="text-2xl font-bold text-gray-800 mt-2"><?= $femaleCount ?></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow">
                <div class="px-4 py-3 border-b bg-gray-50 font-medium">जिल्लाअनुसार आवेदनहरू</div>
                <div class="p-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-600">
                                <th class="py-2">जिल्ला</th>
                                <th class="py-2">संख्या</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($districtStats as $stat): ?>
                            <tr class="border-t">
                                <td class="py-2"><?= htmlspecialchars($stat['district']) ?></td>
                                <td class="py-2"><?= $stat['count'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow">
                <div class="px-4 py-3 border-b bg-gray-50 font-medium">पदअनुसार आवेदनहरू</div>
                <div class="p-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-600">
                                <th class="py-2">पद</th>
                                <th class="py-2">संख्या</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($postStats as $stat): ?>
                            <tr class="border-t">
                                <td class="py-2"><?= htmlspecialchars($stat['post'] ?? '-') ?></td>
                                <td class="py-2"><?= $stat['count'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="px-4 py-3 border-b bg-gray-50 font-medium">हालैका आवेदनहरू</div>
            <div class="p-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-600">
                            <th class="py-2">नाम</th>
                            <th class="py-2">पद</th>
                            <th class="py-2">जिल्ला</th>
                            <th class="py-2">मिति</th>
                            <th class="py-2">कार्य</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentForms as $form): ?>
                        <tr class="border-t">
                            <td class="py-2"><?= htmlspecialchars($form['full_name']) ?></td>
                            <td class="py-2"><?= htmlspecialchars($form['nccaa_position_applied'] ?? '-') ?></td>
                            <td class="py-2"><?= htmlspecialchars($form['district'] ?? '-') ?></td>
                            <td class="py-2"><?= date('Y-m-d', strtotime($form['created_at'])) ?></td>
                            <td class="py-2">
                                <a href="view_form.php?id=<?= $form['id'] ?>" class="inline-block px-3 py-1 bg-nccaa text-white rounded text-xs">हेर्नुहोस्</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="../public/js/main.js"></script>
</body>
</html>