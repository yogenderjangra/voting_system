<?php
 
session_start();
require_once 'includes/db.php';

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($role === 'voter') {
        // Voter login
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['role'] = 'voter';
            
            
            header("Location: dashboard.php");
            exit();
        } else {
            // Login failed
            header("Location: index.php?login=fail");
            exit();
        }
    } elseif ($role === 'admin') {
        // Admin login
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

       if ($admin && $password === $admin['password']) {
            // Login successful
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['role'] = 'admin';
            header("Location: admin/admin_dashboard.php"); // <--- FIXED THIS LINE
            exit();
        } else {
            // Login failed
            header("Location: index.php?login=fail");
            exit();
        }
    } else {
        // Invalid role
        header("Location: index.php?login=fail");
        exit();
    }
} else {
    // Not a POST request, redirect to index
    header("Location: index.php");
    exit();
}
?>