<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}
header('Location: manage_voters.php');
exit();
?>
