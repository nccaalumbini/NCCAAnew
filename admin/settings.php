<?php
require_once '../includes/config.php';
requireLogin();

$success = '';
$error = '';

// Handle settings update
if ($_POST) {
    try {
        // This would normally update database settings
        $success = 'सेटिङहरू सफलतापूर्वक अपडेट गरियो।';
    } catch (Exception $e) {
        $error = 'सेटिङ अपडेट गर्न सकिएन।';
    }
}
?>
<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCCAA Admin - सेटिङहरू</title>
<?php include 'includes/styles.php'; ?>
</head>
<body class="min-h-screen flex flex-col lg:flex-row bg-gray-50">
<?php include 'includes/sidebar.php'; ?>

<main id="main-content" class="flex-1 ml-64 p-4 lg:p-6 transition-all duration-300">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">सेटिङहरू</h1>
            <p class="text-gray-500 text-sm">सिस्टम कन्फिगरेसन र प्राथमिकताहरू</p>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings Menu -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-card border border-card-border">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-800">सेटिङ श्रेणीहरू</h3>
                </div>
                <nav class="p-2">
                    <a href="#general" class="setting-tab active flex items-center px-4 py-3 text-sm rounded-lg mb-1">
                        <i class="fas fa-cog mr-3 text-primary-600"></i>सामान्य सेटिङ
                    </a>
                    <a href="#appearance" class="setting-tab flex items-center px-4 py-3 text-sm rounded-lg mb-1">
                        <i class="fas fa-palette mr-3 text-gray-400"></i>देखावट
                    </a>
                    <a href="#notifications" class="setting-tab flex items-center px-4 py-3 text-sm rounded-lg mb-1">
                        <i class="fas fa-bell mr-3 text-gray-400"></i>सूचनाहरू
                    </a>
                    <a href="#security" class="setting-tab flex items-center px-4 py-3 text-sm rounded-lg mb-1">
                        <i class="fas fa-shield-alt mr-3 text-gray-400"></i>सुरक्षा
                    </a>
                    <a href="#backup" class="setting-tab flex items-center px-4 py-3 text-sm rounded-lg">
                        <i class="fas fa-database mr-3 text-gray-400"></i>ब्याकअप
                    </a>
                </nav>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="lg:col-span-2">
            <!-- General Settings -->
            <div id="general-panel" class="setting-panel bg-white rounded-lg shadow-card border border-card-border p-6">
                <div class="flex items-center mb-6">
                    <div class="h-10 w-10 rounded-lg bg-primary-100 flex items-center justify-center mr-4">
                        <i class="fas fa-cog text-primary-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">सामान्य सेटिङ</h3>
                </div>

                <form method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">संस्थाको नाम</label>
                        <input type="text" class="form-input w-full" value="NCCAA लुम्बिनी प्रदेश" readonly>
                        <p class="text-xs text-gray-500 mt-1">यो सुविधा छिट्टै उपलब्ध हुनेछ</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">सम्पर्क इमेल</label>
                        <input type="email" class="form-input w-full" value="admin@nccaa.gov.np" readonly>
                        <p class="text-xs text-gray-500 mt-1">यो सुविधा छिट्टै उपलब्ध हुनेछ</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">फोन नम्बर</label>
                        <input type="tel" class="form-input w-full" value="+977-1-234567" readonly>
                        <p class="text-xs text-gray-500 mt-1">यो सुविधा छिट्टै उपलब्ध हुनेछ</p>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-800">स्वचालित ब्याकअप</h4>
                            <p class="text-sm text-gray-600">दैनिक डाटा ब्याकअप सक्षम गर्नुहोस्</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked disabled>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-500"></div>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <button type="button" class="btn-primary" onclick="showComingSoon()">
                            <i class="fas fa-save mr-2"></i>परिवर्तनहरू सेभ गर्नुहोस्
                        </button>
                    </div>
                </form>
            </div>

            <!-- Appearance Settings -->
            <div id="appearance-panel" class="setting-panel bg-white rounded-lg shadow-card border border-card-border p-6 hidden">
                <div class="flex items-center mb-6">
                    <div class="h-10 w-10 rounded-lg bg-purple-100 flex items-center justify-center mr-4">
                        <i class="fas fa-palette text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800">देखावट सेटिङ</h3>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-4">थिम छनोट गर्नुहोस्</label>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="theme-option active p-4 border-2 border-primary-500 rounded-lg cursor-pointer">
                                <div class="w-full h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded mb-2"></div>
                                <p class="text-sm font-medium text-center">नीलो (डिफल्ट)</p>
                            </div>
                            <div class="theme-option p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500">
                                <div class="w-full h-16 bg-gradient-to-r from-green-500 to-green-600 rounded mb-2"></div>
                                <p class="text-sm font-medium text-center">हरियो</p>
                            </div>
                            <div class="theme-option p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-purple-500">
                                <div class="w-full h-16 bg-gradient-to-r from-purple-500 to-purple-600 rounded mb-2"></div>
                                <p class="text-sm font-medium text-center">बैजनी</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <h4 class="font-medium text-gray-800">डार्क मोड</h4>
                            <p class="text-sm text-gray-600">अँध्यारो थिम सक्षम गर्नुहोस्</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" disabled>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-500"></div>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <button type="button" class="btn-primary" onclick="showComingSoon()">
                            <i class="fas fa-paint-brush mr-2"></i>थिम लागू गर्नुहोस्
                        </button>
                    </div>
                </div>
            </div>

            <!-- Coming Soon Panel -->
            <div id="coming-soon-panel" class="setting-panel bg-white rounded-lg shadow-card border border-card-border p-8 text-center hidden">
                <div class="mb-6">
                    <div class="h-20 w-20 rounded-full bg-yellow-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-tools text-yellow-600 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2">विकास भइरहेको छ</h3>
                    <p class="text-gray-600">यो सुविधा छिट्टै उपलब्ध हुनेछ। कृपया पर्खनुहोस्।</p>
                </div>
                
                <div class="bg-blue-50 rounded-lg p-6 mb-6">
                    <h4 class="font-semibold text-blue-800 mb-2">आउने सुविधाहरू:</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• उन्नत सुरक्षा सेटिङहरू</li>
                        <li>• स्वचालित सूचना प्रणाली</li>
                        <li>• डाटा ब्याकअप र रिस्टोर</li>
                        <li>• थिम कस्टमाइजेसन</li>
                        <li>• प्रयोगकर्ता अनुमति व्यवस्थापन</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/scripts.php'; ?>
