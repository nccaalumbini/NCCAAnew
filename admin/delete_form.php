<?php
require_once '../includes/config.php';
requireLogin();

if (!isset($_GET['id'])) {
    header('Location: forms.php');
    exit;
}

try {
    // Get form details first to delete signature file
    $stmt = $pdo->prepare("SELECT signature_path FROM cadet_forms WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $form = $stmt->fetch();
    
    if ($form && $form['signature_path'] && file_exists('../' . $form['signature_path'])) {
        unlink('../' . $form['signature_path']);
    }
    
    // Delete the form record
    $stmt = $pdo->prepare("DELETE FROM cadet_forms WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    
    $_SESSION['success'] = 'आवेदन सफलतापूर्वक मेटाइयो।';
} catch (Exception $e) {
    $_SESSION['error'] = 'आवेदन मेटाउन सकिएन।';
}

header('Location: forms.php');
exit;
?>