<?php
// Task 7: Music Search and Playlist Analysis

$playlist = [
    ["title" => "Rowdy Baby",     "artist" => "Dhanush",     "duration" => "3:38", "genre" => "Kollywood"],
    ["title" => "Vaathi Coming",  "artist" => "Anirudh",     "duration" => "3:21", "genre" => "Kollywood"],
    ["title" => "Blinding Lights","artist" => "The Weeknd",  "duration" => "3:20", "genre" => "Pop"],
    ["title" => "Shape of You",   "artist" => "Ed Sheeran",  "duration" => "3:53", "genre" => "Pop"],
    ["title" => "Kannana Kanne",  "artist" => "S. P. B. Charan", "duration" => "4:32", "genre" => "Kollywood"],
    ["title" => "Believer",       "artist" => "Imagine Dragons", "duration" => "3:24", "genre" => "Rock"]
];

$totalSongs = count($playlist);
$searchResult = null;
$searchTerm = "";
$searched = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searched = true;
    $searchTerm = trim($_POST["song_title"]);

    foreach ($playlist as $song) {
        if (strcasecmp($song["title"], $searchTerm) == 0) {
            $searchResult = $song;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Music Search and Playlist Analysis</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Music Search and Playlist Analysis</h1>
    <p class="subtitle">Total songs in playlist: <?php echo $totalSongs; ?></p>

    <form method="POST" action="">
        <label for="song_title">Search for a song:</label>
        <input type="text" id="song_title" name="song_title" placeholder="e.g. Rowdy Baby" required>
        <button type="submit">Search</button>
    </form>

    <?php if ($searched) : ?>
        <?php if ($searchResult) : ?>
            <div class="message success">
                <strong><?php echo $searchResult["title"]; ?></strong> found<br>
                Artist: <?php echo $searchResult["artist"]; ?><br>
                Duration: <?php echo $searchResult["duration"]; ?><br>
                Genre: <?php echo $searchResult["genre"]; ?>
            </div>
        <?php else : ?>
            <div class="message error">
                No song titled "<?php echo htmlspecialchars($searchTerm); ?>" found in the playlist.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <h2>Full Playlist</h2>
    <table>
        <tr><th>Title</th><th>Artist</th><th>Duration</th><th>Genre</th></tr>
        <?php foreach ($playlist as $song) : ?>
        <tr>
            <td><?php echo $song["title"]; ?></td>
            <td><?php echo $song["artist"]; ?></td>
            <td><?php echo $song["duration"]; ?></td>
            <td><?php echo $song["genre"]; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>