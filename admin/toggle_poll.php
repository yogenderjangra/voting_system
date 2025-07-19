<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $poll_id = intval($_GET['id']);
    // Get current status
    $stmt = $pdo->prepare("SELECT active FROM polls WHERE id = ?");
    $stmt->execute([$poll_id]);
    $poll = $stmt->fetch();
    if ($poll) {
        $new_status = $poll['active'] ? 0 : 1;
        $update = $pdo->prepare("UPDATE polls SET active = ? WHERE id = ?");
        $update->execute([$new_status, $poll_id]);
    }
}
header("Location: manage_polls.php");
exit;
