<?php
require_once 'config/database.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $username = 'admin';
    $password = 'password123';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // Update password
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->execute([$hashed_password, $username]);
        echo "Password for '$username' has been reset to '$password'.";
    } else {
        // Create user
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'HR Head')");
        $stmt->execute([$username, $hashed_password]);
        echo "User '$username' created with password '$password'.";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>