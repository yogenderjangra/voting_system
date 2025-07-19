<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Remove photo file if exists
    $stmt = $pdo->prepare("SELECT photo FROM candidates WHERE id=?");
    $stmt->execute([$id]);
    $cand = $stmt->fetch();
    if ($cand && $cand['photo'] && file_exists("../assets/images/" . $cand['photo'])) {
        unlink("../assets/images/" . $cand['photo']);
    }
    // Delete candidate
    $del = $pdo->prepare("DELETE FROM candidates WHERE id=?");
    $del->execute([$id]);
}
header("Location: candidates.php?msg=deleted");
exit;
