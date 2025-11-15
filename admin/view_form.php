<?php
require_once '../includes/config.php';
requireLogin();

if (!isset($_GET['id'])) {
    header('Location: forms.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM cadet_forms WHERE id = ?");
$stmt->execute([$_GET['id']]);
$form = $stmt->fetch();

if (!$form) {
    header('Location: forms.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCCAA Admin - आवेदन विवरण</title>
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
<body class="min-h-screen bg-gray-50">
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        <div class="flex items-center space-x-4">
          <img src="../public/images/hero.png" alt="NCCAA" class="h-10 w-auto">
          <div>
            <h1 class="text-lg font-semibold">NCCAA प्रशासक</h1>
            <p class="text-xs text-gray-500">लुम्बिनी प्रदेश</p>
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
    <div class="flex justify-between items-center mb-8">
      <div>
        <h1 class="text-3xl font-semibold text-gray-800">आवेदन विवरण</h1>
        <p class="text-gray-600 text-sm mt-1"><?= htmlspecialchars($form['full_name']) ?> को पूरा विवरण</p>
      </div>
      <div class="flex gap-3">
        <a href="print_form.php?id=<?= $form['id'] ?>" target="_blank" class="px-6 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700">🖨️ प्रिन्ट गर्नुहोस्</a>
        <a href="forms.php" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg font-medium hover:bg-gray-400">← फिर्ता जानुहोस्</a>
      </div>
    </div>

    <!-- Main Form View Card -->
    <div class="bg-white rounded-lg shadow-lg p-8">
      <h2 class="text-2xl font-semibold text-gray-800 border-b-2 border-nccaa pb-4 mb-8">
        <?= htmlspecialchars($form['full_name']) ?> को आवेदन
      </h2>

      <!-- Section 1: Personal Details -->
      <div class="mb-8">
        <h3 class="text-lg font-semibold text-nccaa mb-4 flex items-center">
          <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-nccaa text-white text-sm mr-3">1</span>
          व्यक्तिगत विवरण
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="text-xs font-semibold text-nccaa uppercase">पूरा नाम</div>
            <div class="text-lg text-gray-800"><?= htmlspecialchars($form['full_name']) ?></div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="text-xs font-semibold text-nccaa uppercase">लिंग</div>
            <div class="text-lg text-gray-800"><?= htmlspecialchars($form['gender']) ?></div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="text-xs font-semibold text-nccaa uppercase">उमेर</div>
            <div class="text-lg text-gray-800"><?= htmlspecialchars($form['age']) ?></div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="text-xs font-semibold text-nccaa uppercase">सम्पर्क नम्बर</div>
            <div class="text-lg text-gray-800"><?= htmlspecialchars($form['contact_number']) ?></div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="text-xs font-semibold text-nccaa uppercase">इमेल</div>
            <div class="text-lg text-gray-800"><?= htmlspecialchars($form['email'] ?? '-') ?></div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="text-xs font-semibold text-nccaa uppercase">प्रदेश</div>
            <div class="text-lg text-gray-800"><?= htmlspecialchars($form['province']) ?></div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="text-xs font-semibold text-nccaa uppercase">जिल्ला</div>
            <div class="text-lg text-gray-800"><?= htmlspecialchars($form['district']) ?></div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg md:col-span-2 lg:col-span-3">
            <div class="text-xs font-semibold text-nccaa uppercase mb-2">ठेगाना</div>
            <div class="text-gray-800"><?= htmlspecialchars($form['address']) ?></div>
          </div>
        </div>
      </div>

      <!-- Section 2: NCC Details -->
      <div class="mb-8 border-t pt-8">
        <h3 class="text-lg font-semibold text-nccaa mb-4 flex items-center">
          <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-purple-500 text-white text-sm mr-3">2</span>
          NCC सम्बन्धी विवरण
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="text-xs font-semibold text-nccaa uppercase">NCC ब्याच नं.</div>
            <div class="text-lg text-gray-800"><?= htmlspecialchars($form['ncc_batch_number'] ?? '-') ?></div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="text-xs font-semibold text-nccaa uppercase">NCC व्य. नं.</div>
            <div class="text-lg text-gray-800"><?= htmlspecialchars($form['ncc_personal_number'] ?? '-') ?></div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="text-xs font-semibold text-nccaa uppercase">NCC डिभिजन</div>
            <div class="text-lg text-gray-800"><?= htmlspecialchars($form['ncc_division'] ?? '-') ?></div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg md:col-span-2">
            <div class="text-xs font-semibold text-nccaa uppercase">NCC दीक्षित विद्यालय</div>
            <div class="text-gray-800"><?= htmlspecialchars($form['ncc_passout_school'] ?? '-') ?></div>
          </div>
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="text-xs font-semibold text-nccaa uppercase">NCC दर्जा / पद</div>
            <div class="text-gray-800"><?= htmlspecialchars($form['ncc_rank_position'] ?? '-') ?></div>
          </div>
        </div>
      </div>

      <!-- Section 3: NCCAA Details -->
      <div class="mb-8 border-t pt-8">
        <h3 class="text-lg font-semibold text-nccaa mb-4 flex items-center">
          <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-500 text-white text-sm mr-3">3</span>
          NCCAA पद आवेदन
        </h3>
        <div class="grid grid-cols-1 gap-4">
          <div class="bg-gray-50 p-4 rounded-lg">
            <div class="text-xs font-semibold text-nccaa uppercase">लागु गरेको पद</div>
            <div class="text-lg text-gray-800"><?= htmlspecialchars($form['nccaa_position_applied'] ?? '-') ?></div>
          </div>
        </div>
      </div>

      <!-- Submission Info -->
      <div class="border-t pt-8 mt-8 bg-blue-50 p-4 rounded-lg">
        <p class="text-gray-700"><strong>📅 आवेदन पेश गरिएको मिति र समय:</strong> <?= date('Y-m-d H:i:s', strtotime($form['created_at'])) ?></p>
      </div>
    </div>
  </main>

  <script src="../public/js/main.js"></script>
</body>
</html>