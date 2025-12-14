

<?php
session_start();
require_once 'configure.php';
require_once 'login_homepage.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Christmas Sliding Puzzle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page-wrapper">
    <div class="auth-corner">
  <details>
    <summary>
    <?= isset($_SESSION["user_id"])
      ? "👤 " . htmlspecialchars($_SESSION["username"])
      : "👤 Sign In" ?>
    </summary>

    <div class="auth-dropdown">
      <form method="POST" action="login.php">
        <label>
          Username
          <input type="text" name="username" required>
        </label>

        <label>
          Password
          <input type="password" name="password" required>
        </label>

        <button type="submit">Sign In</button>
      </form>

      <div class="auth-links">
        <a href="register.php">Create account</a>
      </div>
    </div>
  </details>
</div>
        <header class="header">
            <h1>Santa's Christmas Puzzle</h1>
            <p class="subtitle">Slide the tiles around to fix the grid</p>
        </header>

        <main class="main-layout">
            
            <section class="game-panel">
                <div class="status-bar">
                    <div>
                        Difficulty:
                        <select id="difficulty">
                           
                            <option value="easy">Easy (3×3)</option>
                            <option value="medium">Medium (4×4)</option>
                            <option value="hard">Hard (6×6)</option>
                            <option value="insane">Insane (8×8)</option>
                        </select>
                    </div>

                    <div>Moves: <span id="move-count">0</span></div>
                    <div>Time: <span id="timer">0</span>s</div>
                </div>

                <div id="puzzle-grid" class="puzzle-grid">
                    <!-- tiles are injected here by game.js -->
                </div>

                <div class="controls">
                    <button id="btn-new-game">New Game</button>
                    <button id="btn-save-session">Save Session</button>
                </div>

                <div id="message" class="message"></div>
            </section>

            
            <aside class="info-panel">
                <h2>How to Play</h2>
                    <ul>
                        <li>Click a tile beside the open space and watch it gently slide into place.</li>
                        <li>Your goal is to arrange the tiles in neat, ascending order.</li>
                        <li>As the difficulty increases, the board grows larger and the challenge takes a bit more time.</li>
                        <li>Settle in and see if you can finish with the fewest moves and the shortest time.</li>
                    </ul>

<h3>Leaderboard</h3>
<p>
    A little friendly inspiration—these are the top completed games for your chosen difficulty.
</p>
<ul id="leaderboard" class="leaderboard">
    <li>Warming up the leaderboard…</li>
</ul>

<h3>How Are You Doing?</h3>
<p>
    Take a moment to check in with your progress and enjoy the rhythm of the puzzle as it comes together.
</p>

            </aside>
        </main>

        <footer class="footer">
            <p>Made by Mumo Musyoka and Mohsin</p>
        </footer>
    </div>

    
    <div id="snow-container" class="snow-container"></div>
    <div id="pixel-snowman" class="pixel-snowman">⛄</div>

    
    <audio id="moveSound" src="sounds/move.mp3" preload="auto"></audio>
    <audio id="winSound" src="sounds/win.mp3" preload="auto"></audio>
    <audio id="loseSound" src="sounds/lose.mp3" preload="auto"></audio>

    <script src="game.js"></script>
</body>
</html>