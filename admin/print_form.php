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
$ncc_passout_year = $form['ncc_passout_year'] ?? '२०७५';
$nccaa_position = $form['nccaa_position_applied'] ?? '';
$contact_number = $form['contact_number'] ?? '';
$email = $form['email'] ?? '';
$address = $form['address'] ?? '';
$age = $form['age'] ?? '';
$gender = $form['gender'] ?? '';
$district = $form['district'] ?? '';
$created_at = $form['created_at'] ?? date('Y-m-d');

// Convert BS date for display
function convertToBS($date) {
    // Simple fallback - in production you'd use a proper conversion library
    $year = (int)substr($date, 0, 4) + 56;
    $month = substr($date, 5, 2);
    $day = substr($date, 8, 2);
    return "$year/$month/$day";
}

$application_bs_date = convertToBS($created_at);
?>
<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>मनोनयन आवेदन फारम</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @font-face {
            font-family: 'NotoSansDevanagari';
            src: url('https://fonts.gstatic.com/s/notosansdevanagari/v23/ga6iaw1J5X0T9RN6H0FNwvxdR3E4.0.woff2') format('woff2');
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .print-container {
                box-shadow: none;
                page-break-after: avoid;
            }
        }

        body {
            font-family: 'NotoSansDevanagari', 'Segoe UI', Tahoma, Geneva, sans-serif;
            line-height: 1.7;
            color: #222;
            background: #f5f5f5;
            padding: 20px;
        }

        .print-container {
            max-width: 850px;
            margin: 0 auto;
            background: white;
            padding: 40px 50px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .no-print {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            justify-content: center;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-print {
            background: #2E7A56;
            color: white;
        }

        .btn-print:hover {
            background: #1e5239;
        }

        .btn-close {
            background: #999;
            color: white;
        }

        .btn-close:hover {
            background: #777;
        }

        /* Document content styles */
        .doc-title {
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .doc-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            font-size: 13px;
        }

        .doc-meta-date {
            text-align: right;
        }

        .recipient-block {
            margin-bottom: 20px;
            line-height: 1.8;
            font-size: 13px;
        }

        .recipient-line {
            margin-bottom: 4px;
        }

        .subject-line {
            margin: 20px 0;
            font-size: 13px;
            line-height: 1.8;
        }

        .subject-label {
            font-weight: 600;
            display: inline;
        }

        .body-paragraph {
            margin: 15px 0;
            text-align: justify;
            font-size: 13px;
            line-height: 1.8;
            text-justify: inter-word;
        }

        .info-section {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }

        .info-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            margin-bottom: 8px;
            font-size: 13px;
            gap: 20px;
        }

        .info-label {
            font-weight: 600;
            color: #111;
        }

        .info-value {
            color: #333;
            word-break: break-word;
        }

        .signature-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            padding-top: 20px;
        }

        .signature-block {
            text-align: center;
        }

        .sig-line {
            border-bottom: 1px solid #222;
            margin: 50px 0 5px 0;
            height: 1px;
        }

        .sig-label {
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }

        .attestation-center {
            text-align: center;
            margin-top: 40px;
            font-size: 13px;
            font-weight: 600;
        }

        /* A4 page setup */
        @page {
            size: A4;
            margin: 15mm;
        }

        @media print {
            .print-container {
                box-shadow: none;
                max-width: 100%;
            }
            body {
                background: white;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button class="btn btn-print" onclick="window.print()">प्रिन्ट गर्नुहोस्</button>
        <button class="btn btn-close" onclick="window.history.back()">फिर्ता जानुहोस्</button>
    </div>

    <div class="print-container">
        <!-- Title -->
        <div class="doc-title">मनोनयन आवेदन फाराम</div>

        <!-- Date and Meta Info -->
        <div class="doc-meta">
            <div class="doc-meta-left"></div>
            <div class="doc-meta-date">मिति: <?= htmlspecialchars($application_bs_date) ?></div>
        </div>

        <!-- Recipient Block -->
        <div class="recipient-block">
            <div class="recipient-line">श्रीमान् पत्नपति ज्यु,</div>
            <div class="recipient-line">श्री मध्य पश्चिम प्रथम मुख्यालय,</div>
            <div class="recipient-line">योग्युक्ति कार्यालय, रुपन्देही, नेपाल ।</div>
        </div>

        <!-- Subject Line -->
        <div class="subject-line">
            <span class="subject-label">विषय:</span>
            एन.सि.सि अलुमनाइ एशोसिएशन नेपाल <?= htmlspecialchars($form['province'] ?: 'लुम्बिनी प्रदेश') ?> — <?= htmlspecialchars($nccaa_position ?: '--------') ?> पदमा मनोनयन गरिदिनु हुन।
        </div>

        <!-- Main Body -->
        <div class="body-paragraph">
            म, राष्ट्रिय सेवा दलको <?= htmlspecialchars($ncc_batch_number ?: '----------') ?> औं ब्याच, <?= htmlspecialchars($ncc_division ?: '---') ?> डिभिजनबाट दीक्षित, अनुशासित, ईमानदार, कर्तव्यनिष्ठ, नेतृत्वकारी र राष्ट्रियताको भावना भएको व्यक्ति हुँ। विगत <?= htmlspecialchars($ncc_passout_year ?: '२०७५') ?> सालदेखि समाजसेवा/स्वयंसेवकको रूपमा सक्रिय रहेकाले, म <?= htmlspecialchars($district ?: '........') ?> जिल्ला तथा <?= htmlspecialchars($form['province'] ?: 'लुम्बिनी प्रदेश') ?> निमित्त निर्देशकको जिम्मेवारी सम्हालेको छु र संगठनलाई आवश्यकता परेमा योगदान दिन सधैं तत्पर छु।
        </div>

        <div class="body-paragraph">
            मैले कुनै पनि राजनीतिक दलसँग आवद्धता नराखेको छु। एन.सि.सि अलुमनाइ एशोसिएशनको विधान अनुसार योग्य र अनुभवी पूर्व एन.सि.सि क्याडेट भएकोले, <?= htmlspecialchars($nccaa_position ?: 'पद') ?> मा मनोनयनको लागी यो आवेदन स्वइच्छाले पेश गरेको छु।
        </div>

        <!-- Information Section -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">व्य.न. :</div>
                <div class="info-value"><?= htmlspecialchars($ncc_personal_number ?: '-') ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">दर्जा :</div>
                <div class="info-value"><?= htmlspecialchars($ncc_rank_position ?: '-') ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">नामथर :</div>
                <div class="info-value"><?= htmlspecialchars($full_name) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">उमेर :</div>
                <div class="info-value"><?= htmlspecialchars($age) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">लिङ्ग :</div>
                <div class="info-value"><?= htmlspecialchars($gender) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">सम्पर्क नं. :</div>
                <div class="info-value"><?= htmlspecialchars($contact_number) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">इमेल :</div>
                <div class="info-value"><?= htmlspecialchars($email ?: '-') ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">ठेगाना :</div>
                <div class="info-value"><?= htmlspecialchars($district) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">दीक्षित विद्यालयको नाम :</div>
                <div class="info-value"><?= htmlspecialchars($ncc_passout_school ?: '-') ?></div>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-block">
                <div class="sig-line"></div>
                <div class="sig-label">आवेदकको दस्तखत</div>
            </div>
            <div class="signature-block">
                <div class="sig-line"></div>
                <div class="sig-label">मिति</div>
            </div>
        </div>

        <!-- Attestation -->
        <div class="attestation-center">
            आवेदकको दस्तखत
        </div>
    </div>

    <script>
        // Optional: uncomment to auto-print
        // window.addEventListener('load', function() {
        //     setTimeout(function() { window.print(); }, 500);
        // });
    </script>
</body>
</html>
