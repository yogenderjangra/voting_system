<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: manage_polls.php");
    exit;
}
$poll_id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM polls WHERE id = ?");
$stmt->execute([$poll_id]);
$poll = $stmt->fetch();

if (!$poll) {
    header("Location: manage_polls.php");
    exit;
}

$errors = [];
$title = $poll['title'];
$description = $poll['description'];
$start_date = $poll['start_date'];
$end_date = $poll['end_date'];
$active = $poll['active'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $active = isset($_POST['active']) ? 1 : 0;

    if ($title === '') $errors[] = "Title is required.";
    if ($start_date === '') $errors[] = "Start date is required.";
    if ($end_date === '') $errors[] = "End date is required.";
    if ($start_date && $end_date && $start_date > $end_date) $errors[] = "End date must be after start date.";

    if (!$errors) {
        $stmt = $pdo->prepare("UPDATE polls SET title=?, description=?, start_date=?, end_date=?, active=? WHERE id=?");
        $stmt->execute([$title, $description, $start_date, $end_date, $active, $poll_id]);
        header("Location: manage_polls.php?msg=updated");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Poll - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-4">
    <h2>Edit Poll</h2>
    <?php if ($errors): ?>
        <div class="alert alert-danger"><?= implode('<br>', $errors) ?></div>
    <?php endif; ?>
    <form method="post" class="card p-4 shadow-sm" style="max-width: 500px;">
        <div class="mb-3">
            <label class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"><?= htmlspecialchars($description) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Start Date <span class="text-danger">*</span></label>
            <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">End Date <span class="text-danger">*</span></label>
            <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>" required>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="active" id="active" <?= $active ? 'checked' : '' ?>>
            <label class="form-check-label" for="active">Active</label>
        </div>
        <button type="submit" class="btn btn-maroon">Save Changes</button>
        <a href="manage_polls.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
</body>
</html>
