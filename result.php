<?php
require_once 'includes/db.php';
$poll_id = $_GET['poll_id'] ?? 1;
$stmt = $pdo->prepare("SELECT candidates.name, COUNT(votes.id) as vote_count
    FROM candidates
    LEFT JOIN votes ON candidates.id = votes.candidate_id AND votes.poll_id = ?
    WHERE candidates.poll_id = ?
    GROUP BY candidates.id");
$stmt->execute([$poll_id, $poll_id]);
$results = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Results - Voting System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Election Results</h2>
        <canvas id="resultsChart" width="400" height="200"></canvas>
        <script>
            const data = {
                labels: [<?php foreach ($results as $r) { echo "'".$r['name']."',"; } ?>],
                datasets: [{
                    label: 'Votes',
                    data: [<?php foreach ($results as $r) { echo $r['vote_count'].","; } ?>],
                    backgroundColor: 'rgba(109, 13, 30, 0.7)'
                }]
            };
            const ctx = document.getElementById('resultsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: data
            });
        </script>
    </div>
</body>
</html>
