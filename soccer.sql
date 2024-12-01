-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Des 2024 pada 12.39
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `soccer`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `team_id` int(11) NOT NULL,
  `team_name` varchar(100) NOT NULL,
  `team_logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `favorites`
--

INSERT INTO `favorites` (`id`, `user_name`, `team_id`, `team_name`, `team_logo`, `created_at`, `user_id`) VALUES
(3, '', 186, 'FC St. Pauli', 'https://media.api-sports.io/football/teams/186.png', '2024-11-28 04:01:57', 4),
(5, '', 490, 'Cagliari', 'https://media.api-sports.io/football/teams/490.png', '2024-11-28 04:03:29', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `login_logs`
--

CREATE TABLE `login_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `status` enum('success','failure') DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `login_logs`
--

INSERT INTO `login_logs` (`id`, `username`, `ip_address`, `status`, `timestamp`) VALUES
(0, 'thomidz', '::1', 'failure', '2024-12-01 11:17:43'),
(0, '32rfd', '::1', 'success', '2024-12-01 11:18:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `matches`
--

CREATE TABLE `matches` (
  `home_team_name` varchar(100) DEFAULT NULL,
  `away_team_name` varchar(100) DEFAULT NULL,
  `home_team_id` int(11) DEFAULT NULL,
  `away_team_id` int(11) DEFAULT NULL,
  `home_score` int(11) DEFAULT NULL,
  `away_score` int(11) DEFAULT NULL,
  `match_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `profiles`
--

CREATE TABLE `profiles` (
  `user_id` int(11) NOT NULL,
  `bio` text DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `google_plus` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `profile_photo` varchar(255) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `profiles`
--

INSERT INTO `profiles` (`user_id`, `bio`, `birthday`, `country`, `phone`, `twitter`, `facebook`, `google_plus`, `linkedin`, `instagram`, `profile_photo`, `name`) VALUES
(1, 'asd', '0000-00-00', '1', '1', 'https://x.com/', '', '', '', '', 'uploads/profile_674563734424c7.28872926.jpg', NULL),
(4, '1', '2024-11-05', '1', '1', 'https://x.com/', '', '', '', '', 'uploads/profile_674bae3f973f14.82679326.jpg', NULL),
(12, '1', '0000-00-00', '1', '1', 'https://x.com/', '', '', '', '', 'uploads/profile_674bd6cc0717f3.51286869.jpg', 'salah huddin'),
(27, 'q', '0000-00-00', '', '0', 'https://x.c', '', '', '', '', 'uploads/profile_674c4a3c3e6441.35023330.jpeg', 'dewi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `register_logs`
--

CREATE TABLE `register_logs` (
  `id` int(11) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `register_logs`
--

INSERT INTO `register_logs` (`id`, `email`, `username`, `ip_address`, `timestamp`) VALUES
(0, 'fegm@gmail.com', '32rfd', '::1', '2024-12-01 11:14:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `email`) VALUES
(1, 'thomi', '123', '2024-10-21 10:48:39', ''),
(4, 'thomidz', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-10-21 11:25:49', ''),
(5, 'ayam', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-10-21 14:31:17', ''),
(6, 'qondru', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-10-30 02:20:06', ''),
(8, 'qondrul', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-10-30 03:02:00', ''),
(9, 'walawe', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-10-31 07:18:48', ''),
(10, '1', '6b86b273ff34fce19d6b804eff5a3f5747ada4eaa22f1d49c01e52ddb7875b4b', '2024-11-26 03:41:29', ''),
(11, '0', '5feceb66ffc86f38d952786c6d696c79c2dbc239dd4e91b46729d73a27fb57e9', '2024-11-30 01:05:20', ''),
(12, 'qwe', '489cd5dbc708c7e541de4d7cd91ce6d0f1613573b7fc5b40d3942ccb9555cf35', '2024-12-01 03:05:13', ''),
(13, 'p', '$2y$10$MECcPRuA5Q783sFDwVhzfOhuknEfWbKFR0VtAC2k05J7jA8LRbDJy', '2024-12-01 10:45:28', 'p@p.com'),
(14, 'g', '$2y$10$2RqBeG7zSEA..5Qw/sH/yOOd2q2g8Kjx5ukPPqE8GEnxpMqjnEwxu', '2024-12-01 10:45:48', 'g@gmail.com'),
(16, 'amal', '$2y$10$t39z/2dF2NwzMd/84.htL.KO/8qMu2bGqwYI0SHg/9WZNe28ypFTi', '2024-12-01 10:46:55', 'amal@gmail.com'),
(17, 'priadi', '$2y$10$MVI/tnOLeZoaScS3pKzH0uAIkUFi9iOcfUnQET8H00O5qKTV/TQ72', '2024-12-01 10:47:46', 'priadi@gmail.com'),
(20, 'zakwan', '$2y$10$L2P8.oVb7IXuYgcXemah9ucfSIfqwfncxGDRUqGo5gnvv89NaUuSa', '2024-12-01 10:51:04', 'zakwan@gmail.com'),
(22, 'hjvmnhn', '$2y$10$Tj1F14XF5rkakd.N7.p4/eDTOkOaKRWJYIJR7R2YHbxcAuTYYPF0K', '2024-12-01 10:58:10', 'qadrulzdan@gmail.com'),
(23, 'basdbAJSD', '$2y$10$h.Jz3KIlm3zxMurmm9MIOO0eUIeFJk2Mz7N4FwB627eMNRS85G.Y2', '2024-12-01 11:03:19', 'bgasdhbdas@gmail.com'),
(25, 'kfdhmldkfg', '$2y$10$uiZTe6QQmsftT8Rvcuf.buUJz65BRlXdhWdwKIyJQBr4TMGYsK2Dy', '2024-12-01 11:13:20', 'asdas@gmail.com'),
(27, '32rfd', '$2y$10$lqFaNXSImqy3PJ8r.JW6DeM.ATIs.WX4LtAkB.wJdoSQwf4ZAgBxS', '2024-12-01 11:14:41', 'fegm@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
