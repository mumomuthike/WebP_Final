<?php

header('Content-Type: application/json');

session_start();
require 'configure.php';

// Logged-in users get credit
$user_id = $_SESSION['user_id'] ?? null;

// Validate the  inputs
$difficulty = $_POST['difficulty'] ?? null;
$moves      = $_POST['moves'] ?? null;
$elapsed    = $_POST['elapsed_seconds'] ?? null;
$finished   = $_POST['finished'] ?? null;

if ($difficulty === null || $moves === null || $elapsed === null || $finished === null) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

$moves    = (int)$moves;
$elapsed  = (int)$elapsed;
$finished = (int)$finished;

// Score logic that should match frontend
$multiplier = 1;
switch ($difficulty) {
    case 'medium': $multiplier = 2; break;
    case 'hard':   $multiplier = 3; break;
    case 'insane': $multiplier = 5; break;
    default:       $multiplier = 1; break;
}

$baseScore   = 1000;
$movePenalty = 5;
$timePenalty = 2;
$score = max(($baseScore * $multiplier) - ($moves * $movePenalty) - ($elapsed * $timePenalty), 0);

$sql = "
    INSERT INTO puzzle_sessions (user_id, difficulty, moves, elapsed_seconds, score, finished)
    VALUES (?, ?, ?, ?, ?, ?)
";

$stmt = mysqli_prepare($link, $sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Prepare failed']);
    exit;
}

// user_id might be null though
mysqli_stmt_bind_param($stmt, 'isiiii', $user_id, $difficulty, $moves, $elapsed, $score, $finished);
$ok = mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

echo json_encode([
    'success' => (bool)$ok,
    'score'   => $score
]);
