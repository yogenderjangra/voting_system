<?php
session_start();
require_once 'db.php';

// Check if a voter is logged in
function is_logged_in() {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'voter';
}

// Check if an admin is logged in
function is_admin() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Require voter login
function require_login() {
    if (!is_logged_in()) {
        header('Location: ../login.php');
        exit();
    }
}

// Require admin login
function require_admin() {
    if (!is_admin()) {
        header('Location: ../login.php');
        exit();
    }
}
?>

