<?php
// One-time admin creation script for production
// Run this ONCE after deployment, then delete this file

require_once 'includes/config.php';

if ($_POST) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);
    
    if (strlen($password) < 8) {
        $error = "Password must be at least 8 characters";
    } else {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, email) VALUES (?, ?, ?)");
            $stmt->execute([$username, $hash, $email]);
            
            echo "✅ Admin user created successfully!<br>";
            echo "Username: " . htmlspecialchars($username) . "<br>";
            echo "⚠️ DELETE THIS FILE IMMEDIATELY<br>";
            echo "<a href='admin/login.php'>Go to Login</a>";
            exit;
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Admin User</title>
    <style>
        body { font-family: Arial; max-width: 400px; margin: 50px auto; padding: 20px; }
        input, button { width: 100%; padding: 10px; margin: 5px 0; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Create Admin User</h2>
    <p><strong>⚠️ Run this ONCE, then DELETE this file!</strong></p>
    
    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password (min 8 chars)" required>
        <button type="submit">Create Admin</button>
    </form>
</body>
</html>