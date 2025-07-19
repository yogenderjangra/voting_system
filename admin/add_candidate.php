<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';

// Fetch all polls for dropdown
$polls = $pdo->query("SELECT id, title FROM polls WHERE archived = 0")->fetchAll();

$errors = [];
$name = '';
$position = '';
$status = 'Active';

// Check if poll_id is in URL (from "Add Candidates" button), lock that poll
$locked_poll_id = isset($_GET['poll_id']) ? intval($_GET['poll_id']) : '';
$poll_id = $locked_poll_id;

// Form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $poll_id = isset($_POST['poll_id']) ? intval($_POST['poll_id']) : $locked_poll_id;
    $position = trim($_POST['position'] ?? '');
    $status = $_POST['status'] ?? 'Active';

    // Validation
    if ($name === '') $errors[] = "Name is required.";
    if ($poll_id === 0) $errors[] = "Poll selection is required.";
    if ($position === '') $errors[] = "Position is required.";
    if (!in_array($status, ['Active', 'Inactive'])) $errors[] = "Invalid status.";

    // Handle photo upload
   if (!empty($_FILES['photo']['name'])) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $allowed)) {
        $photo_filename = uniqid('cand_') . '.' . $ext;
        // Save to assets/images/
        move_uploaded_file($_FILES['photo']['tmp_name'], '../assets/images/' . $photo_filename);
    }
}


    if (!$errors) {
        $stmt = $pdo->prepare("INSERT INTO candidates (name, poll_id, position, status, photo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $poll_id, $position, $status, $photo_filename]);
        header("Location: candidates.php?msg=added");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Candidate - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-maroon { background: #6d0d1e; color: #fff; border: none; }
        .btn-maroon:hover { background: #510a16; color: #fff; }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-4">
    <h2>Add Candidate</h2>
    <?php if ($errors): ?>
        <div class="alert alert-danger"><?= implode('<br>', $errors) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm" style="max-width: 500px;">
        <div class="mb-3">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Poll <span class="text-danger">*</span></label>
            <?php if ($locked_poll_id): ?>
                <?php
                foreach ($polls as $poll) {
                    if ($poll['id'] == $locked_poll_id) {
                        echo '<input type="text" class="form-control" value="'.htmlspecialchars($poll['title']).'" readonly>';
                        echo '<input type="hidden" name="poll_id" value="'.$locked_poll_id.'">';
                        break;
                    }
                }
                ?>
            <?php else: ?>
                <select name="poll_id" class="form-select" required>
                    <option value="">-- Select Poll --</option>
                    <?php foreach ($polls as $poll): ?>
                        <option value="<?= $poll['id'] ?>" <?= $poll_id == $poll['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($poll['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Position <span class="text-danger">*</span></label>
            <input type="text" name="position" class="form-control" value="<?= htmlspecialchars($position) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Photo</label>
            <input type="file" name="photo" class="form-control">
            <div class="form-text text-muted">Supported types: jpg, jpeg, png, gif</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="Active" <?= $status == 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= $status == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-maroon">Add Candidate</button>
        <a href="candidates.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
</body>
</html>
