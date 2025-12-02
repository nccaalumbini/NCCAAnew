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
<?php include 'includes/styles.php'; ?>
</head>
<body class="min-h-screen flex flex-col lg:flex-row bg-gray-50">
<?php include 'includes/sidebar.php'; ?>

  <main id="main-content" class="flex-1 ml-64 p-4 lg:p-6 transition-all duration-300">
    <div class="flex justify-between items-center mb-8">
      <div>
        <h1 class="text-3xl font-semibold text-gray-800">आवेदन विवरण</h1>
        <p class="text-gray-600 text-sm mt-1"><?= htmlspecialchars($form['full_name']) ?> को पूरा विवरण</p>
      </div>
      <div class="flex gap-3">
        <button onclick="showPreview('print')" class="px-6 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 flex items-center">
          <i class="fas fa-print mr-2"></i>प्रिन्ट गर्नुहोस्
        </button>
        <button onclick="showPreview('download')" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 flex items-center">
          <i class="fas fa-download mr-2"></i>डाउनलोड गर्नुहोस्
        </button>
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

  <!-- Preview Modal -->
  <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden">
      <div class="flex justify-between items-center p-4 border-b">
        <h3 class="text-lg font-semibold">फारम पूर्वावलोकन</h3>
        <button onclick="closePreview()" class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>
      <div class="p-4 overflow-y-auto max-h-[70vh]">
        <div id="formPreview" class="bg-white"></div>
      </div>
      <div class="flex justify-end gap-3 p-4 border-t">
        <button onclick="closePreview()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400">रद्द गर्नुहोस्</button>
        <button id="confirmBtn" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700"></button>
      </div>
    </div>
  </div>

<?php include 'includes/scripts.php'; ?>
<script>
let currentAction = '';

function showPreview(action) {
  currentAction = action;
  const modal = document.getElementById('previewModal');
  const preview = document.getElementById('formPreview');
  const confirmBtn = document.getElementById('confirmBtn');
  
  if (action === 'print') {
    confirmBtn.innerHTML = '<i class="fas fa-print mr-2"></i>प्रिन्ट गर्नुहोस्';
    confirmBtn.onclick = confirmPrint;
  } else {
    confirmBtn.innerHTML = '<i class="fas fa-download mr-2"></i>डाउनलोड गर्नुहोस्';
    confirmBtn.onclick = confirmDownload;
  }
  
  preview.innerHTML = generateFormHTML();
  modal.classList.remove('hidden');
}

function closePreview() {
  document.getElementById('previewModal').classList.add('hidden');
}

function confirmPrint() {
  const printWindow = window.open('', '_blank');
  printWindow.document.write(`
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="UTF-8">
      <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;600;700&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Noto Sans Devanagari', sans-serif; font-size: 12px; line-height: 1.6; color: #000; }
        @page { size: A4; margin: 20mm; }
        @media print {
          @page { margin: 0; }
          body { margin: 20mm; }
        }
        .form-container { max-width: 100%; }
        .title { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; }
        .date-line { text-align: right; margin-bottom: 20px; }
        .recipient { margin-bottom: 20px; line-height: 1.8; }
        .subject { margin: 20px 0; line-height: 1.8; }
        .content { margin: 15px 0; text-align: justify; line-height: 1.8; }
        .info-section { margin-top: 30px; }
        .info-line { margin-bottom: 8px; }
        .signature-section { margin-top: 50px; text-align: right; }
        .signature-line { border-bottom: 1px solid #000; width: 200px; margin: 30px 0 10px auto; }
      </style>
    </head>
    <body onload="window.print(); window.close();">
      ${generateFormHTML()}
    </body>
    </html>
  `);
  printWindow.document.close();
  closePreview();
}

