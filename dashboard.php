<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'includes/db.php';

// Fetch logged-in user info
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch active polls
$polls = $pdo->query("SELECT * FROM polls WHERE active = 1 AND archived = 0")->fetchAll();

// Handle poll selection and candidate listing
$selectedPollId = isset($_GET['poll_id']) ? intval($_GET['poll_id']) : null;
$selectedPoll = null;
$candidates = [];
$hasVoted = false;
$userVote = null;

if ($selectedPollId) {
    $stmt3 = $pdo->prepare("SELECT * FROM polls WHERE id = ?");
    $stmt3->execute([$selectedPollId]);
    $selectedPoll = $stmt3->fetch();

    $stmt4 = $pdo->prepare("SELECT * FROM candidates WHERE poll_id = ? AND status = 'Active'");
    $stmt4->execute([$selectedPollId]);
    $candidates = $stmt4->fetchAll(PDO::FETCH_ASSOC);

    // Did this user already vote? If so, add details
    $stmt5 = $pdo->prepare("SELECT v.candidate_id, c.name AS candidate_name
                            FROM votes v LEFT JOIN candidates c ON v.candidate_id=c.id 
                            WHERE v.user_id = ? AND v.poll_id = ?");
    $stmt5->execute([$user_id, $selectedPollId]);
    $voteData = $stmt5->fetch();
    $hasVoted = !!$voteData;
    $userVote = $voteData['candidate_name'] ?? null;
}

// Handle vote POST
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['poll_id'], $_POST['candidate_id']) &&
    is_numeric($_POST['poll_id']) && is_numeric($_POST['candidate_id'])
) {
    $pid = intval($_POST['poll_id']);
    $cid = intval($_POST['candidate_id']);
    // Double-check user hasn't already voted
    $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM votes WHERE user_id = ? AND poll_id = ?");
    $stmtCheck->execute([$user_id, $pid]);
    if ($stmtCheck->fetchColumn() == 0) {
        $stmtVote = $pdo->prepare("INSERT INTO votes (user_id, poll_id, candidate_id, voted_at, created_at)
                                   VALUES (?, ?, ?, NOW(), NOW())");
        $stmtVote->execute([$user_id, $pid, $cid]);
    }
    header("Location: dashboard.php?poll_id=$pid&voted=1");
    exit;
}

// Show results logic
$showResults = false;
if ($selectedPoll && isset($_GET['show_results'])) {
    if ($selectedPoll['end_date'] < date('Y-m-d H:i:s')) {
        $showResults = true;
    } else {
        $resultsMsgEarly = "Results will be visible after the poll ends.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard | AmiVote</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap & DataTables CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <!-- Icon font & Google Fonts: Montserrat + Roboto -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        html, body {
            height: 100%; min-height: 100%;
        }
        body {
            /* Sweet overlay glassmorphism effect */
            min-height: 100vh;
            /* background: rgb(109,13,30); */
            background: url('https://www.amity.edu/gurugram/images/university.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            font-family: 'Roboto', 'Segoe UI', Arial, sans-serif;
            color: #232323;
        }
        .backdrop {
            background: rgba(255,255,255,0.82);
            min-height: 100vh;
            min-width: 100vw;
            position: absolute;
            top: 0; left: 0;
            z-index: 0;
        }
        .glass {
            background: rgba(255,255,255, 0.78);
            border-radius: 1.6rem;
            box-shadow: 0 8px 40px rgba(109,13,30,0.17);
            backdrop-filter: blur(4px);
            padding: 2.1rem 1.6rem;
            margin: 2.8rem auto 2.8rem auto;
            max-width: 900px;
            z-index: 2;
            position: relative;
        }
        .navbar {
            background: rgba(255,255,255,0.96)!important;
            box-shadow: 0 4px 24px rgba(109,13,30,0.09);
        }
        .dashboard-title {
            font-family: 'Montserrat', sans-serif;
            text-shadow: 1px 1px 18px #fff7, 0 2px 4px #ae151569;
            font-weight: 700;
            color: #6d0d1e;
        }
        .btn-maroon {
            background: #6d0d1e;
            color: #fff;
            border: none;
            font-weight: 600; font-family: 'Montserrat', sans-serif;
            letter-spacing: 0.5px;
        }
        .btn-maroon:hover, .btn-outline-maroon:active {
            background: #8a1c2b !important; color: #fff !important;
        }
        .btn-outline-maroon {
            border: 1.5px solid #6d0d1e;
            color: #6d0d1e;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
        }
        .btn-outline-maroon:hover {
            color: #fff;
            background: #6d0d1e;
        }
        .candidate-img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5px solid #f6c23e77;
            box-shadow: 0 2px 12px rgba(109,13,30,0.10);
        }
        .table thead {
            background: #f3e6e9;
            color: #6d0d1e;
        }
        .status-badge {
            background: #43aa8b !important;
            color: #fff;
            font-size: .98em;
            border-radius: 1.3rem;
            padding: .38em 1.15em;
            letter-spacing: .32px;
            font-weight: 600;
        }
        .glass .alert {
            border-radius: .95rem;
            font-size: 1.03rem;
        }
        .voters-total {
            font-family: 'Montserrat', sans-serif;
            font-weight: bold; color: #e23c3c;
            letter-spacing: 1px;
        }
        @media (max-width:900px) { .glass { max-width: 100vw; } }
        @media (max-width:576px) {
            .glass { padding: 0.75rem 0.2rem; margin: 1.42rem 0.25rem; }
            .dashboard-title { font-size: 1.35rem; }
            .table th, .table td { font-size: .97rem; }
        }
    </style>
</head>
<body>
    <div class="backdrop"></div>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top shadow">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold dashboard-title" href="dashboard.php">
                <i class="fa-solid fa-graduation-cap me-2 text-maroon"></i>
                AmiVote Student Portal
            </a>
            <span class="ms-auto fw-semibold">
                <i class="fa fa-user-circle me-1"></i>
                <?php echo htmlspecialchars($user['first_name'] ?? $user['name']); ?>
            </span>
            <form method="post" action="logout.php" class="ms-3 mb-0">
                <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
            </form>
        </div>
    </nav>
    <div class="container">
        <div class="glass">
            <h3 class="mb-4 dashboard-title"><i class="fa fa-list-alt text-maroon me-2"></i>Active Polls</h3>
            <?php if (empty($polls)): ?>
                <div class="alert alert-warning">No active polls right now. Please check back later!</div>
            <?php else: ?>
                <ul class="list-group mb-4 shadow-sm">
                    <?php foreach ($polls as $poll): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-white">
                            <div>
                                <b class="text-maroon"><?= htmlspecialchars($poll['title']) ?></b>
                                <span class="badge bg-secondary ms-2"><?= htmlspecialchars($poll['start_date']) ?> -
                                    <?= htmlspecialchars($poll['end_date']) ?></span>
                            </div>
                            <a href="dashboard.php?poll_id=<?= $poll['id'] ?>" class="btn btn-maroon btn-sm fw-semibold">
                                View Candidates
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ($selectedPoll): ?>
                <hr>
                <h4 class="mb-3 dashboard-title">
                    <?= htmlspecialchars($selectedPoll['title']) ?> - Candidates
                    <span class="fs-6 badge <?= (date('Y-m-d H:i:s') > $selectedPoll['end_date']) ? 'bg-success' : 'bg-warning text-dark' ?>">
                        <?= (date('Y-m-d H:i:s') > $selectedPoll['end_date']) ? 'Ended' : 'Ongoing' ?>
                    </span>
                </h4>
                <div class="text-muted mb-2">
                    <i class="fa fa-calendar"></i>
                    <?= htmlspecialchars($selectedPoll['start_date']) ?> to <?= htmlspecialchars($selectedPoll['end_date']) ?>
                </div>
                <?php if (!empty($selectedPoll['description'])): ?>
                    <div class="mb-1"><?= htmlspecialchars($selectedPoll['description']) ?></div>
                <?php endif; ?>
                <?php
                $now = date('Y-m-d H:i:s');
                $is_ongoing = $now >= $selectedPoll['start_date'] && $now <= $selectedPoll['end_date'];
                ?>
                <?php if (!$hasVoted && $is_ongoing): ?>
                    <form method="post" action="dashboard.php?poll_id=<?= $selectedPollId ?>">
                        <input type="hidden" name="poll_id" value="<?= $selectedPollId ?>">
                        <div class="table-responsive">
                            <table id="candidateTable" class="table table-striped table-hover mt-3">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Status</th>
                                        <th>Vote</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($candidates as $candidate): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($candidate['photo'])): ?>
                                                    <img src="assets/images/<?= htmlspecialchars($candidate['photo']) ?>"
                                                         class="candidate-img" alt="<?= htmlspecialchars($candidate['name']) ?>">
                                                <?php else: ?>
                                                    <span class="text-muted fst-italic">No photo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= htmlspecialchars($candidate['name']) ?></td>
                                            <td><?= htmlspecialchars($candidate['position']) ?></td>
                                            <td>
                                                <span class="badge status-badge"><?= htmlspecialchars($candidate['status']) ?></span>
                                            </td>
                                            <td>
                                                <input type="radio" name="candidate_id" value="<?= $candidate['id'] ?>" required>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-maroon mt-3 px-4">Submit Vote</button>
                    </form>
                <?php elseif ($hasVoted): ?>
                    <div class="alert alert-success mt-4">
                        <i class="fa fa-check-circle"></i>
                        You have already voted in this poll
                        <?php if ($userVote): ?>
                            <div class="fs-6 mt-1">Your vote: <b><?= htmlspecialchars($userVote) ?></b></div>
                        <?php endif; ?>
                    </div>
                <?php elseif (!$is_ongoing): ?>
                    <div class="alert alert-info mt-3">Voting is not open for this poll.</div>
                <?php endif; ?>

                <a href="dashboard.php?poll_id=<?= $selectedPollId ?>&show_results=1" class="btn btn-outline-maroon mt-2">
                    <i class="fa fa-bar-chart"></i> View Results
                </a>

                <?php
                // Show Results section
                if (isset($_GET['show_results'])) {
                    if ($showResults) {
                        // Fetch all candidates for this poll
                        $stmt = $pdo->prepare("SELECT * FROM candidates WHERE poll_id = ?");
                        $stmt->execute([$selectedPollId]);
                        $pollCandidates = $stmt->fetchAll();

                        // Get total votes for all candidates in poll
                        $cidList = array_column($pollCandidates, 'id');
                        $totalVotes = 0; $votesPerCandidate = [];
                        if ($cidList) {
                            $in = str_repeat('?,', count($cidList) - 1) . '?';
                            $stmt2 = $pdo->prepare("SELECT candidate_id, COUNT(*) AS votes FROM votes WHERE candidate_id IN ($in) GROUP BY candidate_id");
                            $stmt2->execute($cidList);
                            foreach ($stmt2->fetchAll() as $row) {
                                $votesPerCandidate[$row['candidate_id']] = $row['votes'];
                                $totalVotes += $row['votes'];
                            }
                        }
                        $maxVotes = (!empty($votesPerCandidate)) ? max($votesPerCandidate) : 0;
                        ?>
                        <hr>
                        <h4 class="mb-3 dashboard-title"><?= htmlspecialchars($selectedPoll['title']) ?> - Final Results</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Position</th>
                                        <th>Votes</th>
                                        <th>Percentage</th>
                                        <th>Winner</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($pollCandidates as $cand):
                                    $votes = $votesPerCandidate[$cand['id']] ?? 0;
                                    $percent = $totalVotes ? round(($votes / $totalVotes) * 100, 2) : 0;
                                    $isWinner = $votes == $maxVotes && $votes > 0;
                                    ?>
                                    <tr<?= $isWinner ? ' style="background: #f6c23e33; font-weight: bold;"' : '' ?>>
                                        <td>
                                            <?php if ($cand['photo']): ?>
                                                <img src="assets/images/<?= htmlspecialchars($cand['photo']) ?>" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($cand['name']) ?></td>
                                        <td><?= htmlspecialchars($cand['position']) ?></td>
                                        <td><?= $votes ?></td>
                                        <td><?= $percent ?>%</td>
                                        <td>
                                            <?php if ($isWinner): ?>
                                                <span class="badge bg-success"><i class="fa fa-trophy"></i> Winner</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (empty($pollCandidates)): ?>
                                    <tr><td colspan="6" class="text-muted text-center">No candidates for this poll.</td></tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($totalVotes == 0): ?>
                            <div class="alert alert-warning mt-2">No votes were cast in this poll.</div>
                        <?php endif; ?>
                    <?php } else {
                        echo '<div class="alert alert-info mt-3">'.htmlspecialchars($resultsMsgEarly).'</div>';
                    }
                }
                ?>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/70c4c2edb9.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#candidateTable').DataTable({
                dom: 'Bfrtip',
                buttons: ['pdf', 'csv', 'print', 'colvis'],
                pagingType: 'simple_numbers',
                pageLength: 10
            })
        })
    </script>
</body>
</html>
