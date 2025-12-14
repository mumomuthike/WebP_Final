<?php
require __DIR__ . "/configure.php";

/* Demo users */
$users = [
    "SantaKilla",
    "ElfMasta",
    "Rudolphdared",
    "MizzuzClaus"
];

foreach ($users as $u) {
    $hash = password_hash("demo", PASSWORD_DEFAULT);
    mysqli_query(
        $link,
        "INSERT IGNORE INTO users (username, password)
         VALUES ('$u', '$hash')"
    );
}

/* fetch the demo users*/
$userIds = [];
$res = mysqli_query($link, "SELECT id, username FROM users");

while ($row = mysqli_fetch_assoc($res)) {
    $userIds[$row['username']] = $row['id'];
}

/* usernames matching */
$sessions = [
    ['SantaKilla',   'easy',   12,  18,  980],
    ['ElfMasta',     'easy',   15,  25,  920],
    ['Rudolphdared', 'easy',   20,  30,  860],

    ['SantaKilla',   'medium', 40,  80,  850],
    ['ElfMasta',     'medium', 45,  95,  780],

    ['Rudolphdared', 'hard',   120, 180, 720],
    ['MizzuzClaus',  'hard',   130, 210, 680],

    ['ElfMasta',     'insane', 300, 380, 600],
];

/* -------------------------------------------------
   INSERT SESSIONS SAFELY
------------------------------------------------- */
foreach ($sessions as [$name, $diff, $moves, $time, $score]) {

    if (!isset($userIds[$name])) {
        echo "❌ User not found: $name<br>";
        continue;
    }

    $uid = $userIds[$name];

    $sql = "
        INSERT INTO puzzle_sessions
        (user_id, difficulty, moves, elapsed_seconds, score, finished)
        VALUES
        ($uid, '$diff', $moves, $time, $score, 1)
    ";

    if (!mysqli_query($link, $sql)) {
        echo "❌ Insert failed for $name ($diff): "
           . mysqli_error($link) . "<br>";
    }
}

echo "🎄 Leaderboard demo data populated successfully!";
