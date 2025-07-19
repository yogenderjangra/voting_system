<nav class="navbar navbar-expand-lg navbar-dark bg-maroon shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="admin_dashboard.php">AmiVote Admin</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF'])=='admin_dashboard.php'?' active':'' ?>" href="admin_dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF'])=='manage_voters.php'?' active':'' ?>" href="manage_voters.php">Voters</a></li>
            <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF'])=='manage_polls.php'?' active':'' ?>" href="manage_polls.php">Polls</a></li>
            <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF'])=='candidates.php'?' active':'' ?>" href="candidates.php">Candidates</a></li>
            <li class="nav-item"><a class="nav-link<?= basename($_SERVER['PHP_SELF'])=='results.php'?' active':'' ?>" href="results.php">Results</a></li>
            <li class="nav-item"><a class="nav-link text-danger" href="../logout.php">Logout</a></li>
        </ul>
    </div>
</nav>
