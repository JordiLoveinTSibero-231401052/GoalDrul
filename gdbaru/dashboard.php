<?php

$api_key = '00855a3a8bef4ce1fab54f4eeba3c07f';
$fixtures_url = 'https://v3.football.api-sports.io/fixtures';
$league_ids = [39, 140, 61, 135, 78]; // Premier League, La Liga, Bundesliga, Serie A, Ligue 1

include "service/database.php";

$ch = curl_init();

$today = date('Y-m-d'); // Get today's date in 'YYYY-MM-DD' format

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
            $matches_found = false; // Flag to track if matches are found

            foreach ($data['response'] as $match) {
                $match_date = $match['fixture']['date'];
                $formatted_date = date('d M Y, H:i', strtotime($match_date));
                $match_day = date('Y-m-d', strtotime($match_date));

                // Only display matches for today
                if ($match_day === $today) {
                    $home_team = $match['teams']['home']['name'];
                    $away_team = $match['teams']['away']['name'];
                    $home_score = $match['goals']['home'] ?? '-';
                    $away_score = $match['goals']['away'] ?? '-';
                    $match_status = $match['fixture']['status']['long'];
                    $home_team_id = $match['teams']['home']['id'];
                    $away_team_id = $match['teams']['away']['id'];

                    // Display each match as a card
                   // Inside the fetch_fixtures function, modify the card output like this:
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
    <link rel="stylesheet" href="css/style.css">
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

    <nav class="navbar bg-body-tertiary">
    <div class="leaguelinknav">
        <div class="ms-auto">
            <a href="Lpremierleague.php">
                <img src="assets/premierleague.png" alt="Premier League" class="mx-2 league-logo">
            </a>
            <a href="Llaliga.php">
                <img src="assets/laliga24.png" alt="La Liga" class="mx-2 league-logo">
            </a>
            <a href="Lligue1.php">
                <img src="assets/ligue1.png" alt="Ligue 1" class="mx-2 league-logo">
            </a>
            <a href="Lbundesliga.php">
                <img src="assets/bundesliga.png" alt="Bundesliga" class="mx-2 league-logo">
            </a>
            <a href="LserieA.php">
                <img src="assets/serie_a.png" alt="Serie A" class="mx-2 league-logo">
            </a>
        </div>
    </div>
</nav>


    <div class="container mt-5">
        <h1 class="text-center">Today's Matches</h1>

        <div id="matchCarousel" class="carousel slide mt-3" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://via.placeholder.com/800x400?text=Match+Highlight+1" class="d-block w-100" alt="Match Highlight 1">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Exciting Match Coming Up!</h5>
                    <p>Premier League Next Game</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/800x400?text=Match+Highlight+1" class="d-block w-100" alt="Match Highlight 1">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Exciting Match Coming Up!</h5>
                    <p>La Liga Next Game</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/800x400?text=Match+Highlight+1" class="d-block w-100" alt="Match Highlight 1">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Exciting Match Coming Up!</h5>
                    <p>Serie A Next Game</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/800x400?text=Match+Highlight+2" class="d-block w-100" alt="Match Highlight 2">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Big Match Alert!</h5>
                    <p>Bundesliga Next Game</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/800x400?text=Match+Highlight+3" class="d-block w-100" alt="Match Highlight 3">
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

        <!-- Individual League Sections -->
        <?php foreach ($league_ids as $league_id): ?>
            <section class="mt-5">
                <h2 class="text-center section-title">
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
                    }
                    ?>
                </h2>
                <div class="d-flex flex-wrap justify-content-start gap-3 mt-3">
                    <?php echo fetch_fixtures($league_id, $api_key, $ch, $fixtures_url, $today); ?>
                </div>
            </section>
        <?php endforeach; ?>
    </div>

    <!-- Footer -->
    <footer class="text-center text-lg-start mt-5 pt-4">
        <div class="text-center p-3" style="background-color: #343a40;">
            <p>&copy; 2024 Goaldrul. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
curl_close($ch);
?>

</body>
</html>
