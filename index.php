<?php
require_once 'config.php';

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Handle adding a movie to the playlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isLoggedIn && isset($_POST['movie_id'])) {
    $user_id = $_SESSION['user_id'];
    $movie_id = $_POST['movie_id'];

    // Check if the movie is already in the user's playlist
    $sql = "SELECT * FROM playlists WHERE user_id = ? AND movie_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $movie_id]);
    if ($stmt->rowCount() == 0) {
        // Add the movie to the playlist
        $sql = "INSERT INTO playlists (user_id, movie_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $movie_id]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ottawa Movies - Home</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo"><a href="index.php">Ottawa Movies</a></div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php" class="active"><img src="./image/home.png" alt="Home Icon" class="nav-icon"> Home</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="playlist.php"><img src="./image/watchlist.png" alt="Watchlist Icon" class="nav-icon"> My Playlist</a></li>
                <?php endif; ?>
                <li><a href="search.php"><img src="./image/search.png" alt="Search Icon" class="nav-icon"> Search</a></li>
                <li>
                    <?php if ($isLoggedIn): ?>
                        <a href="logout.php"><img src="./image/login.png" alt="Logout Icon" class="nav-icon"> Logout</a>
                    <?php else: ?>
                        <a href="login.php"><img src="./image/regestier.png" alt="Login Icon" class="nav-icon"> Login/Register</a>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <!-- New Items of This Season Section -->
        <section class="new-items-season">
            <h1>Best 100 Movies</h1>
            <div class="movie-carousel">
                <button class="carousel-btn left">←</button>
                <div class="movie-grid" id="movieGrid">
                    <?php
                    $sql = "SELECT * FROM movies ORDER BY id ASC";
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="movie-item">';
                        echo '<a href="movie_details.php?id=' . $row['id'] . '">';
                        echo '<img src="./image/' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                        echo '</a>';
                        echo '<h3>' . htmlspecialchars(substr($row['name'], 0, 20)) . (strlen($row['name']) > 20 ? '...' : '') . '</h3>';
                        echo '<p>' . htmlspecialchars($row['genre']) . ' <span class="rating">★ ' . htmlspecialchars($row['rating']) . '</span></p>';
                        if ($isLoggedIn) {
                            echo '<form method="POST" style="margin-top: 10px;">';
                            echo '<input type="hidden" name="movie_id" value="' . $row['id'] . '">';
                            echo '<button type="submit" class="add-to-playlist-btn">Add to Playlist</button>';
                            echo '</form>';
                        }
                        echo '</div>';
                    }
                    ?>
                </div>
                <button class="carousel-btn right">→</button>
            </div>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>Copyright@Mahmoud Sharmarke Moustafa 2025</p>
    </footer>

    <script src="index.js"></script>
</body>
</html>