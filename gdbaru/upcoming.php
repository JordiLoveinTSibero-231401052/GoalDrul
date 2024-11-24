<?php

include "service/apikey.php";
$fixtures_url = 'https://v3.football.api-sports.io/fixtures';

$league_ids = [39, 140, 61, 135, 78, 2, 3, 274, 30];
$all_matches = [];

foreach ($league_ids as $league_id) {
    $params = [
        'league' => $league_id,
        'next' => 50 
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fixtures_url . '?' . http_build_query($params));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['x-apisports-key: ' . $api_key]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if ($response === false) {
        echo 'Error: ' . curl_error($ch);
        continue;
    }

    $data = json_decode($response, true);
    if (!empty($data['response'])) {
        $all_matches = array_merge($all_matches, $data['response']);
    }

    curl_close($ch);
}

$today = strtotime(date('Y-m-d'));
$end_date = strtotime('+7 days', $today);

$next_week_matches = array_filter($all_matches, function ($match) use ($today, $end_date) {
    $match_date = strtotime($match['fixture']['date']);
    return $match_date >= $today && $match_date <= $end_date;
});

usort($next_week_matches, function ($a, $b) {
    return strtotime($a['fixture']['date']) - strtotime($b['fixture']['date']);
});

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Matches - Next 7 Days</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>">
    <link rel="stylesheet" href="teaminfo.css?v=<?php echo filemtime('teaminfo.css'); ?>">

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
                  
                </ul>
            </div>
        </div>
    </nav>

<nav class="navbar bg-body-tertiary">
    <div class="bottom_nav">
        <ul>
          <a href="matches.php?league_id=39">
              <img src="assets/premierleague.png" alt="Premier League" class="img">
          </a>
          <a href="matches.php?league_id=140">
              <img src="assets/laliga24.png" alt="La Liga" class="img">
          </a>
          <a href="matches.php?league_id=78">
              <img src="assets/ligue1.png" alt="Ligue 1" class="img">
          </a>
          <a href="matches.php?league_id=61">
              <img src="assets/bundesliga.png" alt="Bundesliga" class="img">
          </a>
          <a href="matches.php?league_id=135">
              <img src="assets/serie_a.png" alt="Serie A" class="img">
            </a>
            <a href="matches.php?league_id=2">
              <img src="assets/ucl.png" alt="Serie A" class="img">
            </a>
          <a href="matches.php?league_id=3">
              <img src="assets/uel.png" alt="Serie A" class="img">
            </a>
          <a href="matches.php?league_id=274">
              <img src="assets/briliga1.png" alt="Serie A" class="img">
            </a>
          <a href="matches.php?league_id=30">
              <img src="assets/afcwc.png" alt="Serie A" class="img">
            </a>
        </ul>
    </div>
</nav>

<div class="container mt-4">
<h2 class="text-center mt-5 mb-2">Upcoming Matches Next 7 Days</h2>

    <?php
    if (!empty($next_week_matches)) {
        $current_day = '';

        echo "<ul class='list-group'>";

        foreach ($next_week_matches as $match) {
            $home_team = $match['teams']['home']['name'];
            $away_team = $match['teams']['away']['name'];
            $home_score = $match['goals']['home'];
            $away_score = $match['goals']['away'];
            $home_logo = $match['teams']['home']['logo'];
            $away_logo = $match['teams']['away']['logo'];
            $match_league = $match['league']['name'];
            $match_status = $match['fixture']['status']['long'];
            $match_date = $match['fixture']['date'];
            date_default_timezone_set('Asia/Bangkok');
            $formatted_date = date('H:i', strtotime($match_date));
            $match_day = date('l, d M Y', strtotime($match_date));
            $home_team_id = $match['teams']['home']['id'];
            $away_team_id = $match['teams']['away']['id'];

            if ($current_day !== $match_day) {
                $current_day = $match_day;
                echo "<h4 class='text-center mt-5 mb-2'><strong>$current_day</strong></h4>";
            }

            // Menampilkan pertandingan dalam list
            echo "<li class='list-group-item match-item'>";
            echo "    <div class='d-flex align-items-center match-container'>";
            echo "        <div class='d-flex align-items-center team-info' style='flex: 1;'>";
            echo "            <img src='$home_logo' alt='$home_team Logo' class='img-fluid me-2' style='width: 50px; height: 50px;'>";
            echo "            <a href='team_info.php?team_id=$home_team_id' class='text-truncate home-team-name' style='flex-grow: 1;'>$home_team</a>";
            echo "        </div>";
            
            // Show "VS" if score is not available or match has not started
            if ($home_score === null || $away_score === null) {
                echo "        <div class='score-container d-flex align-items-center justify-content-center' style='flex: 0 0 60px;'>";
                echo "            <small>$formatted_date</small>"; // Display "VS" when no scores
                echo "        </div>";
            } else {
                echo "        <div class='score-container d-flex align-items-center justify-content-center' style='flex: 0 0 60px;'>";
                echo "            <strong>$home_score - $away_score</strong>"; // Display the actual score
                echo "        </div>";
            }
            
            echo "        <div class='d-flex align-items-center team-info justify-content-end' style='flex: 1;'>";
            echo "            <a href='team_info.php?team_id=$away_team_id' class='text-truncate away-team-name me-2' style='text-align: right; flex-grow: 1;'>$away_team</a>";
            echo "            <img src='$away_logo' alt='$away_team Logo' class='img-fluid' style='width: 50px; height: 50px;'>";
            echo "        </div>";
            echo "    </div>";
            
            echo "    <div class='d-flex justify-content-center text-muted match-details mt-2'>";
            echo "        <div class='text-center' style='flex: 1;'>";
            echo "            <small>$match_league</small><br>";
            echo "        </div>";
            echo "    </div>";
            echo "</li>";
            
            
        }
        echo "</ul>";
    } else {
        echo "<p class='mt-5 text-center'>There are no upcoming matches in the next 7 days.</p>";
    }
    ?>
</div>

    <footer class="text-center text-lg-start mt-5 pt-4">
        <div class="text-center p-3">
            <p>&copy; 2024 Goaldrul. All rights reserved.</p>
        </div>
    </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
