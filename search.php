<?php
require_once 'config.php';

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);

// Handle the search query
$searchResults = [];
$searchQuery = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
    $searchQuery = trim($_GET['query']);
    if (!empty($searchQuery)) {
        // Prepare the search query for LIKE and SOUNDEX
        $likeQuery = '%' . $searchQuery . '%';
        $soundexQuery = $searchQuery;

        // Search for movies by title, cast, director, or writer
        $sql = "SELECT * FROM movies WHERE 
                name LIKE ? OR 
                SOUNDEX(name) = SOUNDEX(?) OR 
                casts LIKE ? OR 
                SOUNDEX(casts) = SOUNDEX(?) OR 
                directors LIKE ? OR 
                SOUNDEX(directors) = SOUNDEX(?) OR 
                writers LIKE ? OR 
                SOUNDEX(writers) = SOUNDEX(?) 
                ORDER BY id ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $likeQuery, $soundexQuery,
            $likeQuery, $soundexQuery,
            $likeQuery, $soundexQuery,
            $likeQuery, $soundexQuery
        ]);
        $searchResults = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ottawa Movies - Search</title>
    <link rel="stylesheet" href="search.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo"><a href="index.php">Ottawa Movies</a></div>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php"><img src="./image/home.png" alt="Home Icon" class="nav-icon"> Home</a></li>
                <?php if ($isLoggedIn): ?>
                    <li><a href="playlist.php"><img src="./image/watchlist.png" alt="Watchlist Icon" class="nav-icon"> My Playlist</a></li>
                <?php endif; ?>
                <li><a href="search.php" class="active"><img src="./image/search.png" alt="Search Icon" class="nav-icon"> Search</a></li>
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
        <section class="search-section">
            <div class="search-container">
                <h1>Find Your Movie</h1>
                <form method="GET" action="search.php" class="search-form">
                    <input type="text" name="query" placeholder="Search by title, cast, director, or writer..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                    <button type="submit"><span class="search-icon">🔍</span> Search</button>
                </form>

                <?php if (!empty($searchQuery)): ?>
                    <div class="search-results">
                        <h2>Results for "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
                        <?php if (count($searchResults) > 0): ?>
                            <div class="movie-grid">
                                <?php foreach ($searchResults as $row): ?>
                                    <div class="movie-card">
                                        <a href="movie_details.php?id=<?php echo $row['id']; ?>">
                                            <img src="./image/<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                            <div class="movie-info">
                                                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                                                <p><?php echo htmlspecialchars($row['genre']); ?></p>
                                                <span class="rating">★ <?php echo htmlspecialchars($row['rating']); ?></span>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="no-results">No movies found for "<?php echo htmlspecialchars($searchQuery); ?>". Try another search.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>Copyright@Mahmoud Sharmarke Moustafa 2025</p>
    </footer>

    <script src="search.js"></script>
</body>
</html>