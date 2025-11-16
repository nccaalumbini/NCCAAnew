<?php
require_once 'includes/config.php';

// Load districts and positions
$districts_data = json_decode(file_get_contents('data/nepal_districts.json'), true);
$lumbini_districts = $districts_data['province_districts']['लुम्बिनी प्रदेश'];
$positions = json_decode(file_get_contents('data/positions.json'), true);

$success = false;
$error = '';

if ($_POST) {
    try {
        // Validate required fields from all 3 sections
        $required_fields = ['full_name', 'gender', 'age', 'contact_number', 'province', 'district', 'address'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("कृपया सबै आवश्यक क्षेत्र पूरा गर्नुहोस्: $field");
            }
        }

        // Section 1: Personal Details
        $full_name = sanitize($_POST['full_name']);
        $gender = sanitize($_POST['gender']);
        $age = (int)$_POST['age'];
        $contact_number = sanitize($_POST['contact_number']);
        $email = sanitize($_POST['email'] ?? '');
        $province = sanitize($_POST['province']);
        $district = sanitize($_POST['district']);
        $address = sanitize($_POST['address']);

        // Section 2: NCC Details
        $ncc_batch_number = sanitize($_POST['ncc_batch_number'] ?? '');
        $ncc_personal_number = sanitize($_POST['ncc_personal_number'] ?? '');
        $ncc_division = sanitize($_POST['ncc_division'] ?? '');
        $ncc_passout_school = sanitize($_POST['ncc_passout_school'] ?? '');
        $ncc_passout_year = sanitize($_POST['ncc_passout_year'] ?? '');
        $ncc_rank_position = sanitize($_POST['ncc_rank_position'] ?? '');

        // Section 3: NCCAA Details
        $nccaa_position_applied = sanitize($_POST['nccaa_position_applied'] ?? '');

        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO cadet_forms (
                full_name, gender, age, contact_number, email, province, district, address,
                ncc_batch_number, ncc_personal_number, ncc_division, ncc_passout_school, ncc_passout_year, ncc_rank_position,
                nccaa_position_applied
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $full_name, $gender, $age, $contact_number, $email, $province, $district, $address,
            $ncc_batch_number, $ncc_personal_number, $ncc_division, $ncc_passout_school, $ncc_passout_year, $ncc_rank_position,
            $nccaa_position_applied
        ]);

        $success = true;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
