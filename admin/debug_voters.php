<?php
require_once '../includes/db.php';
$stmt = $pdo->query("SELECT * FROM votes");
echo '<pre>';
print_r($stmt->fetchAll());
echo '</pre>';
?>
