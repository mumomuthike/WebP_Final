<?php
session_start();
require "configure.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $hash);
    mysqli_stmt_fetch($stmt);

    if ($hash && password_verify($password, $hash)) {
        $_SESSION["user_id"] = $id;
        $_SESSION["username"] = $username;
        header("Location: index.php");
        exit;
    } else {
        echo "Login failed.";
    }

    mysqli_stmt_close($stmt);
}
?>

<form method="POST">
    <h2>🎁 Login</h2>
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>
