

let tiles = [];
let moveCount = 0;
let seconds = 0;
let timerInterval = null;
let finalScore = 0;

const SCORE_MULTIPLIER = {
    easy: 1,
    medium: 2,
    hard: 3,
    insane: 5
};

let gridSize = 4;
let maxTime = 60;

/* difficulty */
const LEVELS = {
    easy:   { size: 3, time: 60 },    // 3×3
    medium: { size: 4, time: 120 },   // 4×4
    hard:   { size: 6, time: 240 },   // 6×6
    insane: { size: 8, time: 420 },   // 8×8
};


const gridEl = document.getElementById("puzzle-grid");
const moveCountEl = document.getElementById("move-count");
const timerEl = document.getElementById("timer");
const msgEl = document.getElementById("message");
const difficultyEl = document.getElementById("difficulty");
const btnNewGame = document.getElementById("btn-new-game");
const btnSaveSession = document.getElementById("btn-save-session");
const leaderboardEl = document.getElementById("leaderboard");


btnNewGame.addEventListener("click", startNewGame);
btnSaveSession.addEventListener("click", () => saveSessionToServer(false));
difficultyEl.addEventListener("change", startNewGame);

startNewGame();
refreshLeaderboard();

function startNewGame() {
    resetTimer();
    stopSnowfall();
    hideSnowman();

    const level = LEVELS[difficultyEl.value];
    gridSize = level.size;
    maxTime = level.time;

    seconds = 0;
    moveCount = 0;

    gridEl.style.gridTemplateColumns = `repeat(${gridSize}, 1fr)`;

    updateHUD();
    msgEl.textContent = "Good luck! 🎄";

    generateSolvableBoard();
    renderBoard();
    startTimer();
}



function generateSolvableBoard() {
    const tileCount = gridSize * gridSize;
    tiles = Array.from({ length: tileCount - 1 }, (_, i) => i + 1);
    tiles.push(null);

    do {
        shuffleArray(tiles);
    } while (!isSolvable(tiles) || isSolved(tiles));
}

function shuffleArray(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [arr[i], arr[j]] = [arr[j], arr[i]];
    }
}

function isSolvable(arr) {
    let inversions = 0;

    for (let i = 0; i < arr.length; i++) {
        for (let j = i + 1; j < arr.length; j++) {
            if (arr[i] && arr[j] && arr[i] > arr[j]) inversions++;
        }
    }

    const blankIndex = arr.indexOf(null);
    const blankRowFromBottom = gridSize - Math.floor(blankIndex / gridSize);

    
    if (gridSize % 2 === 0) {
        return blankRowFromBottom % 2 === 0
            ? inversions % 2 === 1
            : inversions % 2 === 0;
    }

 
    return inversions % 2 === 0;
}

function isSolved(arr) {
    for (let i = 0; i < arr.length - 1; i++) {
        if (arr[i] !== i + 1) return false;
    }
    return arr[arr.length - 1] === null;
}
function calculateScore() {
    const difficulty = difficultyEl.value;
    const multiplier = SCORE_MULTIPLIER[difficulty];

    const baseScore = 1000;
    const movePenalty = 5;
    const timePenalty = 2;

    let score =
        baseScore * multiplier
        - moveCount * movePenalty
        - seconds * timePenalty;

    return Math.max(score, 0);
}

function renderBoard() {
    gridEl.innerHTML = "";

    tiles.forEach((value, index) => {
        const tile = document.createElement("div");
        tile.className = "tile";

        if (value === null) {
            tile.classList.add("empty");
        } else {
            tile.textContent = value;
            tile.onclick = () => handleTileClick(index);
        }

        gridEl.appendChild(tile);
    });
}


function handleTileClick(index) {
    const emptyIndex = tiles.indexOf(null);
    if (!isNeighbor(index, emptyIndex)) return;

    [tiles[index], tiles[emptyIndex]] = [tiles[emptyIndex], tiles[index]];
    moveCount++;

    updateHUD();
    renderBoard();

    if (isSolved(tiles)) onPuzzleSolved();
}

function isNeighbor(a, b) {
    const r1 = Math.floor(a / gridSize),
          c1 = a % gridSize;
    const r2 = Math.floor(b / gridSize),
          c2 = b % gridSize;

    return Math.abs(r1 - r2) + Math.abs(c1 - c2) === 1;
}

function startTimer() {
    timerInterval = setInterval(() => {
        seconds++;
        updateHUD();

        if (seconds >= maxTime) onPuzzleLost();
        if (seconds > maxTime * 0.75) timerEl.classList.add("timer-warning");
    }, 1000);
}

function resetTimer() {
    clearInterval(timerInterval);
    timerInterval = null;
    timerEl.classList.remove("timer-warning");
}


function onPuzzleSolved() {
    resetTimer();
    msgEl.textContent = "✨ You solved it! Merry Christmas!";
    msgEl.classList.add("success-glow");

    setTimeout(() => msgEl.classList.remove("success-glow"), 1200);
    saveSessionToServer(true);
}

function onPuzzleLost() {
    resetTimer();
    msgEl.textContent = "❄️ You ran out of time!";
    startSnowfall();
    showSnowman();
    saveSessionToServer(false);
}


function updateHUD() {
    moveCountEl.textContent = moveCount;
    timerEl.textContent = seconds;
}


function saveSessionToServer(finished) {
    const payload = new FormData();
    payload.append("difficulty", difficultyEl.value);
    payload.append("moves", moveCount);
    payload.append("elapsed_seconds", seconds);
    payload.append("finished", finished ? "1" : "0");

    fetch("session.php", {
        method: "POST",
        body: payload,
    })
        .then((r) => r.json())
        .then((data) => {
            if (!data || !data.success) {
                console.warn("Session save failed", data);
                return;
            }
            // Update leaderboard after a successful save
            refreshLeaderboard();
        })
        .catch((err) => console.warn("Session save error", err));
}

function refreshLeaderboard() {
    if (!leaderboardEl) return;

    const diff = difficultyEl.value;
    fetch(`leaderboard.php?difficulty=${encodeURIComponent(diff)}&limit=10`)
        .then((r) => r.json())
        .then((data) => {
            if (!data || !data.success) {
                leaderboardEl.innerHTML = "<li>Couldn’t load leaderboard.</li>";
                return;
            }

            const items = data.items || [];
            if (items.length === 0) {
                leaderboardEl.innerHTML = "<li>No wins yet — be the first ✨</li>";
                return;
            }

            leaderboardEl.innerHTML = "";
            items.forEach((it) => {
                const li = document.createElement("li");
                const name = it.username || "Guest";
                li.textContent = `${name} — ${it.score} pts • ${it.moves} moves • ${it.elapsed_seconds}s`;
                leaderboardEl.appendChild(li);
            });
        })
        .catch(() => {
            leaderboardEl.innerHTML = "<li>Couldn’t load leaderboard.</li>";
        });
}


function startSnowfall() {
    const el = document.getElementById("snow-container");
    if (el) el.style.display = "block";
}

function stopSnowfall() {
    const el = document.getElementById("snow-container");
    if (el) el.style.display = "none";
}

function showSnowman() {
    const el = document.getElementById("pixel-snowman");
    if (el) el.style.display = "block";
}

function hideSnowman() {
    const el = document.getElementById("pixel-snowman");
    if (el) el.style.display = "none";
}