<?php

require_once 'configure.php';
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
        <header class="header">
            <h1>Santa's Christmas Puzzle</h1>
            <p class="subtitle">slide the tiles around to fix the grid</p>
        </header>

        <main class="main-layout">
            <section class="game-panel">
                <div class="status-bar">
                    <div>
                        Difficulty:
                        <select id="difficulty">
                            <option value="easy">Simple (4×4)</option>
                            <option value="medium">Medium (6×6)</option>
                            <option value="hard">Difficult (8×8)</option>
                            <option value="insane">Crazy (10×10)</option>
                        </select>
                    </div>

                    <div>Moves: <span id="move-count">0</span></div>
                    <div>Time: <span id="timer">0</span>s</div>
                </div>

                <div id="puzzle-grid" class="puzzle-grid">
                
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
                    <li>Click a tile next to the empty space to slide it.</li>
                    <li>Arrange the tiles in ascending order.</li>
                    <li>Higher difficulties have larger grids and longer timers.</li>
                    <li>Try to solve with the fewest moves and time!</li>
                </ul>

                <h3>Session Log</h3>
                <p>
                    In the final version, this panel will show your recent
                    results and stats from the database.
                </p>
            </aside>
        </main>

        <footer class="footer">
            <p>Made by Mumo Musyoka </p>
        </footer>
    </div>

    <script src="game.js"></script>
</body>
</html>

