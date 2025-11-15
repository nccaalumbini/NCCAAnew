<?php
require_once '../includes/config.php';

if ($_POST) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    echo "<h3>Debug Login Process</h3>";
    echo "Submitted Username: " . htmlspecialchars($username) . "<br>";
    echo "Submitted Password: " . htmlspecialchars($password) . "<br><br>";
    
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user) {
        echo "✅ User found in database<br>";
        echo "DB Username: " . $user['username'] . "<br>";
        echo "DB Password Hash: " . $user['password'] . "<br><br>";
        
        if (password_verify($password, $user['password'])) {
            echo "✅ Password verification successful<br>";
            echo "Redirecting to dashboard...<br>";
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            echo "<a href='dashboard.php'>Go to Dashboard</a>";
        } else {
            echo "❌ Password verification failed<br>";
            echo "Testing with different passwords:<br>";
            
            $testPasswords = ['admin123', 'admin@123', 'admin'];
            foreach ($testPasswords as $testPw) {
                if (password_verify($testPw, $user['password'])) {
                    echo "✅ Password '$testPw' works!<br>";
                } else {
                    echo "❌ Password '$testPw' doesn't work<br>";
                }
            }
        }
    } else {
        echo "❌ User not found in database<br>";
        
        // Show all users
        $allUsers = $pdo->query("SELECT username FROM admin_users")->fetchAll();
        echo "Available users: ";
        foreach ($allUsers as $u) {
            echo $u['username'] . " ";
        }
    }
}
?>

<form method="POST">
    <h3>Test Login</h3>
    Username: <input type="text" name="username" value="admin"><br><br>
    Password: <input type="password" name="password" value="admin@123"><br><br>
    <button type="submit">Test Login</button>
</form>

<br><a href="login.php">Back to Login</a>