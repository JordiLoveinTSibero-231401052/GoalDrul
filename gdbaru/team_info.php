<?php
include "service/apikey.php";
$team_id = $_GET['team_id'];

$team_url = 'https://v3.football.api-sports.io/teams';
$params = ['id' => $team_id];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $team_url . '?' . http_build_query($params));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['x-apisports-key: ' . $api_key]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$team_response = curl_exec($ch);

if ($team_response === false) {
    $error_message = 'Error: ' . curl_error($ch);
} else {
    $team_data = json_decode($team_response, true);

    if (!empty($team_data['response'])) {
        $team = $team_data['response'][0];
        $team_logo = $team['team']['logo'];
        $team_name = $team['team']['name'];
        $team_country = $team['team']['country'];
        $team_founded = $team['team']['founded'];
        $team_venue = $team['venue']['name'];
        $team_capacity = $team['venue']['capacity'];


        $players_url = 'https://v3.football.api-sports.io/players/squads';
        $players_params = ['team' => $team_id];

        curl_setopt($ch, CURLOPT_URL, $players_url . '?' . http_build_query($players_params));
        $players_response = curl_exec($ch);

        if ($players_response !== false) {
            $players_data = json_decode($players_response, true);
            $players_list = !empty($players_data['response']) ? $players_data['response'][0]['players'] : [];
        }


        $matches_url = 'https://v3.football.api-sports.io/fixtures';
        $matches_params = ['team' => $team_id, 'last' => 20];

        curl_setopt($ch, CURLOPT_URL, $matches_url . '?' . http_build_query($matches_params));
        $matches_response = curl_exec($ch);

        if ($matches_response !== false) {
            $matches_data = json_decode($matches_response, true);
            $recent_matches = !empty($matches_data['response']) ? $matches_data['response'] : [];
        }
    } else {
        $error_message = 'No information available for this team.';
    }
}

curl_close($ch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">
            <img src="assets/gd.png" class="img-fluid" alt="Logo Goaldrul"> 
                GOALDRUL 
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user"></i> Profile</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="register.php">Register</a></li>
                        <li><a class="dropdown-item" href="login.php">Login</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-star"></i> Favorite Team</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="fas fa-calendar-alt"></i> Upcoming Matches</a>
                </li>
               
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message; ?></div>
    <?php else: ?>
        <div class='text-center mb-4'>
            <img src='<?= $team_logo; ?>' alt='<?= $team_name; ?> Logo' class='img-fluid mb-2' style='width: 100px; height: 100px;'>
            <h1 class='display-4'><?= $team_name; ?></h1>
        </div>

        <h2> Team Information </h2>
        <div class='row'>
            <div class='col-md-6'>
                <p><strong>Country:</strong> <?= $team_country; ?></p>
                <p><strong>Founded:</strong> <?= $team_founded; ?></p>
                <p><strong>Venue:</strong> <?= $team_venue; ?></p>
                <p><strong>Capacity:</strong> <?= $team_capacity; ?></p>
            </div>
        </div>

        <?php if (!empty($players_list)): ?>
            <h2 class='mt-5'>Player List</h2>
            <ul class='list-group'>
                <?php foreach ($players_list as $player): ?>
                    <li class='list-group-item d-flex justify-content-between align-items-center'>
                        <?= $player['name']; ?>
                        <span class='badge bg-primary'><?= $player['position']; ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class='alert alert-warning'>No players found for this team.</div>
        <?php endif; ?>

        <?php if (!empty($recent_matches)): ?>
            <h2 class='mt-5'>Last 20 Matches</h2>
            <ul class='list-group'>
                <?php foreach ($recent_matches as $match): ?>
                    <li class='list-group-item'>
                    <div class='d-flex justify-content-between align-items-center'>

                        <div class='team-info d-flex align-items-center'>
                            <img src='<?= $match['teams']['home']['logo']; ?>' alt='<?= $match['teams']['home']['name']; ?> Logo' class='img-fluid me-2' style='width: 30px; height: 30px;'>
                            <a href='team_info.php?team_id=<?= $match['teams']['home']['id']; ?>'><?= $match['teams']['home']['name']; ?></a>
                        </div>

                        <div class='score'>
                            <?= $match['goals']['home']; ?> - <?= $match['goals']['away']; ?>
                        </div>

                        <div class='team-info d-flex align-items-center'>
                            <a href='team_info.php?team_id=<?= $match['teams']['away']['id']; ?>'><?= $match['teams']['away']['name']; ?></a>
                            <img src='<?= $match['teams']['away']['logo']; ?>' alt='<?= $match['teams']['away']['name']; ?> Logo' class='img-fluid ms-2' style='width: 30px; height: 30px;'>
                        </div>
                    </div>
                        <small class='text-muted'>Competition: <?= $match['league']['name']; ?></small><br>
                        <small class='text-muted'>Status: <?= $match['fixture']['status']['long']; ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class='alert alert-warning'>No recent matches found for this team.</div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
