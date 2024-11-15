<?php

include "service/apikey.php";
$team_id = $_GET['team_id'];

$team_url = 'https://v3.football.api-sports.io/teams';
$params = ['id' => $team_id];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $team_url . '?' . http_build_query($params));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['x-apisports-key: ' . $api_key]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$team_response = curl_exec($ch);
curl_close($ch);

$host = 'localhost'; 
$db = 'soccer';     
$user = 'root';    
$pass = '';

// Membuat koneksi ke database
$conn = new mysqli($host, $user, $pass, $db);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Menangani pengiriman form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $conn->real_escape_string($_POST['user_name']);
    $team_id = intval($_POST['team_id']);
    $team_name = $conn->real_escape_string($_POST['team_name']);
    $team_logo = $conn->real_escape_string($_POST['team_logo']);

    $sql = "INSERT INTO favorites (user_name, team_id, team_name, team_logo) 
            VALUES ('$user_name', $team_id, '$team_name', '$team_logo')";

    if ($conn->query($sql) === TRUE) {
        $message = "Data berhasil disimpan!";
    } else {
        $message = "Gagal menyimpan data: " . $conn->error;
    }
}


// Ambil data tim dari API
$teams = getTeamsFromApi();

// Ambil daftar favorit dari database
$favorites = [];
$sql = "SELECT * FROM favorites ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $favorites[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Bola - Tim Favorit</title>
</head>
<body>
    <h1>Tambah Tim Favorit Anda</h1>
    <?php if (!empty($message)) : ?>
        <p style="color: green;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="user_name">Nama Anda:</label><br>
        <input type="text" id="user_name" name="user_name" required><br><br>

        <label for="team_id">Pilih Tim Favorit:</label><br>
        <select id="team_id" name="team_id" required>
            <option value="">Pilih tim</option>
            <?php foreach ($teams as $team) : ?>
                <option value="<?= htmlspecialchars($team['id']) ?>" data-name="<?= htmlspecialchars($team['name']) ?>" data-logo="<?= htmlspecialchars($team['logo']) ?>">
                    <?= htmlspecialchars($team['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <input type="hidden" id="team_name" name="team_name">
        <input type="hidden" id="team_logo" name="team_logo">

        <button type="submit">Simpan</button>
    </form>

    <h2>Daftar Tim Favorit</h2>
    <ul>
        <?php if (!empty($favorites)) : ?>
            <?php foreach ($favorites as $favorite) : ?>
                <li>
                    <strong><?= htmlspecialchars($favorite['user_name']) ?></strong> menyukai
                    <img src="<?= htmlspecialchars($favorite['team_logo']) ?>" alt="Logo Tim" style="width:30px;">
                    <strong><?= htmlspecialchars($favorite['team_name']) ?></strong>
                </li>
            <?php endforeach; ?>
        <?php else : ?>
            <li>Belum ada data tim favorit.</li>
        <?php endif; ?>
    </ul>

    <script>
        // Menyinkronkan input tersembunyi dengan data tim yang dipilih
        const teamSelect = document.getElementById('team_id');
        const teamNameInput = document.getElementById('team_name');
        const teamLogoInput = document.getElementById('team_logo');

        teamSelect.addEventListener('change', function () {
            const selectedOption = teamSelect.options[teamSelect.selectedIndex];
            teamNameInput.value = selectedOption.dataset.name || '';
            teamLogoInput.value = selectedOption.dataset.logo || '';
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>
