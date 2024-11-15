<?php

include "service/apikey.php";
$fixtures_url = 'https://v3.football.api-sports.io/fixtures';

$league_ids = [39, 140, 61, 135, 78];
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
                            <li>
                                <form action="" method="POST" class="d-inline">
                                    <button type="submit" name="logout" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-star"></i> Favorite Team</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="upcoming.php"><i class="fas fa-calendar-alt"></i> Upcoming Matches</a>
                    </li>
                  
                </ul>
            </div>
        </div>
    </nav>

<div class="container mt-4">
    <h1>Upcoming Matches - Next 7 Days</h1>

    <?php
    if (!empty($next_week_matches)) {
        $current_day = '';

        foreach ($next_week_matches as $match) {
            $home_team = $match['teams']['home']['name'];
            $away_team = $match['teams']['away']['name'];
            $home_score = $match['goals']['home'];
            $away_score = $match['goals']['away'];
            $home_logo = $match['teams']['home']['logo'];
            $away_logo = $match['teams']['away']['logo'];
            $match_status = $match['fixture']['status']['long'];
            $match_date = $match['fixture']['date'];
            $formatted_date = date('d M Y, H:i', strtotime($match_date));
            $match_day = date('l, d M Y', strtotime($match_date));
            $home_team_id = $match['teams']['home']['id'];
            $away_team_id = $match['teams']['away']['id'];

            if ($current_day !== $match_day) {
                $current_day = $match_day;
                echo "<hr style='border-top: 2px solid #000;'>";
                echo "<h4 class='mt-4 mb-4 text-center'>$current_day</h4>";
            }

            echo "<div class='row mb-3 match-info align-items-center'>";
            echo "    <div class='col-md-5 d-flex justify-content-end align-items-center'>";
            echo "        <img src='$home_logo' alt='$home_team Logo' class='img-fluid' style='width: 30px; height: 30px; margin-right: 10px;'>";
            echo "        <a href='team_info.php?team_id=$home_team_id'>$home_team</a>";
            echo "    </div>";

            echo "    <div class='col-md-2 text-center'>";
            echo "        <h3>$home_score - $away_score</h3>";
            echo "    </div>";

            echo "    <div class='col-md-5 d-flex justify-content-start align-items-center'>";
            echo "        <a href='team_info.php?team_id=$away_team_id'>$away_team</a>";
            echo "        <img src='$away_logo' alt='$away_team Logo' class='img-fluid' style='width: 30px; height: 30px; margin-left: 10px;'>";
            echo "    </div>";
            echo "</div>";

            echo "<div class='match-details text-center'>";
            echo "    <p>Status: $match_status</p>";
            echo "    <p>Date: $formatted_date</p>";
            echo "</div>";
        }
    } else {
        echo "<p>There are no upcoming matches in the next 7 days.</p>";
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
