<?php
include "service/database.php";
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']),
    'use_strict_mode' => true,
]);

$login_message = "";

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header("location: login.php");
}
$_SESSION['last_activity'] = time();

if (isset($_SESSION["is_login"])) {
    header("location: dashboardlogin.php");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }

    // Validasi input
    $username_or_email = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    if ($_SESSION['login_attempts'] >= 100) {
        $login_message = "Account temporarily locked due to too many login attempts..";
    } else {
        if (filter_var($username_or_email, FILTER_VALIDATE_EMAIL)) {
            $stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
        } else {
            $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
        }

        $stmt->bind_param("s", $username_or_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            if (password_verify($password, $data['password'])) {
                $_SESSION["username"] = $data["username"];
                $_SESSION["user_id"] = $data["id"];
                $_SESSION["is_login"] = true;

                $log_stmt = $mysqli->prepare("INSERT INTO login_logs (username, ip_address, status) VALUES (?, ?, ?)");
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $status = 'success';
                $log_stmt->bind_param("sss", $username_or_email, $ip_address, $status);
                $log_stmt->execute();

                session_regenerate_id(true);
                header("location: dashboardlogin.php");
                exit;
            } else {
                $login_message = "Wrong Password!";
                $_SESSION['login_attempts'] += 1;
            }
        } else {
            $login_message = "Account not found.";
            $_SESSION['login_attempts'] += 1;
        }
        
        $log_stmt = $mysqli->prepare("INSERT INTO login_logs (username, ip_address, status) VALUES (?, ?, ?)");
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $status = 'failure';
        $log_stmt->bind_param("sss", $username_or_email, $ip_address, $status);
        $log_stmt->execute();

        $stmt->close();
    }
    $mysqli->close();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GD LOGIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/login.css?v=<?php echo filemtime('css/login.css'); ?>">
    <style>
        body{
            background-image: url(assets/bbb.jpg);
        }
    </style>
</head>
<body>
    <?php if ($login_message != "") { ?>
        <div class="alert alert-danger text-center">
            <?= $login_message ?>
        </div>
    <?php } ?>

    <section>
        <div class="container mt-5 pt-5">
            <div class="row">
                <div class="col-12 col-sm-8 col-md-6 m-auto">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="mb-3 pt-4">GOALDRUL</h3>
                            <form action="" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="text" class="form-control my-4 py-2" placeholder="Username or Email" name="username" required />
                                <input type="password" class="form-control my-4 py-2" placeholder="Password" name="password" required />
                                <div class="text-center">
                                    <button class="btn btn-primary" type="submit" name="login">Login</button>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="register.php" class="nav-link">Don't have an account?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="footer">
        <p>&copy; 2024 Goaldrul Football Website | All Rights Reserved</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
