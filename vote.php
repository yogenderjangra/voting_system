<?php
require_once 'includes/auth.php';
require_login();
require_once 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'];
$poll_id = $_GET['poll_id'] ?? 1;

// Fetch poll details
$stmt_p = $pdo->prepare("SELECT * FROM polls WHERE id = ?");
$stmt_p->execute([$poll_id]);
$poll = $stmt_p->fetch();

if (!$poll) {
    die('<div class="alert alert-danger m-4">Poll not found.</div>');
}

$now = date('Y-m-d H:i:s');
$is_ongoing = ($now >= $poll['start_date'] && $now <= $poll['end_date']);
$is_future = ($now < $poll['start_date']);
$is_ended = ($now > $poll['end_date']);

// Fetch candidates for this poll
$stmt2 = $pdo->prepare("SELECT * FROM candidates WHERE poll_id = ?");
$stmt2->execute([$poll_id]);
$candidates = $stmt2->fetchAll();

// Check if user already voted and fetch candidate if so
$stmt = $pdo->prepare("SELECT v.*, c.name AS candidate_name FROM votes v LEFT JOIN candidates c ON v.candidate_id = c.id WHERE v.user_id = ? AND v.poll_id = ?");
$stmt->execute([$user_id, $poll_id]);
$vote_row = $stmt->fetch();
$has_voted = $vote_row !== false;
$your_vote = $has_voted ? $vote_row['candidate_name'] : null;

