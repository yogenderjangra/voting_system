<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';
$candidates = $pdo->query("SELECT c.*, p.title as poll_title FROM candidates c JOIN polls p ON c.poll_id=p.id")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Candidates - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .bg-maroon { background: #6d0d1e !important; color: #fff !important; }
        .btn-maroon { background: #6d0d1e; color: #fff; border: none; }
        .btn-maroon:hover { background: #510a16; color: #fff; }
        .table thead { background: #f3e6e9; color: #6d0d1e; }
        .candidate-photo { border-radius: 50%; object-fit: cover; width: 40px; height: 40px; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manage Candidates</h2>
        <a href="add_candidate.php" class="btn btn-maroon">+ Add Candidate</a>
    </div>
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>Photo</th>
                <th>Name</th>
                <th>Poll</th>
                <th>Position</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($candidates as $c): ?>
                <tr>
                    <td>
                        <?php if ($c['photo']): ?>
                            <img src="../assets/images/<?= htmlspecialchars($c['photo']) ?>" class="candidate-photo">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($c['name']) ?></td>
                    <td><?= htmlspecialchars($c['poll_title']) ?></td>
                    <td><?= htmlspecialchars($c['position']) ?></td>
                    <td>
                        <span class="badge <?= $c['status']=='Active' ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $c['status'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="edit_candidate.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <a href="delete_candidate.php?id=<?= $c['id'] ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Are you sure you want to delete this candidate?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($candidates)): ?>
                <tr><td colspan="6" class="text-center text-muted">No candidates found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
