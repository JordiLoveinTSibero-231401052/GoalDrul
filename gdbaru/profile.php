<?php
include "service/database.php";
session_start();

if (isset($_POST['logout'])) {
    // Destroy the session and redirect to the login page
    session_destroy();
    header("Location: dashboard.php"); // Change this to the appropriate login page
    exit();
}
if (!isset($_SESSION["is_login"]) || !isset($_SESSION["user_id"])) {
    echo "Anda belum login atau session user_id tidak ditemukan.";
    exit;
}


// Mengambil data profile berdasarkan user_id
$user_id = $_SESSION["user_id"];
$stmt = $mysqli->prepare("SELECT * FROM profiles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// echo $data['bio'];
// Pastikan kode ini diletakkan sebelum form atau bagian yang membutuhkan variabel
if (isset($_POST['update_profile'])) {
    // Inisialisasi variabel dari form
    $bio = isset($_POST['bio']) ? $_POST['bio'] : '';  
    $birthday = isset($_POST['birthday']) ? $_POST['birthday'] : '';  
    $country = isset($_POST['country']) ? $_POST['country'] : '';  
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';  

    // Simpan ke database atau lakukan proses lainnya
}

// Update Profile
if (isset($_POST['update_info'])) {
    // Proses penyimpanan untuk tab Info
    $bio = $_POST['bio'];
    $birthday = $_POST['birthday'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];

    $stmt = $mysqli->prepare("UPDATE profiles SET bio = ?, birthday = ?, country = ?, phone = ? WHERE user_id = ?");
    $stmt->bind_param("ssssi", $bio, $birthday, $country, $phone, $user_id);
    if ($stmt->execute()) {
        echo "Info updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

if (isset($_POST['update_social_links'])) {
    // Proses penyimpanan untuk tab Social Links
    $twitter = $_POST['twitter'];
    $facebook = $_POST['facebook'];
    $google_plus = $_POST['google_plus'];
    $linkedin = $_POST['linkedin'];
    $instagram = $_POST['instagram'];

    $stmt = $mysqli->prepare("UPDATE profiles SET twitter = ?, facebook = ?, google_plus = ?, linkedin = ?, instagram = ? WHERE user_id = ?");
    $stmt->bind_param("sssssi", $twitter, $facebook, $google_plus, $linkedin, $instagram, $user_id);
    if ($stmt->execute()) {
        echo "Social links updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Update Password
if (isset($_POST['update_password'])) {
    $password_lama = $_POST['password_lama']; // Mengambil input password lama
    $password_baru = $_POST['password_baru']; // Mengambil input password baru
    $konfirmasi_password = $_POST['konfirmasi_password']; // Mengambil input konfirmasi password

    // Validasi input
    if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_password)) {
        echo "<script>alert('Semua kolom harus diisi!');</script>";
    } elseif ($password_baru !== $konfirmasi_password) {
        echo "<script>alert('Password baru dan konfirmasi password tidak cocok!');</script>";
    } else {
        // Query untuk mendapatkan password lama dari database
        $sql = "SELECT password FROM users WHERE username = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verifikasi apakah password lama sesuai
            if (hash("sha256", $password_lama) == $row['password']) {
                // Enkripsi password baru menggunakan hash
                $hashed_password_baru = hash("sha256", $password_baru);

                // Update password baru ke database
                $sql_update = "UPDATE users SET password = ? WHERE username = ?";
                $stmt_update = $mysqli->prepare($sql_update);
                $stmt_update->bind_param("ss", $hashed_password_baru, $username);
                if ($stmt_update->execute()) {
                    echo "<script>alert('Password berhasil diubah!');</script>";
                } else {
                    echo "<script>alert('Gagal mengubah password. Silakan coba lagi.');</script>";
                }
            } else {
                echo "<script>alert('Password lama salah.');</script>";
            }
        } else {
            echo "<script>alert('Pengguna tidak ditemukan.');</script>";
        }

        // Menutup statement dan koneksi
        $stmt->close();
        $mysqli->close();
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodingDung | Profile Template</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">

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

    <div class="container light-style flex-grow-1 container-p-y">
        <h4 class="font-weight-bold py-3 mb-4">
            Account settings
        </h4>
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-links">
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                            href="#account-general">General</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-change-password">Change password</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-info">Info</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-social-links">Social links</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-connections">Connections</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#account-notifications">Notifications</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="account-general">
                            <div class="card-body media align-items-center">
                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt
                                    class="d-block ui-w-80">
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control mb-1" value="nmaxwell">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" value="Nelle Maxwell">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">E-mail</label>
                                    <input type="text" class="form-control mb-1" value="nmaxwell@mail.com">
                                    <div class="alert alert-warning mt-3">
                                        Your email is not confirmed. Please check your inbox.<br>
                                        <a href="javascript:void(0)">Resend confirmation</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-change-password">
                            <div class="card-body pb-2">
                                <!-- Form untuk Update Password -->
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label class="form-label">Current password</label>
                                        <input type="password" class="form-control" id="password_lama" name="password_lama" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">New password</label>
                                        <input type="password" class="form-control" id="password_baru" name="password_baru" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Repeat new password</label>
                                        <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" required>
                                    </div>
                                    <div class="text-right mt-3">
                                        <input type="submit" name="update_password" value="Change Password">
                                    </div>
                                </form>

                            </div>
                        </div>
                            <div class="tab-pane fade" id="account-info">
                                <div class="card-body pb-2">
                                    <form action="" method="POST">
                                        <div class="form-group">
                                            <label class="form-label">Biografi</label>
                                            <textarea type="text" class="form-control" name="bio"><?php echo htmlspecialchars($data['bio']); ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Birthday</label>
                                            <input type="date" name="birthday" value="<?php echo $data['birthday']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Country</label>
                                            <input placeholder="Indonesia" type="text" class="form-control" name="country" value="<?php echo htmlspecialchars($data['country']); ?>">
                                        </div>
                                        <hr class="border-light m-0">
                                        <div class="card-body pb-2">
                                            <h6 class="mb-4" >Contacts</h6>
                                            <input placeholder="+123 456789" type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($data['phone']); ?>">
                                        </div>
                                        <div class="text-right mt-3">
                                            <input type="submit" name="update_" value="Save Changes">
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="account-social-links">
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label class="form-label">Twitter</label>
                                        <input type="url" class="form-control" name="twitter" value="<?php echo htmlspecialchars($data['twitter']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Facebook</label>
                                        <input type="url" class="form-control" name="facebook" value="<?php echo htmlspecialchars($data['facebook']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Google+</label>
                                        <input type="url" class="form-control" name="google_plus" value="<?php echo htmlspecialchars($data['google_plus']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">LinkedIn</label>
                                        <input type="url" class="form-control" name="linkedin" value="<?php echo htmlspecialchars($data['linkedin']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Instagram</label>
                                        <input type="url" class="form-control" name="instagram" value="<?php echo htmlspecialchars($data['instagram']); ?>">
                                    </div>
                                    <div class="text-right mt-3">
                                        <input type="submit" name="update_social_links" value="Save Changes">
                                    </div>
                                </form>
                            </div>

                        <div class="tab-pane fade" id="account-connections">
                            <div class="card-body">
                                <button type="button" class="btn btn-twitter">Connect to
                                    <strong>Twitter</strong></button>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <h5 class="mb-2">
                                    <a href="javascript:void(0)" class="float-right text-muted text-tiny"><i
                                            class="ion ion-md-close"></i> Remove</a>
                                    <i class="ion ion-logo-google text-google"></i>
                                    You are connected to Google:
                                </h5>
                                <a href="/cdn-cgi/l/email-protection" class="__cf_email__"
                                    data-cfemail="f9979498818e9c9595b994989095d79a9694">[email&#160;protected]</a>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <button type="button" class="btn btn-facebook">Connect to
                                    <strong>Facebook</strong></button>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body">
                                <button type="button" class="btn btn-instagram">Connect to
                                    <strong>Instagram</strong></button>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="account-notifications">
                            <div class="card-body pb-2">
                                <h6 class="mb-4">Activity</h6>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input" checked>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Email me when someone comments on my article</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input" checked>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Email me when someone answers on my forum
                                            thread</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input">
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Email me when someone follows me</span>
                                    </label>
                                </div>
                            </div>
                            <hr class="border-light m-0">
                            <div class="card-body pb-2">
                                <h6 class="mb-4">Application</h6>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input" checked>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">News and announcements</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input">
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Weekly product updates</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher-input" checked>
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes"></span>
                                            <span class="switcher-no"></span>
                                        </span>
                                        <span class="switcher-label">Weekly blog digest</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
 
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


</body>
</html>
