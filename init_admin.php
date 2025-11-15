<?php
require_once 'includes/config.php';

// Clear and create admin user
$pdo->exec("DELETE FROM admin_users");

$hash = password_hash('admin@123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
$stmt->execute(['admin', $hash, 'nccaalumbini@gmail.com']);

echo "Admin user created. <a href='admin/debug_login.php'>Test Login</a>";
?>