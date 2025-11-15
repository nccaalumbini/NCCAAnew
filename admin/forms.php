<?php
require_once '../includes/config.php';
requireLogin();

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';

// Clear session messages
unset($_SESSION['success'], $_SESSION['error']);

// Handle delete
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM cadet_forms WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $success = 'आवेदन सफलतापूर्वक मेटाइयो।';
    } catch (Exception $e) {
        $error = 'मेटाउन सकिएन।';
    }
}

// Search functionality
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$whereClause = '';
$params = [];

if ($search) {
    $whereClause = "WHERE full_name LIKE ? OR district LIKE ? OR nccaa_position_applied LIKE ? OR contact_number LIKE ?";
    $searchTerm = "%$search%";
    $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
}

// Get all forms with search
$sql = "SELECT * FROM cadet_forms $whereClause ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$forms = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCCAA Admin - आवेदन व्यवस्थापन</title>
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
          <a href="forms.php" class="text-sm text-nccaa font-semibold border-b-2 border-nccaa pb-4">आवेदनहरू</a>
          <a href="logout.php" class="text-sm text-gray-700 hover:text-nccaa">लगआउट</a>
        </nav>
      </div>
    </div>
  </header>

  <main class="max-w-7xl mx-auto p-6">
    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">आवेदन व्यवस्थापन</h2>
      <p class="text-gray-600 text-sm mt-1">सबै आवेदनकर्ताहरूको विवरण र व्यवस्थापन</p>
    </div>

    <?php if ($success): ?>
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
      <p class="text-green-700">✅ <?= $success ?></p>
    </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
      <p class="text-red-700">❌ <?= $error ?></p>
    </div>
    <?php endif; ?>

    <!-- Search & Stats -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
      <div class="flex items-center justify-between flex-wrap gap-4">
        <form method="GET" class="flex-1 min-w-xs">
          <input type="text" name="search" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-nccaa" 
                 placeholder="🔍 नाम, जिल्ला, पद वा फोन नम्बरले खोज्नुहोस्..." 
                 value="<?= htmlspecialchars($search) ?>">
        </form>
        <div class="bg-nccaa text-white px-6 py-2 rounded-lg font-semibold">
          कुल: <?= count($forms) ?>
        </div>
      </div>
    </div>

    <!-- Forms Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="bg-gray-100 text-left text-gray-600">
              <th class="px-6 py-3">क्र.सं.</th>
              <th class="px-6 py-3">नाम</th>
              <th class="px-6 py-3">जिल्ला</th>
              <th class="px-6 py-3">NCCAA पद</th>
              <th class="px-6 py-3">लिंग</th>
              <th class="px-6 py-3">सम्पर्क</th>
              <th class="px-6 py-3">आवेदन मिति</th>
              <th class="px-6 py-3">कार्य</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <?php foreach ($forms as $index => $form): ?>
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-3 text-gray-600"><?= $index + 1 ?></td>
              <td class="px-6 py-3 font-medium"><?= htmlspecialchars($form['full_name']) ?></td>
              <td class="px-6 py-3"><?= htmlspecialchars($form['district']) ?></td>
              <td class="px-6 py-3"><?= htmlspecialchars($form['nccaa_position_applied'] ?? '-') ?></td>
              <td class="px-6 py-3"><?= htmlspecialchars($form['gender']) ?></td>
              <td class="px-6 py-3"><?= htmlspecialchars($form['contact_number']) ?></td>
              <td class="px-6 py-3 text-gray-600"><?= date('Y-m-d', strtotime($form['created_at'])) ?></td>
              <td class="px-6 py-3 space-x-2">
                <a href="view_form.php?id=<?= $form['id'] ?>" class="inline-block px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600">👁️ हेर्नुहोस्</a>
                <button onclick="if(confirm('मेटाउन निश्चित?')) window.location='forms.php?delete=<?= $form['id'] ?>'" class="inline-block px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600">🗑️ मेटाउनुहोस्</button>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($forms)): ?>
            <tr>
              <td colspan="8" class="px-6 py-8 text-center text-gray-500">कुनै आवेदन छैन</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <script src="../public/js/main.js"></script>
  <script>
    // Real-time search
    document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.querySelector('input[name="search"]');
      if (searchInput) {
        searchInput.addEventListener('input', function() {
          setTimeout(() => {
            this.form.submit();
          }, 500);
        });
      }
    });
  </script>
</body>
</html>