<?php
require "configure.php";

/* TABLE */
$sql_users = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
)";


$sql_sessions = "
CREATE TABLE IF NOT EXISTS puzzle_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    difficulty VARCHAR(20),
    moves INT,
    elapsed_seconds INT,
    score INT DEFAULT 0,
    finished TINYINT(1),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";


$sql_index = "CREATE INDEX IF NOT EXISTS idx_sessions_leaderboard ON puzzle_sessions (finished, difficulty, score, moves, elapsed_seconds, created_at)";

mysqli_query($link, $sql_users);
mysqli_query($link, $sql_sessions);
@mysqli_query($link, $sql_index); 

echo "Tables created successfully.";

mysqli_close($link);
?>