?>
<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCCAA - आवेदन फॉर्म</title>
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
  <header class="bg-white shadow">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <img src="public/images/hero.png" alt="NCCAA" class="h-12 w-auto">
          <div>
            <h1 class="text-xl font-semibold text-gray-800">NCCAA लुम्बिनी प्रदेश</h1>
            <p class="text-sm text-gray-500">राष्ट्रिय सेवा कैडेट कोर सहायक</p>
          </div>
        </div>
        <a href="home.php" class="text-nccaa hover:text-green-700 font-medium">← गृहपृष्ठ</a>
      </div>
    </div>
  </header>

  <main class="max-w-4xl mx-auto p-6">
    <div class="mb-8">
      <h2 class="text-3xl font-semibold text-gray-800 mb-2">सदस्यता आवेदन फॉर्म</h2>
      <p class="text-gray-600">कृपया तलका सेक्सनहरूमा आफ्नो विस्तृत विवरण भर्नुहोस्</p>
    </div>

    <?php if ($success): ?>
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
      <p class="text-green-700 font-medium">✅ आपको आवेदन सफलतापूर्वक जमा भयो। धन्यवाद!</p>
    </div>
    <div class="text-center mt-4">
      <a href="home.php" class="inline-block px-6 py-2 bg-nccaa text-white rounded-lg font-medium hover:bg-green-700">गृहपृष्ठमा फर्किनुहोस्</a>
    </div>
    <?php else: ?>

    <?php if ($error): ?>
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
      <p class="text-red-700">❌ त्रुटि: <?= htmlspecialchars($error) ?></p>
    </div>
    <?php endif; ?>

    <form method="POST" class="space-y-8">

      <!-- 🔵 SECTION 1: PERSONAL DETAILS -->
      <div class="bg-white rounded-lg shadow p-6 border-l-4 border-nccaa">
        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
          <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-nccaa text-white text-sm mr-3">1</span>
          व्यक्तिगत विवरण
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">नाम *</label>
            <input type="text" name="full_name" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-nccaa">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">लिंग *</label>
            <select name="gender" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-nccaa">
              <option value="">छान्नुहोस्</option>
              <option value="पुरुष">पुरुष</option>
              <option value="महिला">महिला</option>
              <option value="अन्य">अन्य</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">सम्पर्क नं. (10 अंक) *</label>
            <input type="tel" name="contact_number" required pattern="[0-9]{10}" placeholder="9841234567" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-nccaa">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">इमेल</label>
            <input type="email" name="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-nccaa">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">उमेर *</label>
            <input type="number" name="age" required min="18" max="100" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-nccaa">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">प्रदेश *</label>
            <select name="province" required onchange="loadDistricts(this.value)" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-nccaa">
              <option value="">छान्नुहोस्</option>
              <option value="लुम्बिनी प्रदेश" selected>लुम्बिनी प्रदेश</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">जिल्ला *</label>
            <select name="district" id="districtSelect" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-nccaa">
              <option value="">छान्नुहोस्</option>
              <?php foreach ($lumbini_districts as $district): ?>
              <option value="<?= htmlspecialchars($district) ?>"><?= htmlspecialchars($district) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">ठेगाना *</label>
            <textarea name="address" required rows="2" placeholder="सडक नं., वार्ड नं., गाउँ, नगर पालिका" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-nccaa"></textarea>
          </div>
        </div>
      </div>

      <!-- 🟣 SECTION 2: NCC DETAILS -->
      <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
          <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-purple-500 text-white text-sm mr-3">2</span>
          NCC सम्बन्धी विवरण
        </h3>
        <p class="text-sm text-gray-600 mb-6">*(यदि तपाईँ NCC सदस्य हुनुहुन्छ भने भर्नुहोस्)</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">NCC ब्याच नं.</label>
            <input type="text" name="ncc_batch_number" placeholder="जस्तै: 2023-A" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">NCC व्य. नं. (Personal Number)</label>
            <input type="text" name="ncc_personal_number" placeholder="जस्तै: NCC/2023/001" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">NCC डिभिजन</label>
            <select name="ncc_division" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
              <option value="">छान्नुहोस्</option>
              <option value="Senior">Senior</option>
              <option value="Junior">Junior</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">NCC दीक्षित विद्यालय</label>
            <input type="text" name="ncc_passout_school" placeholder="जस्तै: त्रिभुवन विश्वविद्यालय" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">NCC दीक्षित वर्ष</label>
            <input type="number" name="ncc_passout_year" min="2000" max="2100" placeholder="जस्तै: 2075" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">NCC दर्जा / पद</label>
            <select name="ncc_rank_position" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
              <option value="">छान्नुहोस्</option>
              <?php foreach ($positions['ncc_ranks'] as $rank): ?>
              <option value="<?= htmlspecialchars($rank) ?>"><?= htmlspecialchars($rank) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <!-- 🟢 SECTION 3: NCCAA DETAILS -->
      <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center">
          <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-500 text-white text-sm mr-3">3</span>
          NCCAA पद आवेदन
        </h3>

        <div class="grid grid-cols-1 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700">लागु गरेको पद छान्नुहोस्</label>
            <select name="nccaa_position_applied" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
              <option value="">छान्नुहोस्</option>
              <?php foreach ($positions['nccaa_positions'] as $position): ?>
              <option value="<?= htmlspecialchars($position) ?>"><?= htmlspecialchars($position) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <!-- SUBMIT BUTTON -->
      <div class="flex justify-between items-center pt-4">
        <a href="home.php" class="text-nccaa hover:text-green-700 font-medium">← गृहपृष्ठमा फर्किनुहोस्</a>
        <button type="submit" class="px-8 py-3 bg-nccaa text-white rounded-lg font-medium hover:bg-green-700 transition">
          आवेदन जमा गर्नुहोस्
        </button>
      </div>
    </form>
    <?php endif; ?>
  </main>

  <footer class="bg-gray-800 text-gray-300 text-center py-4 mt-12">
    <p class="text-sm">© 2025 NCCAA लुम्बिनी प्रदेश | सर्वाधिकार सुरक्षित</p>
  </footer>

  <script>
    // Load districts based on selected province
    const districtsData = <?= json_encode($districts_data['province_districts']) ?>;
    
    function loadDistricts(province) {
      const districtSelect = document.getElementById('districtSelect');
      const districts = districtsData[province] || [];
      
      districtSelect.innerHTML = '<option value="">छान्नुहोस्</option>';
      districts.forEach(district => {
        const option = document.createElement('option');
        option.value = district;
        option.textContent = district;
        districtSelect.appendChild(option);
      });
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
      loadDistricts('लुम्बिनी प्रदेश');
    });
  </script>
</body>
</html>