<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: candidates.php");
    exit;
}
$candidate_id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM candidates WHERE id = ?");
$stmt->execute([$candidate_id]);
$candidate = $stmt->fetch();
if (!$candidate) {
    header("Location: candidates.php");
    exit;
}

// Fetch all polls for dropdown
$polls = $pdo->query("SELECT id, title FROM polls WHERE archived = 0")->fetchAll();

$errors = [];
$name = $candidate['name'];
$poll_id = $candidate['poll_id'];
$position = $candidate['position'];
$status = $candidate['status'];
$photo_filename = $candidate['photo'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $poll_id = $_POST['poll_id'] ?? '';
    $position = trim($_POST['position'] ?? '');
    $status = $_POST['status'] ?? 'Active';

    if ($name === '') $errors[] = "Name is required.";
    if ($poll_id === '' || !is_numeric($poll_id)) $errors[] = "Poll selection is required.";
    if ($position === '') $errors[] = "Position is required.";
    if (!in_array($status, ['Active', 'Inactive'])) $errors[] = "Invalid status.";

    // Handle photo upload
    if (!empty($_FILES['photo']['name'])) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $errors[] = "Photo must be an image (jpg, jpeg, png, gif).";
        } else {
            // Delete old photo if exists
            if ($photo_filename && file_exists("../assets/images/$photo_filename")) {
                unlink("../assets/images/$photo_filename");
            }
            $photo_filename = uniqid('cand_') . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], "../assets/images/$photo_filename");
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare("UPDATE candidates SET name=?, poll_id=?, position=?, status=?, photo=? WHERE id=?");
        $stmt->execute([$name, $poll_id, $position, $status, $photo_filename, $candidate_id]);
        header("Location: candidates.php?msg=updated");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Candidate - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-4">
    <h2>Edit Candidate</h2>
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
            <select name="poll_id" class="form-select" required>
                <option value="">-- Select Poll --</option>
                <?php foreach ($polls as $poll): ?>
                    <option value="<?= $poll['id'] ?>" <?= $poll_id == $poll['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($poll['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Position <span class="text-danger">*</span></label>
            <input type="text" name="position" class="form-control" value="<?= htmlspecialchars($position) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Photo</label>
            <?php if ($photo_filename): ?>
                <div class="mb-2">
                    <img src="../assets/images/<?= htmlspecialchars($photo_filename) ?>" alt="Current Photo" style="width:60px;height:60px;border-radius:50%;object-fit:cover;">
                </div>
            <?php endif; ?>
            <input type="file" name="photo" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="Active" <?= $status == 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= $status == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-maroon">Save Changes</button>
        <a href="candidates.php" class="btn btn-secondary ms-2">Cancel</a>
    </form>
</div>
</body>
</html>
