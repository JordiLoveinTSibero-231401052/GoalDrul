<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: dashboard.php"); 
    exit();
}
include "service/database.php";
include "service/apikeyorg.php";

$fixtures_url = 'https://api.football-data.org/v4';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GD Leagues</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=<?php echo filemtime('css/style.css'); ?>">
    <link rel="stylesheet" href="css/teaminfo.css?v=<?php echo filemtime('css/teaminfo.css'); ?>">
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
                    <a class="nav-link" href="#" id="league"><i class="fas fa-calendar-alt"></i> League</a>
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

<h2 class="mb-3 mt-5 text-center">LEAGUES</h2>

<div class="leaguess">
    <h3 class="mb-3 mt-5 text-center">International</h3>
    <div class="leaguelist">
        <a href="matches.php?league_id=2001">
            <img src="assets/ucl.png" alt="UCL" class="img">
        </a>
    </div>
    
    <h3 class="mb-3 mt-5 text-center">Europe</h3>
    <div class="leaguelist">
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
    </div>
    
    <div class="leaguelist">
        <a href="matches.php?league_id=2003">
            <img src="assets/eredivisie.png" alt="Eredivisie" class="img">
        </a>
        <a href="matches.php?league_id=2016">
            <img src="assets/championship_england.png" alt="Championship" class="img">
        </a>
        <a href="matches.php?league_id=2017">
                <img src="assets/primeira_liga.png" alt="Primeira Liga" class="img">
        </a>
    </div>
    

    <h3 class="mb-3 mt-5 text-center">Americas</h3>
    <div class="leaguelist">
        <a href="matches.php?league_id=2013">
            <img src="assets/serie_a_brazil.png" alt="Serie A Brazil" class="img">
        </a>
    </div>
</div>  


    <footer class="text-center text-lg-start mt-5 pt-4">
        <div class="text-center p-3">
            <p>&copy; 2024 Goaldrul. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

</body>
</html>
