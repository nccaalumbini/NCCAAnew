<?php
require_once '../includes/config.php';
requireLogin();

$success = '';
$error = '';

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
          <a href="notices.php" class="text-sm text-nccaa font-semibold border-b-2 border-nccaa pb-4">सूचनाहरू</a>
          <a href="forms.php" class="text-sm text-gray-700 hover:text-nccaa">आवेदनहरू</a>
          <a href="logout.php" class="text-sm text-gray-700 hover:text-nccaa">लगआउट</a>
        </nav>
      </div>
    </div>
  </header>

  <main class="max-w-7xl mx-auto p-6">
    <div class="mb-8">
      <h2 class="text-2xl font-semibold text-gray-800">सूचना व्यवस्थापन</h2>
      <p class="text-gray-600 text-sm mt-1">होमपेजमा प्रदर्शन गर्ने सूचनाहरू व्यवस्थापन गर्नुहोस्</p>
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
    <div class="bg-white rounded-lg shadow p-6 mb-8">
      <h3 class="text-lg font-semibold text-gray-800 mb-6">
        <?= $editNotice ? '✏️ सूचना सम्पादन गर्नुहोस्' : '➕ नयाँ सूचना थप्नुहोस्' ?>
      </h3>
      
      <form method="POST" enctype="multipart/form-data" class="space-y-4">
        <?php if ($editNotice): ?>
        <input type="hidden" name="notice_id" value="<?= $editNotice['id'] ?>">
        <?php endif; ?>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">शीर्षक *</label>
          <input type="text" name="title" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-nccaa"
                 value="<?= $editNotice ? htmlspecialchars($editNotice['title']) : '' ?>" required>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">सामग्री *</label>
          <textarea name="content" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-nccaa" 
                    rows="6" required><?= $editNotice ? htmlspecialchars($editNotice['content']) : '' ?></textarea>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700">तस्बिर</label>
          <input type="file" name="image" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" accept="image/*">
          <?php if ($editNotice && $editNotice['image_path']): ?>
          <div class="mt-3">
            <img src="../<?= htmlspecialchars($editNotice['image_path']) ?>" alt="Current Image" class="w-32 h-32 object-cover rounded">
          </div>
          <?php endif; ?>
        </div>
        
        <div class="flex gap-4 pt-4">
          <button type="submit" class="px-6 py-2 bg-nccaa text-white rounded-lg font-medium hover:bg-green-700">
            <?= $editNotice ? 'अपडेट गर्नुहोस्' : 'प्रकाशित गर्नुहोस्' ?>
          </button>
          <?php if ($editNotice): ?>
          <a href="notices.php" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg font-medium hover:bg-gray-400">रद्द गर्नुहोस्</a>
          <?php endif; ?>
        </div>
      </form>
    </div>

    <!-- Notices List -->
    <div class="bg-white rounded-lg shadow">
      <div class="px-6 py-4 bg-gray-50 border-b font-medium">प्रकाशित सूचनाहरू</div>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="text-left text-gray-600 border-b">
              <th class="px-6 py-3">शीर्षक</th>
              <th class="px-6 py-3">सामग्री</th>
              <th class="px-6 py-3">तस्बिर</th>
              <th class="px-6 py-3">मिति</th>
              <th class="px-6 py-3">कार्य</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($notices as $notice): ?>
            <tr class="border-t hover:bg-gray-50">
              <td class="px-6 py-3"><?= htmlspecialchars($notice['title']) ?></td>
              <td class="px-6 py-3 text-gray-600"><?= htmlspecialchars(substr($notice['content'], 0, 50)) ?>...</td>
              <td class="px-6 py-3">
                <?php if ($notice['image_path']): ?>
                <img src="../<?= htmlspecialchars($notice['image_path']) ?>" alt="Notice" class="w-12 h-12 object-cover rounded">
                <?php else: ?>
                <span class="text-gray-400 text-xs">कोनै छैन</span>
                <?php endif; ?>
              </td>
              <td class="px-6 py-3 text-gray-600"><?= date('Y-m-d', strtotime($notice['created_at'])) ?></td>
              <td class="px-6 py-3 space-x-2">
                <a href="notices.php?edit=<?= $notice['id'] ?>" class="inline-block px-3 py-1 bg-blue-500 text-white rounded text-xs hover:bg-blue-600">✏️ सम्पादन</a>
                <a href="notices.php?delete=<?= $notice['id'] ?>" class="inline-block px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600" onclick="return confirm('मेटाउन निश्चित?')">🗑️ मेटाउनुहोस्</a>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($notices)): ?>
            <tr>
              <td colspan="5" class="px-6 py-4 text-center text-gray-500">कुनै सूचना छैन</td>
            </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <script src="../public/js/main.js"></script>
</body>
</html>