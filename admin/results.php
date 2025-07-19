<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';

if (isset($_GET['poll_id'])) {
    $polls = $pdo->prepare("SELECT * FROM polls WHERE id = ?");
    $polls->execute([$_GET['poll_id']]);
    $polls = $polls->fetchAll();
} else {
    $polls = $pdo->query("SELECT * FROM polls")->fetchAll();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Poll Results - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .bg-maroon { background: #6d0d1e !important; color: #fff !important; }
        .card { border-radius: 1rem; }
        .card-header { font-size: 1.2rem; }
        .table thead { background: #f3e6e9; color: #6d0d1e; }
        .badge-status { font-size: 1rem; padding: 0.5em 1em; }
        .winner-row { background: #f6c23e33; font-weight: 600; }
        .countdown { font-weight: bold; color: #e63946; }
        @media (max-width: 600px) {
            .card { padding: 0.5rem; }
            .table th, .table td { font-size: 0.95rem; }
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="container mt-4">
    <a href="admin_dashboard.php#results" class="btn btn-maroon mb-3"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
    <h2>Poll Results</h2>
    <?php foreach ($polls as $poll): ?>
        <?php
            $now = date('Y-m-d H:i:s');
            $is_ended = ($now > $poll['end_date']);
            $is_ongoing = ($now >= $poll['start_date'] && $now <= $poll['end_date']);
            $status_badge = $is_ended
                ? '<span class="badge bg-success badge-status"><i class="fa fa-check-circle"></i> Ended</span>'
                : ($is_ongoing
                    ? '<span class="badge bg-warning text-dark badge-status"><i class="fa fa-hourglass-half"></i> Ongoing</span>'
                    : '<span class="badge bg-secondary badge-status"><i class="fa fa-clock"></i> Not Started</span>');
        ?>
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-maroon d-flex justify-content-between align-items-center">
                <span><?= htmlspecialchars($poll['title']) ?></span>
                <?= $status_badge ?>
            </div>
            <div class="card-body">
                <div class="mb-2 text-muted">
                    <i class="fa fa-calendar"></i>
                    <?= htmlspecialchars($poll['start_date']) ?> to <?= htmlspecialchars($poll['end_date']) ?>
                </div>
                <?php if ($is_ongoing): ?>
                    <div>
                        <i class="fa fa-clock"></i>
                        <span class="countdown" id="countdown-<?= $poll['id'] ?>"></span>
                    </div>
                    <script>
                        function countdown<?= $poll['id'] ?>() {
                            const end = new Date("<?= $poll['end_date'] ?>").getTime();
                            const now = new Date().getTime();
                            let distance = end - now;
                            if (distance < 0) {
                                document.getElementById("countdown-<?= $poll['id'] ?>").innerHTML = "Poll ended";
                                return;
                            }
                            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            document.getElementById("countdown-<?= $poll['id'] ?>").innerHTML =
                                days + "d " + hours + "h " + minutes + "m " + seconds + "s left";
                        }
                        countdown<?= $poll['id'] ?>();
                        setInterval(countdown<?= $poll['id'] ?>, 1000);
                    </script>
                <?php endif; ?>
                <?php
                // Get all candidates for this poll
                $candidates = $pdo->prepare("SELECT * FROM candidates WHERE poll_id = ?");
                $candidates->execute([$poll['id']]);
                $candidates = $candidates->fetchAll();

                // Get total votes for all candidates in this poll
                $candidate_ids = array_column($candidates, 'id');
                if ($candidate_ids) {
                    $in = str_repeat('?,', count($candidate_ids) - 1) . '?';
                    $total_votes_stmt = $pdo->prepare("SELECT COUNT(*) FROM votes WHERE candidate_id IN ($in)");
                    $total_votes_stmt->execute($candidate_ids);
                    $total_votes = $total_votes_stmt->fetchColumn();
                } else {
                    $total_votes = 0;
                }

                // Find the winner(s)
                $votes_per_candidate = [];
                foreach ($candidates as $cand) {
                    $cand_votes = $pdo->prepare("SELECT COUNT(*) FROM votes WHERE candidate_id = ?");
                    $cand_votes->execute([$cand['id']]);
                    $votes_per_candidate[$cand['id']] = (int)$cand_votes->fetchColumn();
                }
                $max_votes = $votes_per_candidate ? max($votes_per_candidate) : 0;
                ?>
                <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Votes</th>
                            <th>Percentage</th>
                            <?php if ($is_ended): ?>
                                <th>Winner</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $labels = [];
                        $data = [];
                        foreach ($candidates as $cand):
                            $cand_votes = $votes_per_candidate[$cand['id']];
                            $percent = $total_votes ? round(($cand_votes / $total_votes) * 100, 2) : 0;
                            $labels[] = $cand['name'];
                            $data[] = $cand_votes;
                            $is_winner = $is_ended && ($cand_votes === $max_votes && $max_votes > 0);
                        ?>
                        <tr<?= $is_winner ? ' class="winner-row"' : '' ?>>
                            <td>
                                <?php if ($cand['photo']): ?>
                                    <img src="../assets/images/<?= htmlspecialchars($cand['photo']) ?>" style="width:32px;height:32px;border-radius:50%;object-fit:cover;margin-right:8px;">
                                <?php endif; ?>
                                <?= htmlspecialchars($cand['name']) ?>
                            </td>
                            <td><?= $cand_votes ?></td>
                            <td><?= $percent ?>%</td>
                            <?php if ($is_ended): ?>
                                <td>
                                    <?php if ($is_winner): ?>
                                        <span class="badge bg-gold text-dark"><i class="fa fa-trophy"></i> Winner</span>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($candidates)): ?>
                            <tr><td colspan="<?= $is_ended ? 4 : 3 ?>" class="text-center text-muted">No candidates found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
                <canvas id="chart-<?= $poll['id'] ?>" height="60"></canvas>
                <script>
                const ctx<?= $poll['id'] ?> = document.getElementById('chart-<?= $poll['id'] ?>').getContext('2d');
                new Chart(ctx<?= $poll['id'] ?>, {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode($labels) ?>,
                        datasets: [{
                            label: 'Votes',
                            data: <?= json_encode($data) ?>,
                            backgroundColor: [
                                '#3a86ff', '#6d0d1e', '#f6c23e', '#8a1c2b', '#43aa8b', '#e63946'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false } },
                            y: { beginAtZero: true, grid: { color: '#f3e6e9' } }
                        }
                    }
                });
                </script>
                <?php if (!$is_ended): ?>
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="fa fa-info-circle"></i>
                        Voting is ongoing. Results will be officially announced after <b><?= htmlspecialchars($poll['end_date']) ?></b>.
                    </div>
                <?php elseif ($max_votes == 0): ?>
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fa fa-exclamation-circle"></i>
                        No votes were cast in this poll.
                    </div>
                <?php endif; ?>
            </div>
            <!-- Voters List -->
            <div class="card-footer bg-light">
                <h6 class="mb-2"><i class="fa fa-users"></i> Voters</h6>
                <div class="table-responsive">
                <table class="table table-bordered table-sm align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Voted For</th>
                            <th>Voted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch all votes for this poll
                        $votes_stmt = $pdo->prepare("
                            SELECT v.*, u.first_name, u.middle_name, u.last_name, u.username, c.name AS candidate_name,
                                   COALESCE(v.voted_at, v.created_at) AS voted_time
                            FROM votes v
                            JOIN users u ON v.user_id = u.id
                            JOIN candidates c ON v.candidate_id = c.id
                            WHERE v.poll_id = ?
                            ORDER BY voted_time DESC
                        ");
                        $votes_stmt->execute([$poll['id']]);
                        $voters = $votes_stmt->fetchAll();
                        foreach ($voters as $v):
                        ?>
                        <tr>
                            <td><?= htmlspecialchars(trim($v['first_name'].' '.$v['middle_name'].' '.$v['last_name'])) ?></td>
                            <td><?= htmlspecialchars($v['username']) ?></td>
                            <td><?= htmlspecialchars($v['candidate_name']) ?></td>
                            <td><?= htmlspecialchars($v['voted_time']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($voters)): ?>
                            <tr><td colspan="4" class="text-center text-muted">No votes yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
