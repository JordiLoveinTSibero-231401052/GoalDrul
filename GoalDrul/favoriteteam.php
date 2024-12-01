<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: dashboard.php"); 
    exit();
}

include "service/database.php";
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$success_message = $error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['favorite_id'])) {
    $favorite_id = intval($_POST['favorite_id']);

    $stmt = $mysqli->prepare("DELETE FROM favorites WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $favorite_id, $user_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $success_message = "Team removed from favorites.";
    } else {
        $error_message = "Failed to remove the team.";
    }
    $stmt->close();
}

$query = "SELECT id, team_id, team_name, team_logo FROM favorites WHERE user_id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$favorites = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorite Teams</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?= filemtime('css/style.css'); ?>">
    <link rel="stylesheet" href="css/teaminfo.css?v=<?= filemtime('css/teaminfo.css'); ?>">
    <link rel="stylesheet" href="background.css?v=<?php echo filemtime('background.css'); ?>">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboardlogin.php">
            <img src="assets/gd.png" class="img-fluid" alt="Logo Goaldrul"> GOALDRUL 
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user"></i> <?php echo htmlspecialchars($username); ?></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="profile.php" class="dropdown-item">Profile</a> 
                            <form action="" method="POST" class="d-inline">
                                <button type="submit" name="logout" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="favoriteteam.php"><i class="fas fa-star"></i> Favorite Team</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="upcoming.php"><i class="fas fa-calendar-alt"></i> Upcoming Matches</a>
                </li>
                <li class="nav-item">
                        <a class="nav-link" href="league.php" id="league"><i class="fas fa-calendar-alt"></i> League</a>
                </li> 
            </ul>
        </div>
    </div>
</nav>

<nav class="navbar bg-body-tertiary">
    <div class="bottom_nav">
        <ul>
            <a href="matches.php?league_id=2021">
                <img src="assets/premierleague.png" alt="Premier League" class="img">
            </a>
            <a href="matches.php?league_id=2014">
                <img src="assets/laliga24.png" alt="La Liga" class="img">
            </a>
            <a href="matches.php?league_id=2015">
                <img src="assets/ligue1.png" alt="Ligue 1" class="img">
            </a>
            <a href="matches.php?league_id=2002">
                <img src="assets/bundesliga.png" alt="Bundesliga" class="img">
            </a>
            <a href="matches.php?league_id=2019">
                <img src="assets/serie_a.png" alt="Serie A" class="img">
            </a>
            <a href="matches.php?league_id=2001">
                <img src="assets/ucl.png" alt="UCL" class="img">
            </a>
            <a href="matches.php?league_id=2016">
                <img src="assets/championship_england.png" alt="Championship" class="img">
            </a>
            <a href="matches.php?league_id=2017">
                <img src="assets/primeira_liga.png" alt="Primeira Liga" class="img">
            </a>
            <a href="matches.php?league_id=2013">
                <img src="assets/serie_a_brazil.png" alt="Serie A Brazil" class="img">
            </a>
            <a href="matches.php?league_id=2003">
                <img src="assets/eredivisie.png" alt="Eredivisie" class="img">
            </a>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <h1 class="text-center mb-4">Favorite Teams</h1>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message); ?></div>
    <?php elseif (!empty($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <?php if (count($favorites) > 0): ?>
        <ul class="list-group">
            <?php foreach ($favorites as $favorite): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="team_info.php?team_id=<?= htmlspecialchars($favorite['team_id']); ?>" class="text-decoration-none text-dark">
                        <img src="<?= htmlspecialchars($favorite['team_logo']); ?>" alt="<?= htmlspecialchars($favorite['team_name']); ?> Logo" style="width: 50px; margin-right: 10px;">
                        <?= htmlspecialchars($favorite['team_name']); ?>
                    </a>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="favorite_id" value="<?= htmlspecialchars($favorite['id']); ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-warning text-center">No favorite teams found.</div>
    <?php endif; ?>
</div>

<footer class="text-center text-lg-start mt-5 pt-4">
    <div class="text-center p-3">
        <p>&copy; 2024 Goaldrul. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
