<?php
require "configure.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);

    if (mysqli_stmt_execute($stmt)) {
        echo "Registration successful. <a href='login.php'>Login</a>";
    } else {
        echo "Username already exists.";
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register 🎄</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">

  <div class="auth-wrapper">
    <div class="auth-card">


      <h1 class="auth-title">Create an Account</h1>
      <?php if (isset($_SESSION["username"])): ?>
        <p class="welcome-msg">
             Hey, <?= htmlspecialchars($_SESSION["username"]) ?>! 🎄
        </p>
        <?php endif; ?>
      <p class="auth-subtitle">Save your scores & join the leaderboard ✨</p>

      <?php if (!empty($error)): ?>
        <div class="auth-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div class="auth-success">
          Registration successful 🎉<br>
          <a href="login.php">Sign in</a>
        </div>
      <?php else: ?>
        <form method="POST" class="auth-form">
          <label>
            Username
            <input type="text" name="username" required>
          </label>

          <label>
            Password
            <input type="password" name="password" required>
          </label>

          <button type="submit" class="auth-button">Register</button>
        </form>
      <?php endif; ?>

    </div>
  </div>

</body>
</html>