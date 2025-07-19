<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';

// Get poll_id from URL, default to 1 if not set
$poll_id = isset($_GET['poll_id']) ? intval($_GET['poll_id']) : 1;

// Fetch poll title for display
$poll_stmt = $pdo->prepare("SELECT title FROM polls WHERE id = ?");
$poll_stmt->execute([$poll_id]);
$poll = $poll_stmt->fetch();

$stmt = $pdo->prepare("
    SELECT 
        u.first_name, u.middle_name, u.last_name, u.username,
        c.name AS candidate_name,
        COALESCE(v.voted_at, v.created_at) AS voted_time
    FROM votes v
    JOIN users u ON v.user_id = u.id
    JOIN candidates c ON v.candidate_id = c.id
    WHERE v.poll_id = ?
    ORDER BY voted_time DESC
");
$stmt->execute([$poll_id]);
$voters = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Voters for Poll <?= isset($poll['title']) ? ' - ' . htmlspecialchars($poll['title']) : '' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Voters for Poll <?= isset($poll['title']) ? ' - ' . htmlspecialchars($poll['title']) : $poll_id ?></h2>
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>Voter Name</th>
                <th>Username</th>
                <th>Candidate</th>
                <th>Voted At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($voters as $v): ?>
                <tr>
                    <td><?= htmlspecialchars(trim($v['first_name'].' '.$v['middle_name'].' '.$v['last_name'])) ?></td>
                    <td><?= htmlspecialchars($v['username']) ?></td>
                    <td><?= htmlspecialchars($v['candidate_name']) ?></td>
                    <td><?= htmlspecialchars($v['voted_time']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($voters)): ?>
                <tr><td colspan="4" class="text-center text-muted">No votes found for this poll.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
