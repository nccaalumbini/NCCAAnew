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
// Prepare safe variables to avoid undefined index warnings
$full_name = $form['full_name'] ?? '';
$ncc_batch_number = $form['ncc_batch_number'] ?? '';
$ncc_personal_number = $form['ncc_personal_number'] ?? '';
$ncc_division = $form['ncc_division'] ?? '';
$ncc_rank_position = $form['ncc_rank_position'] ?? '';
$ncc_passout_school = $form['ncc_passout_school'] ?? '';
$nccaa_position = $form['nccaa_position_applied'] ?? '';
$contact_number = $form['contact_number'] ?? '';
$email = $form['email'] ?? '';
$address = $form['address'] ?? '';
$age = $form['age'] ?? '';
$gender = $form['gender'] ?? '';
$application_bs_date = $form['application_bs_date'] ?? '';
if (empty($application_bs_date)) {
    // fallback to formatted created_at (gregorian) if BS date not provided
    $application_bs_date = date('Y/m/d', strtotime($form['created_at'] ?? 'now'));
}
?>
<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>मनोनयन आवेदन फारम - <?= htmlspecialchars($form['full_name']) ?></title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #4a5d23;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #4a5d23;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .header h2 {
            color: #666;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }
        
        .form-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .form-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .form-item.full-width {
            grid-column: 1 / -1;
        }
        
        .label {
            font-weight: bold;
            color: #4a5d23;
            margin-bottom: 5px;
        }
        
        .value {
            color: #333;
        }
        
        .signature-section {
            text-align: center;
            margin: 40px 0;
            padding: 20px;
            border: 2px solid #4a5d23;
            border-radius: 10px;
        }
        
        .signature-section img {
            max-width: 200px;
            border: 1px solid #ddd;
            padding: 10px;
            background: white;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #4a5d23;
            font-size: 0.9rem;
            color: #666;
        }
        
        .print-btn {
            background: #4a5d23;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin: 20px 0;
        }
        /* New print layout styles */
        .paper {
            padding: 30px 40px;
            border: none;
            background: white;
        }
        .title {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .date-right {
            text-align: right;
            margin-bottom: 18px;
            font-size: 14px;
        }
        .recipient { margin: 10px 0 20px 0; font-size: 14px; }
        .body-text { margin: 10px 0 20px 0; font-size: 14px; line-height: 1.6; }
        .info-table { margin-top: 18px; border-top: 1px solid #ddd; padding-top: 12px; }
        .info-table .row { display: flex; padding: 6px 0; }
        .info-table .label { width: 200px; font-weight: 600; color: #111; }
        .info-table .value { flex: 1; color: #111; }
        .signature-box { margin-top: 28px; text-align: center; }
        .sig-label { margin-bottom: 8px; font-weight: 600; }
        .sig-img { max-width: 220px; border: none; }
        .sig-placeholder { width: 220px; height: 60px; border-bottom: 1px solid #111; margin: 0 auto; }
        @page { size: A4; margin: 20mm; }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="print-btn">प्रिन्ट गर्नुहोस्</button>
        <button onclick="window.close()" class="print-btn" style="background: #666; margin-left: 10px;">बन्द गर्नुहोस्</button>
    </div>

    <div class="paper">
        <div class="title">मनोनयन आवेदन फाराम</div>

        <div class="date-right">मिति: <?= date('Y/m/d', strtotime($form['created_at'])) ?></div>

        <div class="recipient">
            <p>श्रीमान् पत्नपति ज्यु,</p>
            <p>श्री मध्य पश्चिम प्रथम मुख्यालय,</p>
            <p>योग्युक्ति कार्यालय, रुपन्देही, नेपाल ।</p>
        </div>

        <div class="body-text">
            <p><strong>विषय:</strong> एन.सि.सि अलुमनाइ एशोसिएशन नेपाल लुम्बिनी प्रदेश <?= htmlspecialchars($nccaa_position ?: '') ?> पदमा मनोनयन गरिदिनु हुन।</p>

            <p>
                म, राष्ट्रिय सेवा दलको <strong><?= htmlspecialchars($ncc_batch_number ?: '-') ?></strong> औं ब्याच, <strong><?= htmlspecialchars($ncc_division ?: '-') ?></strong> डिभिजनबाट दीक्षित, अनुशासित, ईमानदार, कर्तव्यनिष्ठ, नेतृत्वकारी र राष्ट्रियताको भावना भएको व्यक्ति हुँ।
                विगत <strong>२०७५</strong> सालदेखि समाजसेवा/स्वयंसेवकको रूपमा सक्रिय रहेकाले म पाल्पा जिल्ला निर्देशक तथा लुम्बिनी प्रदेश निमित्त निर्देशकको जिम्मेवारी सम्हालेको छु र संगठनलाई आवश्यकता परेमा योगदान दिन सधैं तत्पर छु।
                मैले कुनै पनि राजनीतिक दलसँग आवद्धता नराखेको छु र एन.सि.सि अलुमनाइ एशोसिएशनको विधान अनुसार योग्य र अनुभवी पूर्व एन.सि.सि क्याडेट भएकोले प्रदेश स्तरमा निर्देशक पदका लागि मनोनयनको लागी यो आवेदन स्वइच्छाले पेश गरेको छु।
            </p>
        </div>

        <div class="info-table">
            <div class="row"><div class="label">व्य.न. :</div><div class="value"><?= htmlspecialchars($form['ncc_personal_number'] ?: '-') ?></div></div>
            <div class="row"><div class="label">दर्जा :</div><div class="value"><?= htmlspecialchars($form['ncc_rank_position'] ?: '-') ?></div></div>
            <div class="row"><div class="label">नामथर :</div><div class="value"><?= htmlspecialchars($form['full_name']) ?></div></div>
            <div class="row"><div class="label">उमेर :</div><div class="value"><?= htmlspecialchars($form['age']) ?></div></div>
            <div class="row"><div class="label">लिङ्ग :</div><div class="value"><?= htmlspecialchars($form['gender']) ?></div></div>
            <div class="row"><div class="label">सम्पर्क नं. :</div><div class="value"><?= htmlspecialchars($form['contact_number']) ?></div></div>
            <div class="row"><div class="label">इमेल :</div><div class="value"><?= htmlspecialchars($form['email'] ?: '-') ?></div></div>
            <div class="row"><div class="label">ठेगाना :</div><div class="value"><?= nl2br(htmlspecialchars($form['address'])) ?></div></div>
            <div class="row"><div class="label">दीक्षित विद्यालयको नाम :</div><div class="value"><?= htmlspecialchars($form['ncc_passout_school'] ?: '-') ?></div></div>
        </div>

        <div class="signature-box">
            <div class="sig-label">आवेदकको दस्तखत</div>
            <?php if (!empty($form['signature_path'])): ?>
                <img src="../<?= htmlspecialchars($form['signature_path']) ?>" alt="signature" class="sig-img">
            <?php else: ?>
                <div class="sig-placeholder"></div>
            <?php endif; ?>
        </div>

        <div class="footer">NCCAA लुम्बिनी प्रदेश</div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>