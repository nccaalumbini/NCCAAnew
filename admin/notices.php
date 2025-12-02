<?php
require_once '../includes/config.php';
requireLogin();

$success = '';
$error = '';
header("Content-Type: text/html; charset=UTF-8");

// Handle form submission
if ($_POST) {
    try {
        $title = sanitize($_POST['title']);
        $content = sanitize($_POST['content']);
        $imagePath = null;
        
        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../public/uploads/notices/';
            $fileName = time() . '_' . $_FILES['image']['name'];
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $imagePath = 'public/uploads/notices/' . $fileName;
            }
        }
        
        if (isset($_POST['notice_id']) && $_POST['notice_id']) {
            // Update existing notice
            $stmt = $pdo->prepare("UPDATE notices SET title = ?, content = ?, image_path = COALESCE(?, image_path) WHERE id = ?");
            $stmt->execute([$title, $content, $imagePath, $_POST['notice_id']]);
            $success = 'सूचना सफलतापूर्वक अपडेट गरियो।';
        } else {
            // Create new notice
            $stmt = $pdo->prepare("INSERT INTO notices (title, content, image_path) VALUES (?, ?, ?)");
            $stmt->execute([$title, $content, $imagePath]);
            $success = 'सूचना सफलतापूर्वक प्रकाशित गरियो।';
        }
    } catch (Exception $e) {
        $error = 'त्रुटि: ' . $e->getMessage();
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("UPDATE notices SET is_active = 0 WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $success = 'सूचना सफलतापूर्वक मेटाइयो।';
    } catch (Exception $e) {
        $error = 'मेटाउन सकिएन।';
    }
}

// Get all notices
$notices = $pdo->query("SELECT * FROM notices WHERE is_active = 1 ORDER BY created_at DESC")->fetchAll();

// Get notice for editing
$editNotice = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM notices WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editNotice = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCCAA Admin - सूचना व्यवस्थापन</title>
<?php include 'includes/styles.php'; ?>
</head>
<body class="min-h-screen flex flex-col lg:flex-row bg-gray-50">
<?php include 'includes/sidebar.php'; ?>

  <main id="main-content" class="flex-1 ml-64 p-4 lg:p-6 transition-all duration-300">
    <div class="flex justify-between items-center mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">सूचना व्यवस्थापन</h1>
        <p class="text-gray-500 text-sm">होमपेजमा प्रदर्शन गर्ने सूचनाहरू व्यवस्थापन गर्नुहोस्</p>
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

    <!-- Add/Edit Form -->
    <div class="bg-white rounded-lg shadow-card p-6 mb-8 border border-card-border">
      <div class="flex items-center mb-6">
        <div class="h-10 w-10 rounded-lg bg-primary-100 flex items-center justify-center mr-4">
          <i class="fas fa-<?= $editNotice ? 'edit' : 'plus' ?> text-primary-600"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-800">
          <?= $editNotice ? 'सूचना सम्पादन गर्नुहोस्' : 'नयाँ सूचना थप्नुहोस्' ?>
        </h3>
      </div>
      
      <form method="POST" enctype="multipart/form-data" class="space-y-6">
        <?php if ($editNotice): ?>
        <input type="hidden" name="notice_id" value="<?= $editNotice['id'] ?>">
        <?php endif; ?>
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">शीर्षक *</label>
          <input type="text" name="title" class="form-input w-full"
                 value="<?= $editNotice ? htmlspecialchars($editNotice['title']) : '' ?>" 
                 placeholder="सूचनाको शीर्षक लेख्नुहोस्" required>
        </div>
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">सामग्री *</label>
          <textarea name="content" class="form-input w-full" 
                    rows="6" 
                    placeholder="सूचनाको विस्तृत विवरण लेख्नुहोस्" required><?= $editNotice ? htmlspecialchars($editNotice['content']) : '' ?></textarea>
        </div>
        
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">तसबिर</label>
          <input type="file" name="image" class="form-input w-full" accept="image/*">
          <?php if ($editNotice && $editNotice['image_path']): ?>
          <div class="mt-4">
            <p class="text-sm text-gray-600 mb-2">हालको तसबिर:</p>
            <img src="../<?= htmlspecialchars($editNotice['image_path']) ?>" alt="Current Image" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
          </div>
          <?php endif; ?>
        </div>
        
        <div class="flex gap-4 pt-4 border-t border-gray-200">
          <button type="submit" class="btn-primary inline-flex items-center">
            <i class="fas fa-<?= $editNotice ? 'save' : 'paper-plane' ?> mr-2"></i>
            <?= $editNotice ? 'अपडेट गर्नुहोस्' : 'प्रकाशित गर्नुहोस्' ?>
          </button>
          <?php if ($editNotice): ?>
          <a href="notices.php" class="inline-flex items-center px-6 py-3 bg-gray-300 text-gray-800 rounded-lg font-medium hover:bg-gray-400 transition-all">
            <i class="fas fa-times mr-2"></i>रद्द गर्नुहोस्
          </a>
          <?php endif; ?>
        </div>
      </form>
    </div>

    <!-- Notices List -->
    <div class="table-container bg-white shadow-card">
      <div class="px-6 py-4 table-header border-b font-semibold text-gray-800 flex items-center">
        <i class="fas fa-list mr-2 text-primary-600"></i>प्रकाशित सूचनाहरू
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="table-header">
            <tr class="text-left text-gray-700">
              <th class="px-6 py-4 font-semibold">शीर्षक</th>
              <th class="px-6 py-4 font-semibold">सामग्री</th>
              <th class="px-6 py-4 font-semibold">तसबिर</th>
              <th class="px-6 py-4 font-semibold">मिति</th>
              <th class="px-6 py-4 font-semibold">कार्य</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-table-border">
            <?php foreach ($notices as $notice): ?>
            <tr class="table-row">
              <td class="px-6 py-4">
                <div class="font-medium text-gray-900"><?= htmlspecialchars($notice['title']) ?></div>
              </td>
              <td class="px-6 py-4 text-gray-600 max-w-xs">
                <div class="truncate"><?= htmlspecialchars(substr($notice['content'], 0, 80)) ?>...</div>
              </td>
              <td class="px-6 py-4">
                <?php if ($notice['image_path']): ?>
                <img src="../<?= htmlspecialchars($notice['image_path']) ?>" alt="Notice" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                <?php else: ?>
                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                  <i class="fas fa-image text-gray-400"></i>
                </div>
                <?php endif; ?>
              </td>
              <td class="px-6 py-4 text-gray-600">
                <div class="text-sm"><?= date('Y-m-d', strtotime($notice['created_at'])) ?></div>
                <div class="text-xs text-gray-400"><?= date('H:i', strtotime($notice['created_at'])) ?></div>
              </td>
              <td class="px-6 py-4 space-x-2">
                <a href="notices.php?edit=<?= $notice['id'] ?>" class="action-btn inline-flex items-center bg-primary-500 text-white hover:bg-primary-600">
                  <i class="fas fa-edit mr-1"></i> सम्पादन
                </a>
                <a href="notices.php?delete=<?= $notice['id'] ?>" class="action-btn inline-flex items-center bg-red-500 text-white hover:bg-red-600" onclick="return confirm('मेटाउन निश्चित?')">
                  <i class="fas fa-trash mr-1"></i> मेटाउनुहोस्
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($notices)): ?>
            <tr>
              <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                <i class="fas fa-bullhorn text-4xl mb-4 text-gray-300"></i>
                <p class="text-lg font-medium">कुनै सूचना छैन</p>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

<?php include 'includes/scripts.php'; ?>
</body>
</html>