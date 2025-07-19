<?php
session_start();
session_unset();
session_destroy();

// Safe redirect
$redirectTo = $_POST['redirect'] ?? 'index.php';

// Prevent open redirect (optional security)
$allowedRedirects = ['index.php', 'login.php', 'dashboard.php'];
if (!in_array($redirectTo, $allowedRedirects)) {
    $redirectTo = 'index.php';
}

header("Location: $redirectTo");
exit();
