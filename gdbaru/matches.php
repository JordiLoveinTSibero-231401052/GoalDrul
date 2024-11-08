<?php
include "service/apikey.php";
$fixtures_url = 'https://v3.football.api-sports.io/fixtures';
include "service/database.php";

$league_id = isset($_GET['league_id']) ? (int)$_GET['league_id'] : 39;

$league_names = [
    39 => 'Premier League',
    140 => 'La Liga',
    61 => 'Bundesliga',
    135 => 'Serie A',
    78 => 'Ligue 1',
    2 => 'UEFA Champions Leauge',
    3 => 'UEFA Europe League'
];
$league_name = $league_names[$league_id] ?? 'Unknown League';

$params = [
    'league' => $league_id,
    'next' => 50, 
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $fixtures_url . '?' . http_build_query($params));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['x-apisports-key: ' . $api_key]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    echo 'Error fetching data.';
    exit;
}

$data = json_decode($response, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Matches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="newcss/leaguestyle.css">
    <link rel="stylesheet" href="newcss/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
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
    <h1>Upcoming <?php echo $league_name; ?> Matches</h1>

    <div id="matches-container">
        <?php
        $current_day = '';
        
        if (isset($data['response']) && !empty($data['response'])) {
            foreach ($data['response'] as $match) {
                $home_team = $match['teams']['home']['name'];
                $away_team = $match['teams']['away']['name'];
                $home_score = $match['goals']['home'];
                $away_score = $match['goals']['away'];
                $home_logo = $match['teams']['home']['logo'];
                $away_logo = $match['teams']['away']['logo'];
                $match_status = $match['fixture']['status']['long'];
                $match_date = $match['fixture']['date'];
                $formatted_date = date('H:i', strtotime($match_date));
                $match_day = date('l, d M Y', strtotime($match_date));
                $home_team_id = $match['teams']['home']['id'];
                $away_team_id = $match['teams']['away']['id'];

                if ($current_day !== $match_day) {
                    $current_day = $match_day;

                    echo "<hr style='border-top: 2px solid #000;'>";
                    echo "<h4 class='mt-4 mb-4 text-center'>$current_day</h4>";
                }

                echo "<div class='row mb-3 match-info align-items-center' id='match-{$match['fixture']['id']}'>";
                echo "    <div class='col-md-5 d-flex justify-content-end align-items-center'>";
                echo "        <img src='$home_logo' alt='$home_team Logo' class='img-fluid' style='width: 30px; height: 30px; margin-right: 10px;'>";
                echo "        <a href='team_info.php?team_id=$home_team_id'>$home_team</a>";
                echo "    </div>";

                echo "    <div class='col-md-2 text-center' id='score-{$match['fixture']['id']}'>";
                echo "        <h3>$home_score - $away_score</h3>";
                echo "    </div>";

                echo "    <div class='col-md-5 d-flex justify-content-start align-items-center'>";
                echo "        <a href='team_info.php?team_id=$away_team_id'>$away_team</a>";
                echo "        <img src='$away_logo' alt='$away_team Logo' class='img-fluid' style='width: 30px; height: 30px; margin-left: 10px;'>";
                echo "    </div>";
                echo "    <p>$formatted_date</p>"; 

                echo "</div>";

                echo "<div class='match-details text-center'>";
                echo "    <p>Status: $match_status</p>"; 
                echo "</div>";
            }
        } else {
            echo "<p>There are no upcoming or live matches.</p>";
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
