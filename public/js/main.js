// Captcha functionality
function generateCaptcha() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
    let captcha = '';
    for (let i = 0; i < 6; i++) {
        captcha += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return captcha;
}

function initCaptcha() {
    const captchaElement = document.getElementById('captcha-text');
    if (captchaElement) {
        const captcha = generateCaptcha();
        captchaElement.textContent = captcha;
        captchaElement.dataset.captcha = captcha;
    }
}

function verifyCaptcha() {
    const captchaText = document.getElementById('captcha-text');
    const userInput = document.getElementById('captcha-input');
    
    if (captchaText && userInput) {
        if (captchaText.dataset.captcha === userInput.value) {
            window.location.href = 'home.php';
        } else {
            alert('गलत क्याप्चा! कृपया फेरि प्रयास गर्नुहोस्।');
            initCaptcha();
            userInput.value = '';
        }
    }
}

// Signature pad functionality
let signaturePad;

function initSignaturePad() {
    const canvas = document.getElementById('signature-pad');
    if (canvas) {
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: 'rgb(0, 0, 0)'
        });
        
        // Resize canvas
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext('2d').scale(ratio, ratio);
            signaturePad.clear();
        }
        
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();
    }
}

function clearSignature() {
    if (signaturePad) {
        signaturePad.clear();
    }
}

// Form validation
function validateForm() {
    const requiredFields = document.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = '#dc3545';
            isValid = false;
        } else {
            field.style.borderColor = '#ddd';
        }
    });
    
    // Validate signature
    if (signaturePad && signaturePad.isEmpty()) {
        alert('कृपया हस्ताक्षर गर्नुहोस्।');
        isValid = false;
    }
    
    return isValid;
}

// Form submission
function submitForm() {
    if (!validateForm()) {
        return false;
    }
    
    // Get signature data
    if (signaturePad) {
        const signatureData = signaturePad.toDataURL();
        document.getElementById('signature-data').value = signatureData;
    }
    
    return true;
}

// Modal functionality
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Confirmation dialogs
function confirmDelete(id, name) {
    if (confirm(`के तपाईं ${name} को फारम मेटाउन चाहनुहुन्छ?`)) {
        window.location.href = `delete_form.php?id=${id}`;
    }
}

function confirmPrint(id) {
    if (confirm('के तपाईं यो फारम प्रिन्ट गर्न चाहनुहुन्छ?')) {
        window.open(`print_form.php?id=${id}`, '_blank');
    }
}

// Search functionality
function searchTable() {
    const input = document.getElementById('search-input');
    const filter = input.value.toUpperCase();
    const table = document.getElementById('data-table');
    const tr = table.getElementsByTagName('tr');
    
    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName('td');
        let found = false;
        
        for (let j = 0; j < td.length; j++) {
            if (td[j]) {
                let txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        
        tr[i].style.display = found ? '' : 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initCaptcha();
    initSignaturePad();
    
    // Close modals when clicking outside
    window.onclick = function(event) {
        const modals = document.getElementsByClassName('modal');
        for (let modal of modals) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    };
});

// Auto-hide alerts
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 300);
    });
}, 5000);