// Handle vote submission
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$has_voted && $is_ongoing) {
    if (isset($_POST['candidate']) && is_numeric($_POST['candidate'])) {
        $candidate_id = intval($_POST['candidate']);
        $stmt = $pdo->prepare("INSERT INTO votes (user_id, poll_id, candidate_id, voted_at, created_at) VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute([$user_id, $poll_id, $candidate_id]);
        header('Location: vote.php?poll_id=' . $poll_id . '&voted=1');
        exit();
    } else {
        $error = "Please select a candidate.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Vote | <?= htmlspecialchars($poll['title']) ?> — Student Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap & Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Roboto', 'Montserrat', Arial, sans-serif;
        }
        .dashboard-card {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 6px 32px rgba(109,13,30,0.08);
            padding: 2.2rem 1.2rem;
            margin-top: 2.5rem;
            max-width: 900px;
            margin-left: auto; margin-right: auto;
        }
        .dashboard-card h4 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .bg-maroon {
            background: #6d0d1e !important; color: #fff !important;
        }
        .btn-maroon {
            background: #6d0d1e; color: #fff; border: 1px solid #6d0d1e;
            font-weight: bold; letter-spacing: 1px; font-size: 1rem;
        }
        .btn-maroon:hover { background: #8a1c2b; border-color: #8a1c2b; }
        .candidate-img {
            width: 46px; height: 46px; border-radius: 50%;
            object-fit: cover; border: 2px solid #f6c23e66;
            box-shadow: 0 2px 8px rgba(109,13,30,0.10);
        }
        .status-badge {
            background: #43aa8b !important;
            color: #fff; border-radius: 1rem; padding: 0.45em 1em;
            font-size: 0.92em; font-weight: 600;
            letter-spacing: 0.3px;
        }
        .radio-custom {
            accent-color: #6d0d1e;
            width: 1.2rem; height: 1.2rem;
        }
        .end-divider {
            border-top: 3px solid #f3e6e9;
            margin: 1.5em 0;
        }
        @media (max-width: 600px) {
            .dashboard-card { padding: 0.5rem; }
            .dashboard-card h4 { font-size: 1.1rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-card shadow-sm">
            <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom">
                <h4>
                    <i class="fa fa-poll me-2 text-maroon"></i>
                    <?= htmlspecialchars($poll['title']) ?>
                </h4>
                <span class="badge <?= $is_ended ? 'bg-success' : ($is_ongoing ? 'bg-warning text-dark' : 'bg-secondary') ?> px-3 py-2">
                    <?php if ($is_ended): ?>
                        <i class="fa fa-check-circle"></i> Ended
                    <?php elseif ($is_ongoing): ?>
                        <i class="fa fa-hourglass-half"></i> Ongoing
                    <?php else: ?>
                        <i class="fa fa-clock"></i> Not Started
                    <?php endif; ?>
                </span>
            </div>
            <div class="mb-3 text-muted">
                <i class="fa fa-calendar"></i>
                <?= htmlspecialchars($poll['start_date']) ?> to <?= htmlspecialchars($poll['end_date']) ?>
            </div>
            <?php if ($poll['description']): ?>
                <div class="mb-3"><i class="fa fa-info-circle"></i> <?= nl2br(htmlspecialchars($poll['description'])) ?></div>
            <?php endif; ?>
            <?php if ($is_ongoing): ?>
                <div class="mb-3">
                    <span class="fw-semibold">Time remaining: </span>
                    <span class="text-danger" id="countdown"></span>
                </div>
            <?php elseif ($is_future): ?>
                <div class="alert alert-info mb-3">This poll has not started yet.</div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Voting Form or Result Message -->
            <?php if ($has_voted || isset($_GET['voted'])): ?>
                <div class="alert alert-success">
                    <i class="fa fa-thumbs-up"></i>
                    Thank you! You have already voted in this poll.<br>
                    <?php if($your_vote): ?>
                    <span class="d-block mt-2">Your vote: <b><?= htmlspecialchars($your_vote) ?></b></span>
                    <?php endif;?>
                    <a href="results.php?poll_id=<?= $poll_id ?>" class="btn btn-outline-maroon btn-sm mt-3">
                        <i class="fa fa-chart-bar"></i> View Results
                    </a>
                </div>
            <?php elseif ($is_ongoing): ?>
                <form method="post">
                    <div class="table-responsive">
                    <table id="candidateTable" class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Photo</th>
                                <th scope="col">Name</th>
                                <th scope="col">Position</th>
                                <th scope="col">Status</th>
                                <th scope="col">Select</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($candidates as $candidate): ?>
                            <tr>
                                <td>
                                    <img src="assets/images/<?= htmlspecialchars($candidate['photo']) ?>" class="candidate-img"
                                        alt="<?= htmlspecialchars($candidate['name']) ?>">
                                </td>
                                <td class="fw-semibold"><?= htmlspecialchars($candidate['name']) ?></td>
                                <td><?= htmlspecialchars($candidate['position']) ?></td>
                                <td>
                                    <span class="badge status-badge">
                                        <?= htmlspecialchars($candidate['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <input type="radio" class="radio-custom" name="candidate" value="<?= $candidate['id'] ?>" required>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                    <button type="submit" class="btn btn-maroon px-4 mt-3">Cast Vote</button>
                </form>
            <?php elseif ($is_ended): ?>
                <div class="alert alert-info">
                    This poll has ended.&nbsp;
                    <a href="results.php?poll_id=<?= $poll_id ?>" class="btn btn-outline-primary btn-sm"><i class="fa fa-trophy"></i> See Results</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($is_ongoing): ?>
    <script>
    // Countdown timer JS
    function startCountdown(){
        const pollEnd = new Date("<?= $poll['end_date'] ?>").getTime();
        function updateCountdown() {
            const now = new Date().getTime();
            let distance = pollEnd - now;
            if (distance < 0) {
                document.getElementById("countdown").innerHTML = "Poll ended";
                return;
            }
            const days = Math.floor(distance / (1000*60*60*24));
            const hours = Math.floor((distance % (1000*60*60*24))/(1000*60*60));
            const minutes = Math.floor((distance % (1000*60*60))/ (1000*60));
            const seconds = Math.floor((distance % (1000*60))/ 1000);
            let text = '';
            if(days > 0) text += days + "d ";
            text += hours + "h " + minutes + "m " + seconds + "s left";
            document.getElementById("countdown").innerHTML = text;
        }
        updateCountdown();
        setInterval(updateCountdown,1000);
    }
    startCountdown();
    </script>
    <?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#candidateTable').DataTable({
                dom: 'Bfrtip',
                buttons: [ 'pdf', 'csv', 'print', 'colvis' ],
                pagingType: "simple_numbers",
                pageLength: 10,
                "bInfo": false,
                ordering:  false // no sorting needed for radio buttons
            });
        });
    </script>
</body>
</html>
