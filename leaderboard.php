<?php
require __DIR__ . "/configure.php";
header("Content-Type: application/json");

$difficulty = $_GET["difficulty"] ?? "easy";
$limit = intval($_GET["limit"] ?? 10);

$sql = "
SELECT 
  u.username,
  s.moves,
  s.elapsed_seconds,
  s.score
FROM puzzle_sessions s
LEFT JOIN users u ON s.user_id = u.id
WHERE s.finished = 1
  AND s.difficulty = ?
ORDER BY s.score DESC, s.moves ASC, s.elapsed_seconds ASC
LIMIT ?
";

$stmt = mysqli_prepare($link, $sql);
if (!$stmt) {
    echo json_encode(["success" => false, "error" => mysqli_error($link)]);
    exit;
}

mysqli_stmt_bind_param($stmt, "si", $difficulty, $limit);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$items = [];

while ($row = mysqli_fetch_assoc($result)) {
    $items[] = $row;
}

mysqli_stmt_close($stmt);

echo json_encode([
    "success" => true,
    "items" => $items
]);
