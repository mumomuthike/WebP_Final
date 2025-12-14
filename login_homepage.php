<?php
session_start();
require __DIR__ . "/configure.php";

$login_error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login_submit"])) {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $hash);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($hash && password_verify($password, $hash)) {
        $_SESSION["user_id"] = $id;
        $_SESSION["username"] = $username;
        header("Location: index.php");
        exit;
    } else {
        $login_error = "Invalid username or password.";
    }
}
?>