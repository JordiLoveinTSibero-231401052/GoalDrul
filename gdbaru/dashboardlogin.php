<?php

session_start();

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: dashboard.php"); 
    exit();
}
include "service/apikey.php";

$fixtures_url = 'https://v3.football.api-sports.io/fixtures';
$league_ids = [39, 140, 61, 135, 78, 2, 3, 274, 30];
include "service/database.php";

$ch = curl_init();

$today = date('Y-m-d');

function fetch_fixtures($league_id, $api_key, $ch, $fixtures_url, $today) {
    $params = [
        'league' => $league_id,
        'next' => 30
    ];

    curl_setopt($ch, CURLOPT_URL, $fixtures_url . '?' . http_build_query($params));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'x-apisports-key: ' . $api_key
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    if ($response === false) {
        return '<div class="col-12"><p>Error: ' . curl_error($ch) . '</p></div>';
    } else {
        $data = json_decode($response, true);
        $output = '';

        if (!empty($data['response'])) {
            $matches_found = false;

            foreach ($data['response'] as $match) {
                $match_date = $match['fixture']['date'];
                $formatted_date = date('d M Y, H:i', strtotime($match_date));
                $match_day = date('Y-m-d', strtotime($match_date));

                if ($match_day === $today) {
                    $home_team = $match['teams']['home']['name'];
                    $away_team = $match['teams']['away']['name'];
                    $home_score = $match['goals']['home'] ?? '-';
                    $away_score = $match['goals']['away'] ?? '-';
                    $match_status = $match['fixture']['status']['long'];
                    $home_team_id = $match['teams']['home']['id'];
                    $away_team_id = $match['teams']['away']['id'];

                $output .= "<div class='col-md-4 mt-2 d-flex'>
                <div class='card shadow-sm flex-grow-1'>
                    <div class='card-body'>
                        <h5 class='card-title'><a href='team_info.php?team_id=$home_team_id'>$home_team</a> vs <a href='team_info.php?team_id=$away_team_id'>$away_team</a></h5>
                        <p class='match-date'>Date: $formatted_date</p>
                        <p>Status: $match_status</p>
                        <p>Score: $home_score - $away_score</p>
                        <a href='#' class='btn btn-primary'>View Details</a>
                    </div>
                </div>
                </div>";

                    $matches_found = true;
                }
            }

            if (!$matches_found) {
                $output .= '<div class="col-12"><p>No matches scheduled for today.</p></div>';
            }
        } else {
            $output .= '<div class="col-12"><p>No upcoming match data available.</p></div>';
        }

        return $output;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goaldrul Match Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
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


    <div class="container mt-5">
        <h1 class="text-center text-white">Today's Matches</h1>

        <div id="matchCarousel" class="carousel slide mt-3" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets/pl.webp" class="d-block w-100" alt="Match Highlight 1">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Exciting Match Coming Up!</h5>
                    <p>Premier League Next Game</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/alaves-1-1-mallorca-_-laliga-23-24-match-highlights_24022024_210052-486f2c.webp" class="d-block w-100" alt="Match Highlight 1">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Exciting Match Coming Up!</h5>
                    <p>La Liga Next Game</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/Venezia-vs-Udinese.jpg" class="d-block w-100" alt="Match Highlight 1">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Exciting Match Coming Up!</h5>
                    <p>Serie A Next Game</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/hq720 (2).jpg" class="d-block w-100" alt="Match Highlight 2">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Big Match Alert!</h5>
                    <p>Bundesliga Next Game</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="assets/image.png" class="d-block w-100" alt="Match Highlight 3">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Champions League Showdown!</h5>
                    <p>Ligue 1 Next Game</p>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#matchCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#matchCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

        <?php foreach ($league_ids as $league_id): ?>
            <section class="mt-5">
                <h2 class="section-title">
                    <?php
                    switch ($league_id) {
                        case 39:
                            echo 'Premier League';
                        break;
                        case 140:
                            echo 'La Liga';
                        break;
                        case 61:
                            echo 'Bundesliga';
                        break;
                        case 135:
                            echo 'Serie A';
                        break;
                        case 78:
                            echo 'Ligue 1';
                        break;
                        case 2:
                            echo 'UCL';
                        break;
                        case 3:
                            echo 'UEL';
                        break;
                        case 274:
                            echo 'Liga 1 Indonesia';
                        break;
                        case 30:
                            echo 'AFC WC Qualifier';
                            break;
                    }
                    ?>
                </h2>
            </section>
            <div class="d-flex text-center text-white">
                <?php echo fetch_fixtures($league_id, $api_key, $ch, $fixtures_url, $today); ?>
            </div>
        <?php endforeach; ?>
    </div>

    <footer class="text-center text-lg-start mt-5 pt-4">
        <div class="text-center p-3" style="background-color: black;">
            <p>&copy; 2024 Goaldrul. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
curl_close($ch);
?>

</body>
</html>
