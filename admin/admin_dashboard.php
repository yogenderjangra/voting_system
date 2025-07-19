<?php
require_once '../includes/auth.php';
require_admin();
require_once '../includes/db.php';

// Quick stats
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_polls = $pdo->query("SELECT COUNT(*) FROM polls")->fetchColumn();
$total_candidates = $pdo->query("SELECT COUNT(*) FROM candidates")->fetchColumn();
$total_votes = $pdo->query("SELECT COUNT(*) FROM votes")->fetchColumn();

// Chart labels (last 6 months)
$months = [];
$votes_per_month = [];
$users_per_month = [];
for ($i = 5; $i >= 0; $i--) {
    $current_month_ts = strtotime("-$i months");
    $month_label = date('M', $current_month_ts);
    $year_label = date('Y', $current_month_ts);

    $months[] = $month_label;

    // Votes per month (real data, use voted_at!)
    $stmt_votes = $pdo->prepare("SELECT COUNT(*) FROM votes WHERE MONTH(voted_at) = ? AND YEAR(voted_at) = ?");
    $stmt_votes->execute([date('n', $current_month_ts), $year_label]);
    $votes_per_month[] = (int)$stmt_votes->fetchColumn();

    // Users per month (real data, if users.created_at exists)
    $stmt_users = $pdo->prepare("SELECT COUNT(*) FROM users WHERE MONTH(created_at) = ? AND YEAR(created_at) = ?");
    $stmt_users->execute([date('n', $current_month_ts), $year_label]);
    $users_per_month[] = (int)$stmt_users->fetchColumn();
}

