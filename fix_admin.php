<?php
require_once 'includes/config.php';

// Delete all admin users
$pdo->exec("DELETE FROM admin_users");

// Create fresh admin user
$username = 'admin';
$password = 'admin@123';
$email = 'nccaalumbini@gmail.com';

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
$stmt->execute([$username, $hash, $email]);

echo "✅ Fresh admin user created<br>";
echo "Username: $username<br>";
echo "Password: $password<br>";
echo "New Hash: $hash<br>";

// Test the password immediately
if (password_verify($password, $hash)) {
    echo "✅ Password verification works!<br>";
} else {
    echo "❌ Password verification still fails<br>";
}

echo "<br><a href='admin/login.php'>Try Login Now</a>";
?>