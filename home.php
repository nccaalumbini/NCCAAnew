<?php
require_once 'includes/config.php';

// Get latest notice
$stmt = $pdo->prepare("SELECT * FROM notices WHERE is_active = 1 ORDER BY created_at DESC LIMIT 1");
$stmt->execute();
$notice = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCCAA Lumbini Province - गृहपृष्ठ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              nccaa: '#2E7A56',
              accent: '#F3F7FB'
            }
          }
        }
      }
    </script>
</head>
<body class="min-h-screen bg-gray-50">
  <!-- Header -->
  <header class="bg-white shadow">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16 items-center">
        <div class="flex items-center space-x-4">
          <img src="public/images/hero.png" alt="NCCAA" class="h-12 w-auto">
          <div>
            <h1 class="text-xl font-semibold text-gray-800">NCCAA लुम्बिनी प्रदेश</h1>
            <p class="text-sm text-gray-500">राष्ट्रिय सेवा कैडेट कोर सहायक</p>
          </div>
        </div>
        <a href="admin/login.php" class="px-6 py-2 bg-nccaa text-white rounded-lg font-medium hover:bg-green-700">प्रशासक लगइन</a>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <?php if ($notice): ?>
    <!-- Notice Section -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
      <div class="bg-gradient-to-r from-nccaa to-green-700 text-white px-6 py-4">
        <h2 class="text-2xl font-semibold">📢 नवीन सूचना</h2>
      </div>
      
      <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
          <div class="md:col-span-2">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4"><?= htmlspecialchars($notice['title']) ?></h3>
            <div class="text-gray-600 leading-relaxed mb-6">
              <?= nl2br(htmlspecialchars($notice['content'])) ?>
            </div>
            <div class="text-sm text-gray-500">
              📅 <?= date('Y-m-d', strtotime($notice['created_at'])) ?>
            </div>
          </div>
          
          <?php if ($notice['image_path']): ?>
          <div class="flex justify-center">
            <img src="<?= htmlspecialchars($notice['image_path']) ?>" alt="Notice Image" class="rounded-lg shadow w-full max-h-96 object-cover">
          </div>
          <?php endif; ?>
        </div>

        <!-- CTA Button -->
        <div class="mt-10 pt-6 border-t">
          <a href="form.php" class="inline-block px-8 py-4 bg-nccaa text-white rounded-lg font-semibold text-lg hover:bg-green-700 transition">
            📝 अब आवेदन गर्नुहोस्
          </a>
        </div>
      </div>
    </div>

    <?php else: ?>
    <!-- Welcome Section -->
    <div class="bg-gradient-to-br from-nccaa to-green-700 text-white rounded-lg shadow-lg overflow-hidden">
      <div class="p-12">
        <div class="flex items-center justify-between mb-8">
          <div>
            <h2 class="text-4xl font-bold mb-4">NCCAA लुम्बिनी प्रदेशमा स्वागतम्</h2>
            <p class="text-xl text-green-100">राष्ट्रिय सेवा कैडेट कोरको पूर्व सदस्यों को संघ</p>
          </div>
          <img src="public/images/hero.png" alt="NCCAA" class="h-24 w-auto opacity-80">
        </div>

        <div class="bg-white bg-opacity-10 rounded-lg p-6 mb-8 backdrop-blur">
          <p class="text-lg text-green-50 leading-relaxed">
            यो वेबसाइट NCCAA लुम्बिनी प्रदेशको आधिकारिक संगठन पोर्टल हो। यहाँ तपाईंले आवेदन गर्न र संघको गतिविधिहरूबारे जानकारी पाउन सक्नुहुन्छ।
          </p>
        </div>

        <a href="form.php" class="inline-block px-10 py-4 bg-white text-nccaa rounded-lg font-semibold text-lg hover:bg-gray-100 transition shadow-lg">
          📝 अब आवेदन गर्नुहोस्
        </a>
      </div>
    </div>

    <?php endif; ?>

    <!-- Features Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
      <div class="bg-white rounded-lg shadow p-6 text-center hover:shadow-lg transition">
        <div class="text-4xl mb-4">🎖️</div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">गौरवमय इतिहास</h3>
        <p class="text-gray-600 text-sm">NCCAA लुम्बिनी प्रदेशको समृद्ध इतिहास र परम्परा</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6 text-center hover:shadow-lg transition">
        <div class="text-4xl mb-4">👥</div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">सामूहिक प्रयास</h3>
        <p class="text-gray-600 text-sm">सदस्यों को एकता र समन्वय आधारित कार्य</p>
      </div>

      <div class="bg-white rounded-lg shadow p-6 text-center hover:shadow-lg transition">
        <div class="text-4xl mb-4">🎯</div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">लक्ष्य केन्द्रित</h3>
        <p class="text-gray-600 text-sm">समाजको विकास र देश सेवामा समर्पित</p>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-800 text-gray-300 text-center py-6 mt-16">
    <div class="max-w-6xl mx-auto px-4">
      <p class="mb-2">© 2025 NCCAA लुम्बिनी प्रदेश | सर्वाधिकार सुरक्षित</p>
      <p class="text-sm">राष्ट्रिय सेवा कैडेट कोर सहायक - Nepal</p>
    </div>
  </footer>

  <script src="public/js/main.js"></script>
</body>
</html>