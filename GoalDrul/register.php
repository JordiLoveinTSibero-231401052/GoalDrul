<?php

include "service/database.php";

session_start([
    'cookie_httponly' => true,
    'cookie_secure' => isset($_SERVER['HTTPS']),
    'use_strict_mode' => true,
]);

$register_message = "";

if (isset($_SESSION["is_login"])) {
    header("location: dashboardlogin.php");
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["register"])) {
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }

    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $username = htmlspecialchars(trim($_POST["username"]));
    $password = htmlspecialchars(trim($_POST["password"]));
    $confirm_password = isset($_POST["confirm_password"]) ? htmlspecialchars(trim($_POST["confirm_password"])) : '';

    if (!$email || empty($username) || empty($password) || empty($confirm_password)) {
        $register_message = "Make sure all fields are filled out correctly.";
    } elseif ($password !== $confirm_password) {
        $register_message = "Password and confirm password do not match.";
    } else {
        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $mysqli->prepare("INSERT INTO users (email, username, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $email, $username, $hash_password);

            if ($stmt->execute()) {
                $register_message = "Account registered successfully. Please login.";
                
                $log_stmt = $mysqli->prepare("INSERT INTO register_logs ( email, username, ip_address) VALUES (?, ?, ?)");
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $log_stmt->bind_param("sss", $email, $username, $ip_address);
                $log_stmt->execute();
            } else {
                $register_message = "Account registration failed. Please try again.";
            }

            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            echo "Terjadi kesalahan: " . $e->getMessage();
        }
    }
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GD REGISTER</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/register.css?v=<?php echo filemtime('css/register.css'); ?>">
    <style>
        body{
            background-image: url(assets/bbb.jpg);
        }
    </style>
</head>
<body>
    <?php if ($register_message != "") { ?>
        <div class="alert alert-danger text-center">
            <?= $register_message ?>
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
                                <input type="email" class="form-control my-4 py-2" placeholder="example@gmail.com" name="email" required />
                                <input type="text" class="form-control my-4 py-2" placeholder="Username" name="username" required />
                                <input type="password" class="form-control my-4 py-2" placeholder="Password" name="password" required />
                                <input type="password" class="form-control my-4 py-2" placeholder="Confirm Password" name="confirm_password" required />
                                <div class="text-center">
                                    <button class="btn btn-primary" type="submit" name="register">Register</button>
                                </div>
                                <div class="text-center mt-3">
                                    <a href="login.php" class="nav-link">Already have an account?</a>
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
