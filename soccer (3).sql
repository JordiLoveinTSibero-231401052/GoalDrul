CREATE TABLE favorites (
  id SERIAL PRIMARY KEY,
  user_name VARCHAR(100) NOT NULL,
  team_id INT NOT NULL,
  team_name VARCHAR(100) NOT NULL,
  team_logo VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT current_timestamp,
  user_id INT NOT NULL
);

-- Tabel matches
CREATE TABLE matches (
  home_team_name VARCHAR(100) DEFAULT NULL,
  away_team_name VARCHAR(100) DEFAULT NULL,
  home_team_id INT DEFAULT NULL,
  away_team_id INT DEFAULT NULL,
  home_score INT DEFAULT NULL,
  away_score INT DEFAULT NULL,
  match_status VARCHAR(50) DEFAULT NULL
);

-- Tabel users
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  username VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT current_timestamp,
  email VARCHAR(255) UNIQUE
);

-- Dump data for users (you dapat menyesuaikan data jika diperlukan)
INSERT INTO users (id, username, password, created_at) VALUES
(1, 'thomi', '123', '2024-10-21 03:48:39'),
(4, 'thomidz', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-10-21 04:25:49'),
(5, 'ayam', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-10-21 07:31:17'),
(6, 'qondru', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-10-29 19:20:06'),
(8, 'qondrul', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-10-29 20:02:00'),
(9, 'walawe', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '2024-10-31 00:18:48');