function confirmDownload() {
  // Create a blob with proper encoding
  const formData = <?= json_encode($form) ?>;
  const htmlContent = `
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Devanagari:wght@400;600;700&display=swap');
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Noto Sans Devanagari', Arial, sans-serif; font-size: 12px; line-height: 1.6; color: #000; }
    @page { size: A4; margin: 20mm; }
    .form-container { max-width: 100%; padding: 20px; }
    .title { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; }
    .date-line { text-align: right; margin-bottom: 20px; }
    .recipient { margin-bottom: 20px; line-height: 1.8; }
    .subject { margin: 20px 0; line-height: 1.8; }
    .content { margin: 15px 0; text-align: justify; line-height: 1.8; }
    .info-section { margin-top: 30px; }
    .info-line { margin-bottom: 8px; }
    .signature-section { margin-top: 50px; text-align: right; }
    .signature-line { border-bottom: 1px solid #000; width: 200px; margin: 30px 0 10px auto; }
  </style>
</head>
<body>
${generateFormHTML()}
</body>
</html>`;

  // Create blob and download
  const blob = new Blob([htmlContent], { type: 'text/html;charset=utf-8' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = 'Application_Form_' + (formData.full_name || 'form').replace(/[^a-zA-Z0-9]/g, '_') + '.html';
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
  
  closePreview();
}

function generateFormHTML() {
  const formData = <?= json_encode($form) ?>;
  
  return `
    <div class="form-container" style="font-family: 'Noto Sans Devanagari', sans-serif; font-size: 12px; line-height: 1.6; max-width: 210mm; margin: 0 auto; padding: 20px;">
      <div class="title" style="text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px;">
        मनोनयन आवेदन फारम
      </div>
      
      <div class="date-line" style="text-align: right; margin-bottom: 20px;">
        मिति: <?= date('Y/m/d', strtotime($form['created_at'])) ?> / गते
      </div>
      
      <div class="recipient" style="margin-bottom: 20px; line-height: 1.8;">
        श्रीमान् पत्नपति ज्यु,<br>
        श्री मध्य पश्चिम प्रथम मुख्यालय,<br>
        योग्युक्ति कार्यालय, रुपन्देही, नेपाल ।
      </div>
      
      <div class="subject" style="margin: 20px 0; line-height: 1.8;">
        <strong>विषय:</strong> एन.सि.सि अल्मुनाई एशोसिएशन नेपाल लुम्बिनी प्रदेश <u>${formData.nccaa_position_applied || '........................'}</u> पदमा मनोनयन गरिदिनु हुन।
      </div>
      
      <div class="content" style="margin: 15px 0; text-align: justify; line-height: 1.8;">
        म, राष्ट्रिय सेवा दलको <u>${formData.ncc_batch_number || '........'}</u> औं ब्याच <u>${formData.ncc_division || '........'}</u> डिभिजन तालिमबाट दीक्षित,अनुशासित, ईमानदार, कर्तव्यनिष्ठ, नेतृतवकारी र राष्ट्रियताको भावनाले प्रेरित भई विगत <u>${formData.ncc_passout_year || '........'}</u> सालबाट समाज सेवा/स्वयंसेवकको रूपमा <u>${formData.district || '........'}</u> जिल्ला/क्षेत्रमा सक्रिय रहेको, सबैसँग समन्वय गरी संगठनलाई परेको समयमा योगदान दिन सक्ने र कुनै पनि राजनैतिक दलसंग आवद्ध नभएको तथा एन.सि.सि अल्मुनाई एशोसिएशनको विधान अनुसार योग्य र अनुभवी पूर्व एन.सि.सि. क्याडेट भएको हुनाले प्रदेश स्तरमा रहेको एन.सि.सि. अल्मुनाई एशोसियशन नेपालको लुम्बिनी प्रदेश <u>${formData.nccaa_position_applied || '........................'}</u> पदमा मनोनयनको लागी स्वईच्छाले यो आवेदन पेश गरेको छु ।
      </div>
      
      <div class="info-section" style="margin-top: 30px;">
        <div class="info-line" style="margin-bottom: 8px;">व्य.नं. <u>${formData.ncc_personal_number || '..................'}</u></div>
        <div class="info-line" style="margin-bottom: 8px;">दर्जा : <u>${formData.ncc_rank_position || '..................'}</u></div>
        <div class="info-line" style="margin-bottom: 8px;">नामथर: <u>${formData.full_name || '..................'}</u></div>
        <div class="info-line" style="margin-bottom: 8px;">उमेर : <u>${formData.age || '..................'}</u></div>
        <div class="info-line" style="margin-bottom: 8px;">लिङ्ग : <u>${formData.gender || '..................'}</u></div>
        <div class="info-line" style="margin-bottom: 8px;">सम्पर्क नं. <u>${formData.contact_number || '..................'}</u></div>
        <div class="info-line" style="margin-bottom: 8px;">इमेल : <u>${formData.email || '..................'}</u></div>
        <div class="info-line" style="margin-bottom: 8px;">ठेगाना : <u>${formData.address || '..................'}</u></div>
        <div class="info-line" style="margin-bottom: 8px;">दीक्षित विद्यालयको नाम : <u>${formData.ncc_passout_school || '..................'}</u></div>
      </div>
      
      <div class="signature-section" style="margin-top: 50px; text-align: right;">
        <div class="signature-line" style="border-bottom: 1px solid #000; width: 200px; margin: 30px 0 10px auto;"></div>
        <div>आवेदकको दस्तखत</div>
      </div>
    </div>
  `;
}
</script>
</body>
</html>