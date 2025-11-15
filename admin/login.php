<?php
require_once '../includes/config.php';

$error = '';

if ($_POST) {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        header('Location: /admin/dashboard.php');
        exit;
    } else {
        $error = 'गलत प्रयोगकर्ता नाम वा पासवर्ड।';
    }
}
?>
<!DOCTYPE html>
<html lang="ne">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>NCCAA Admin - लगइन</title>
        <link rel="stylesheet" href="../public/css/style.css">
        <!-- Tailwind CDN -->
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
        <style>
            /* Small override for legacy styles to avoid conflicts */
            body { background-color: #f8fafc; }
        </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-white">
    <div class="max-w-md w-full p-6">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6 text-center">
                <img src="../public/images/hero.png" alt="NCCAA" class="mx-auto h-20 w-auto mb-4 object-contain">
                <h1 class="text-2xl font-semibold text-gray-800">प्रशासक लगइन</h1>
                <p class="text-sm text-gray-500 mt-1">NCCAA लुम्बिनी प्रदेश - प्रशासक प्यानल</p>
            </div>

            <div class="p-6 border-t">
                <?php if ($error): ?>
                    <div class="mb-4 text-sm text-red-700 bg-red-50 rounded-md p-3">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700">प्रयोगकर्ता नाम</label>
                        <input type="text" id="username" name="username" required autofocus class="mt-1 block w-full px-4 py-2 border border-gray-200 rounded-md bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-nccaa">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">पासवर्ड</label>
                        <input type="password" id="password" name="password" required class="mt-1 block w-full px-4 py-2 border border-gray-200 rounded-md bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-nccaa">
                    </div>

                    <div>
                        <button type="submit" class="w-full px-4 py-2 bg-nccaa text-white rounded-md font-medium hover:bg-green-700 transition">लगइन गर्नुहोस्</button>
                    </div>
                </form>

                <div class="mt-4 flex items-center justify-between">
                    <a href="../home.php" class="text-sm text-gray-600 hover:underline">गृहपृष्ठमा फर्किनुहोस्</a>
                    <span class="text-sm text-gray-500">डिफल्ट: <strong>admin</strong> / <strong>admin@123</strong></span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>