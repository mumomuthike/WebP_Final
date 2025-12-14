<?php
require __DIR__ . "/configure.php";

/* USERS TABLE */
$sql_users = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
";

/* SESSIONS TABLE */
$sql_sessions = "
CREATE TABLE IF NOT EXISTS puzzle_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    difficulty VARCHAR(20),
    moves INT,
    elapsed_seconds INT,
    score INT DEFAULT 0,
    finished TINYINT(1),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_leaderboard (finished, difficulty, score, moves, elapsed_seconds)
) ENGINE=InnoDB;
";

/* RUN QUERIES + ERROR CHECKING */
if (!mysqli_query($link, $sql_users)) {
    die("❌ Error creating users table: " . mysqli_error($link));
}

if (!mysqli_query($link, $sql_sessions)) {
    die("❌ Error creating puzzle_sessions table: " . mysqli_error($link));
}

echo "✅ Tables created successfully.";

mysqli_close($link);