// Polls status for doughnut chart
$active_polls = $pdo->query("SELECT COUNT(*) FROM polls WHERE active = 1")->fetchColumn();
$inactive_polls = $pdo->query("SELECT COUNT(*) FROM polls WHERE active = 0")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | AmiVote</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap, FontAwesome, Chart.js -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
            background: #f8f9fa;
            color: #222;
        }
        .bg-maroon { background: #6d0d1e !important; color: #fff !important; }
        .bg-blue { background: #3a86ff !important; color: #fff !important; }
        .bg-dark { background: #343a40 !important; color: #fff !important; }
        .bg-green { background: #43aa8b !important; color: #fff !important; }
        .bg-red { background: #e63946 !important; color: #fff !important; }
        .bg-gold { background: #f6c23e !important; color: #fff !important; }
        .stat-card {
            border-radius: 1.2rem;
            box-shadow: 0 2px 16px rgba(0,0,0,0.08);
            padding: 1.5rem 1rem;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
            cursor: pointer;
            animation: fadeInUp .7s;
        }
        .stat-card:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 6px 32px rgba(109,13,30,0.10);
        }
        .stat-icon {
            font-size: 2.4rem;
            margin-bottom: .7rem;
            opacity: 0.9;
        }
        .stat-title {
            color: #fff;
            font-size: 1.1rem;
            margin-bottom: .2rem;
        }
        .stat-value {
            font-size: 2.1rem;
            font-weight: bold;
            color: #fff;
        }
        .quick-link-card {
            border-radius: 1.2rem;
            background: #fff;
            box-shadow: 0 2px 16px rgba(109,13,30,0.06);
            text-align: center;
            padding: 1.5rem 1rem;
            transition: box-shadow .2s, transform .2s;
            text-decoration: none;
            color: #6d0d1e;
            font-weight: 500;
            margin-bottom: 1rem;
            animation: fadeInUp .8s;
        }
        .quick-link-card:hover {
            box-shadow: 0 6px 32px rgba(109,13,30,0.10);
            transform: translateY(-2px) scale(1.03);
            color: #8a1c2b;
            text-decoration: none;
        }
        .quick-link-card i {
            font-size: 2rem;
            margin-bottom: .7rem;
            display: block;
        }
        .sidebar {
            background: #fff;
            border-radius: 1.5rem;
            min-height: 80vh;
            box-shadow: 0 6px 32px rgba(109,13,30,0.08);
            padding: 1.5rem 1rem;
        }
        .sidebar a {
            color: #6d0d1e;
            font-weight: 500;
            display: block;
            margin-bottom: 1.2rem;
            text-decoration: none;
            transition: color .2s;
        }
        .sidebar a.active, .sidebar a:hover {
            color: #8a1c2b;
        }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(40px);}
            100% { opacity: 1; transform: translateY(0);}
        }
        @media (max-width: 991px) {
            .sidebar { min-height: unset; margin-bottom: 2rem; }
        }
        @media (max-width: 767px) {
            .gradient-header { padding: 1.5rem 1rem 1rem 1rem; }
        }
    </style>
</head>
<body>
<!-- Gradient Header -->
<div class="bg-maroon mb-4 py-4 px-4 rounded-bottom-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <h2 class="fw-bold mb-0 text-white">Dashboard</h2>
        <form class="d-flex mt-3 mt-md-0" role="search">
            <input class="form-control search-bar" type="search" placeholder="Search..." aria-label="Search">
        </form>
    </div>
    <div class="mt-3">
        <span class="fw-semibold text-white">Welcome, <?= htmlspecialchars($_SESSION['admin_username'] ?? 'Admin') ?>!</span>
    </div>
</div>
<div class="container-fluid">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-2 col-md-3">
            <div class="sidebar">
                <a href="admin_dashboard.php" class="active"><i class="fa-solid fa-gauge-high me-2"></i>Dashboard</a>
                <a href="manage_polls.php"><i class="fa-solid fa-poll-h me-2"></i>Polls</a>
                <a href="candidates.php"><i class="fa-solid fa-user-tie me-2"></i>Candidates</a>
                <a href="manage_voters.php"><i class="fa-solid fa-users me-2"></i>Voters</a>
                <a href="results.php"><i class="fa-solid fa-chart-bar me-2"></i>Results</a>
                <a href="../logout.php"><i class="fa-solid fa-sign-out-alt me-2"></i>Logout</a>
            </div>
        </div>
        <!-- Main Content -->
        <div class="col-lg-10 col-md-9">
            <div class="row g-4 mb-4">
                <div class="col-md-3 col-6">
                    <div class="stat-card bg-blue">
                        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                        <div class="stat-title">Total Voters</div>
                        <div class="stat-value"><?= $total_users ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card bg-dark">
                        <div class="stat-icon"><i class="fa-solid fa-poll-h"></i></div>
                        <div class="stat-title">Total Polls</div>
                        <div class="stat-value"><?= $total_polls ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card bg-green">
                        <div class="stat-icon"><i class="fa-solid fa-user-tie"></i></div>
                        <div class="stat-title">Candidates</div>
                        <div class="stat-value"><?= $total_candidates ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-card bg-red">
                        <div class="stat-icon"><i class="fa-solid fa-vote-yea"></i></div>
                        <div class="stat-title">Total Votes</div>
                        <div class="stat-value"><?= $total_votes ?></div>
                    </div>
                </div>
            </div>
            <!-- Chart and Quick Links -->
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm p-4 mb-4" style="border-radius: 1.2rem;">
                        <h5 class="fw-bold mb-3 text-maroon"><i class="fa-solid fa-chart-bar me-2"></i>Monthly Votes</h5>
                        <canvas id="votesChart" height="100"></canvas>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card shadow-sm p-4 mb-4" style="border-radius: 1.2rem;">
                        <h5 class="fw-bold mb-3 text-maroon"><i class="fa-solid fa-chart-line me-2"></i>Voters Growth</h5>
                        <canvas id="usersChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card shadow-sm p-4 mb-4" style="border-radius: 1.2rem;">
                        <h5 class="fw-bold mb-3 text-maroon"><i class="fa-solid fa-chart-pie me-2"></i>Polls Status</h5>
                        <canvas id="pollsChart" height="180"></canvas>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <a href="manage_polls.php" class="quick-link-card d-block">
                                <i class="fa-solid fa-poll-h text-gold"></i>
                                Manage Polls
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="candidates.php" class="quick-link-card d-block">
                                <i class="fa-solid fa-user-tie text-green"></i>
                                Manage Candidates
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="manage_voters.php" class="quick-link-card d-block">
                                <i class="fa-solid fa-users text-blue"></i>
                                View Voters
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="results.php" class="quick-link-card d-block">
                                <i class="fa-solid fa-chart-bar text-maroon"></i>
                                View Results
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Chart and Quick Links -->
        </div>
    </div>
</div>
<script>
const votesColors = [
    '#3a86ff', '#6d0d1e', '#f6c23e', '#8a1c2b', '#43aa8b', '#e63946'
];
const ctx = document.getElementById('votesChart').getContext('2d');
const votesChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            label: 'Votes',
            data: <?= json_encode($votes_per_month) ?>,
            backgroundColor: votesColors,
            borderRadius: 8,
            barPercentage: 0.6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, grid: { color: '#f3e6e9' } }
        },
        animation: { duration: 1200, easing: 'easeOutBounce' }
    }
});

// Voters Growth Line Chart
const ctx2 = document.getElementById('usersChart').getContext('2d');
const usersChart = new Chart(ctx2, {
    type: 'line',
    data: {
        labels: <?= json_encode($months) ?>,
        datasets: [{
            label: 'New Voters',
            data: <?= json_encode($users_per_month) ?>,
            backgroundColor: 'rgba(61, 90, 254, 0.15)',
            borderColor: '#3a86ff',
            pointBackgroundColor: '#6d0d1e',
            pointBorderColor: '#fff',
            borderWidth: 3,
            tension: 0.4,
            fill: true
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

// Polls Status Doughnut
const ctx3 = document.getElementById('pollsChart').getContext('2d');
const pollsChart = new Chart(ctx3, {
    type: 'doughnut',
    data: {
        labels: ['Active', 'Inactive'],
        datasets: [{
            data: [<?= $active_polls ?>, <?= $inactive_polls ?>],
            backgroundColor: ['#43aa8b', '#e63946'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true, position: 'bottom' }
        },
        cutout: '70%'
    }
});
</script>
</body>
</html>
