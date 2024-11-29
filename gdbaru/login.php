<?php
include "service/database.php";
session_start();

$login_message = "";

if (isset($_SESSION["is_login"])) {
    header("location: dashboardlogin.php");
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hash_password = hash("sha256", $password);

    // Menggunakan prepared statement untuk keamanan
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $hash_password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $_SESSION["username"] = $data["username"];
        $_SESSION["user_id"] = $data["id"];  // Menyimpan id di session
        $_SESSION["is_login"] = true;
    
        header("location: dashboardlogin.php");
    } else {
        $login_message = "Akun tidak ditemukan";
    }
    
    $stmt->close();
    $mysqli->close();
}
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GOALDRUL LOGIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style2.css">
    <style>
        body {
            background-image: url('assets/bggd.jpg');
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
            background-attachment: fixed; 
            height: 100vh;
            margin: 0;
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
                            <h3 class="mb-3 pt-4 text-center">GOALDRUL</h3>
                            <form action="" method="POST">
                                <input type="text" class="form-control my-4 py-2" placeholder="username" name="username" required/>
                                <input type="password" class="form-control my-4 py-2" placeholder="password" name="password" required/>
                                <div class="text-center">
                                    <button class="btn btn-primary" type="submit" name="login">Login</button>
                                </div>
                                <div class="text-center mt-3">
                                <a href="register.php" class="nav-link mt-3">Don't have an account?</a>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
