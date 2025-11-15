<?php
require 'includes/config.php';
try {
    $ds = $pdo->query("SELECT district, COUNT(*) as count FROM cadet_forms GROUP BY district ORDER BY count DESC LIMIT 5")->fetchAll();
    echo "district query ok\n";
    $ps = $pdo->query("SELECT nccaa_position_applied AS post, COUNT(*) as count FROM cadet_forms GROUP BY nccaa_position_applied ORDER BY count DESC")->fetchAll();
    echo "post query ok\n";
    $rf = $pdo->query("SELECT * FROM cadet_forms ORDER BY created_at DESC LIMIT 5")->fetchAll();
    echo "recent query ok\n";
} catch (PDOException $e) {
    echo 'ERROR: '.$e->getMessage()."\n";
}
