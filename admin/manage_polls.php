<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';

// Auto-archive polls whose end_date has passed
$pdo->prepare("UPDATE polls SET archived = 1 WHERE end_date < NOW() AND archived = 0")->execute();

// Fetch polls
$active_polls = $pdo->query("SELECT * FROM polls WHERE archived = 0")->fetchAll();
$archived_polls = $pdo->query("SELECT * FROM polls WHERE archived = 1")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Polls - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .bg-maroon { background: #6d0d1e !important; color: #fff !important; }
        .btn-maroon { background: #6d0d1e; color: #fff; border: none; }
        .btn-maroon:hover { background: #510a16; color: #fff; }
        .table thead { background: #f3e6e9; color: #6d0d1e; }
        .nav-tabs .nav-link.active { background: #6d0d1e; color: #fff; }
        .nav-tabs .nav-link { color: #6d0d1e; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manage Polls</h2>
        <a href="add_poll.php" class="btn btn-maroon">+ Add New Poll</a>
    </div>
    <ul class="nav nav-tabs mb-3" id="pollTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">Active Polls</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="archived-tab" data-bs-toggle="tab" data-bs-target="#archived" type="button" role="tab" aria-controls="archived" aria-selected="false">Archived Polls</button>
        </li>
    </ul>
    <div class="tab-content" id="pollTabsContent">
        <!-- Active Polls Tab -->
        <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date Range</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($active_polls as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['title']) ?></td>
                            <td><?= htmlspecialchars($p['description']) ?></td>
                            <td><?= htmlspecialchars($p['start_date']) ?> - <?= htmlspecialchars($p['end_date']) ?></td>
                            <td>
                                <span class="badge <?= $p['active'] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $p['active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <a href="add_candidate.php?poll_id=<?= $p['id'] ?>" class="btn btn-sm btn-success mb-1">
                                  <i class="fa fa-plus"></i> Add Candidates
                                </a>
                                <a href="edit_poll.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary mb-1">Edit</a>
                                <a href="toggle_poll.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning mb-1">
                                    <?= $p['active'] ? 'Deactivate' : 'Activate' ?>
                                </a>
                                <a href="archive_poll.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger mb-1" onclick="return confirm('Are you sure you want to archive this poll?');">Archive</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($active_polls)): ?>
                        <tr><td colspan="5" class="text-center text-muted">No active polls found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Archived Polls Tab -->
        <div class="tab-pane fade" id="archived" role="tabpanel" aria-labelledby="archived-tab">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Date Range</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($archived_polls as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['title']) ?></td>
                            <td><?= htmlspecialchars($p['description']) ?></td>
                            <td><?= htmlspecialchars($p['start_date']) ?> - <?= htmlspecialchars($p['end_date']) ?></td>
                            <td>
                                <span class="badge <?= $p['active'] ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= $p['active'] ? 'Active' : 'Inactive' ?>
                                </span>
                            </td>
                            <td>
                                <a href="edit_poll.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                <a href="toggle_poll.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-warning">
                                    <?= $p['active'] ? 'Deactivate' : 'Activate' ?>
                                </a>
                                <a href="unarchive_poll.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-success" onclick="return confirm('Unarchive this poll?');">Unarchive</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($archived_polls)): ?>
                        <tr><td colspan="5" class="text-center text-muted">No archived polls found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</body>
</html>
