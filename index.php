<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCCAA Lumbini Province - मानव प्रमाणीकरण</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              nccaa: '#2E7A56'
            }
          }
        }
      }
    </script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="max-w-md w-full p-6">
        <div class="bg-white rounded-lg shadow-xl">
            <div class="p-8 text-center border-b">
                <img src="public/images/hero.png" alt="NCCAA Logo" class="h-16 w-auto mx-auto mb-4">
                <h1 class="text-2xl font-semibold text-gray-800">मानव प्रमाणीकरण</h1>
                <p class="text-gray-600 text-sm mt-2">NCCAA लुम्बिनी प्रदेश सुरक्षा जाँच</p>
            </div>

            <div class="p-8">
                <p class="text-gray-700 text-center mb-6">कृपया तलको कोड टाइप गर्नुहोस्:</p>
                
                <!-- CAPTCHA Display -->
                <div class="bg-gray-100 border-2 border-gray-300 rounded-lg p-6 mb-6 text-center">
                    <div id="captcha-text" class="text-4xl font-bold text-nccaa tracking-widest font-mono"></div>
                </div>
                
                <!-- Input Field -->
                <div class="mb-6">
                    <input type="text" id="captcha-input" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-nccaa focus:ring-2 focus:ring-nccaa text-center tracking-widest" 
                           placeholder="कोड यहाँ टाइप गर्नुहोस्" maxlength="6">
                </div>
                
                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="button" onclick="verifyCaptcha()" class="flex-1 px-4 py-2 bg-nccaa text-white rounded-lg font-medium hover:bg-green-700 transition">
                        ✓ प्रमाणित गर्नुहोस्
                    </button>
                    <button type="button" onclick="initCaptcha()" class="flex-1 px-4 py-2 bg-gray-300 text-gray-800 rounded-lg font-medium hover:bg-gray-400 transition">
                        🔄 नयाँ कोड
                    </button>
                </div>

                <p class="text-xs text-gray-500 text-center mt-4">
                    यो पेज स्वचालित स्पाम रोकको लागि डिजाइन गरिएको छ
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-xs text-gray-500">
            <p>© 2025 NCCAA लुम्बिनी प्रदेश</p>
        </div>
    </div>
    
    <script src="public/js/main.js"></script>
</body>
</html>