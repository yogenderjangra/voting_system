<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';

$voters = $pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Voters List | AmiVote</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .bg-maroon { background: #6d0d1e !important; color: #fff !important; }
        .btn-maroon { background: #6d0d1e; color: #fff; border: none; }
        .btn-maroon:hover { background: #510a16; }
        .user-photo { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .table thead { background: #f3e6e9; color: #6d0d1e; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Voters List</h2>
        <a href="admin_dashboard.php" class="btn btn-maroon">Back to Dashboard</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Department</th>
                    <th>Voted</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($voters as $v): ?>
                    <tr>
                        <td>
                            <?php if ($v['photo']): ?>
                                <img src="../assets/images/<?= htmlspecialchars($v['photo']) ?>" class="user-photo" alt="Photo">
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($v['first_name'] . ' ' . $v['last_name']) ?></td>
                        <td><?= htmlspecialchars($v['username']) ?></td>
                        <td><?= htmlspecialchars($v['department']) ?></td>
                        <td>
                            <span class="badge <?= $v['has_voted'] ? 'bg-success' : 'bg-secondary' ?>">
                                <?= $v['has_voted'] ? 'Yes' : 'No' ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($voters)): ?>
                    <tr><td colspan="5" class="text-center text-muted">No voters found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
