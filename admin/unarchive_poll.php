<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $poll_id = intval($_GET['id']);
    $update = $pdo->prepare("UPDATE polls SET archived = 0 WHERE id = ?");
    $update->execute([$poll_id]);
}
header("Location: manage_polls.php");
exit;
