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
<?php include 'includes/styles.php'; ?>
</head>
<body class="min-h-screen flex flex-col lg:flex-row bg-gray-50">
<?php include 'includes/sidebar.php'; ?>

  <main id="main-content" class="flex-1 ml-64 p-4 lg:p-6 transition-all duration-300">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">आवेदन व्यवस्थापन</h1>
        <p class="text-gray-500 text-sm">सबै आवेदनकर्ताहरूको विवरण र व्यवस्थापन</p>
      </div>
      <div class="flex items-center space-x-4">
        <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-md bg-white shadow-card text-gray-700">
          <i class="fas fa-bars"></i>
        </button>
      </div>
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
    <div class="bg-white rounded-lg shadow-card p-6 mb-6 border border-card-border">
      <div class="flex items-center justify-between flex-wrap gap-4">
        <form method="GET" class="flex-1 min-w-xs">
          <div class="relative">
            <input type="text" name="search" class="form-input w-full pl-10" 
                   placeholder="नाम, जिल्ला, पद वा फोन नम्बरले खोज्नुहोस्..." 
                   value="<?= htmlspecialchars($search) ?>">
            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
          </div>
        </form>
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 text-white px-6 py-3 rounded-lg font-semibold shadow-lg">
          <i class="fas fa-file-alt mr-2"></i>कुल: <?= count($forms) ?>
        </div>
      </div>
    </div>

    <!-- Forms Table -->
    <div class="table-container bg-white shadow-card">
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="table-header">
            <tr class="text-left text-gray-700">
              <th class="px-6 py-4 font-semibold">क्र.सं.</th>
              <th class="px-6 py-4 font-semibold">नाम</th>
              <th class="px-6 py-4 font-semibold">जिल्ला</th>
              <th class="px-6 py-4 font-semibold">NCCAA पद</th>
              <th class="px-6 py-4 font-semibold">लिंग</th>
              <th class="px-6 py-4 font-semibold">सम्पर्क</th>
              <th class="px-6 py-4 font-semibold">आवेदन मिति</th>
              <th class="px-6 py-4 font-semibold">कार्य</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-table-border">
            <?php foreach ($forms as $index => $form): ?>
            <tr class="table-row">
              <td class="px-6 py-4 text-gray-600 font-medium"><?= $index + 1 ?></td>
              <td class="px-6 py-4">
                <div class="flex items-center">
                  <div class="h-8 w-8 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center text-sm font-medium mr-3">
                    <?= mb_substr($form['full_name'] ?? 'A', 0, 1, 'UTF-8') ?>
                  </div>
                  <span class="font-medium text-gray-900"><?= htmlspecialchars($form['full_name']) ?></span>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                  <?= htmlspecialchars($form['district']) ?>
                </span>
              </td>
              <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($form['nccaa_position_applied'] ?? '-') ?></td>
              <td class="px-6 py-4">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?= $form['gender'] === 'पुरुष' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' ?>">
                  <i class="fas fa-<?= $form['gender'] === 'पुरुष' ? 'male' : 'female' ?> mr-1"></i>
                  <?= htmlspecialchars($form['gender']) ?>
                </span>
              </td>
              <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($form['contact_number']) ?></td>
              <td class="px-6 py-4 text-gray-600"><?= date('Y-m-d', strtotime($form['created_at'])) ?></td>
              <td class="px-6 py-4 space-x-2">
                <a href="view_form.php?id=<?= $form['id'] ?>" class="action-btn inline-flex items-center bg-primary-500 text-white hover:bg-primary-600">
                  <i class="fas fa-eye mr-1"></i> हेर्नुहोस्
                </a>
                <button onclick="if(confirm('मेटाउन निश्चित?')) window.location='forms.php?delete=<?= $form['id'] ?>'" class="action-btn inline-flex items-center bg-red-500 text-white hover:bg-red-600">
                  <i class="fas fa-trash mr-1"></i> मेटाउनुहोस्
                </button>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($forms)): ?>
            <tr>
              <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                <i class="fas fa-inbox text-4xl mb-4 text-gray-300"></i>
                <p class="text-lg font-medium">कुनै आवेदन छैन</p>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

<?php include 'includes/scripts.php'; ?>
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