<script>
// Settings tab functionality
document.querySelectorAll('.setting-tab').forEach(tab => {
    tab.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all tabs
        document.querySelectorAll('.setting-tab').forEach(t => {
            t.classList.remove('active', 'bg-primary-50', 'text-primary-700');
            t.classList.add('text-gray-600', 'hover:bg-gray-50');
            t.querySelector('i').classList.remove('text-primary-600');
            t.querySelector('i').classList.add('text-gray-400');
        });
        
        // Add active class to clicked tab
        this.classList.add('active', 'bg-primary-50', 'text-primary-700');
        this.classList.remove('text-gray-600', 'hover:bg-gray-50');
        this.querySelector('i').classList.add('text-primary-600');
        this.querySelector('i').classList.remove('text-gray-400');
        
        // Hide all panels
        document.querySelectorAll('.setting-panel').forEach(panel => {
            panel.classList.add('hidden');
        });
        
        // Show appropriate panel
        const target = this.getAttribute('href').substring(1);
        if (target === 'general') {
            document.getElementById('general-panel').classList.remove('hidden');
        } else if (target === 'appearance') {
            document.getElementById('appearance-panel').classList.remove('hidden');
        } else {
            document.getElementById('coming-soon-panel').classList.remove('hidden');
        }
    });
});

// Theme selection
document.querySelectorAll('.theme-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.theme-option').forEach(opt => {
            opt.classList.remove('active', 'border-primary-500');
            opt.classList.add('border-gray-200');
        });
        this.classList.add('active', 'border-primary-500');
        this.classList.remove('border-gray-200');
        
        showComingSoon();
    });
});

function showComingSoon() {
    alert('यो सुविधा छिट्टै उपलब्ध हुनेछ!');
}
</script>

<style>
.setting-tab.active {
    background-color: rgb(240 249 255);
    color: rgb(29 78 216);
}
</style>
</body>
</html>