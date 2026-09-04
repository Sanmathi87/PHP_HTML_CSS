<?php
// Article File Reading and Display
$fileName = "article.txt";
$message = "";

// Create a sample article file if it doesn't exist
if (!file_exists($fileName)) {
    $sampleContent = "Title: The Rise of Web Technologies\n";
    $sampleContent .= "Author: Ammu\n";
    $sampleContent .= "Date: 2026-08-20\n";
    $sampleContent .= "-----\n";
    $sampleContent .= "Web development has evolved rapidly over the years.\n";
    $sampleContent .= "PHP remains a popular server-side scripting language.\n";
    $sampleContent .= "Modern frameworks simplify complex tasks for developers.\n";
    $sampleContent .= "Understanding file handling is essential for real applications.\n";
    file_put_contents($fileName, $sampleContent);
}

// Handle new article submission (append/overwrite)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['article_body'])) {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $body = trim($_POST['article_body']);

    if ($title === "" || $author === "" || $body === "") {
        $message = "All fields are required.";
    } else {
        try {
            $content = "Title: $title\nAuthor: $author\nDate: " . date("Y-m-d") . "\n-----\n$body\n";
            if (file_put_contents($fileName, $content) === false) {
                throw new Exception("Unable to write to article file.");
            }
            $message = "Article saved successfully!";
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}

// Read and process the file
$lines = [];
$lineCount = 0;
try {
    if (!file_exists($fileName) || !is_readable($fileName)) {
        throw new Exception("Article file not found or unreadable.");
    }
    $lines = file($fileName, FILE_IGNORE_NEW_LINES);
    $lineCount = count($lines);
} catch (Exception $e) {
    $message = "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Article File Reader</title>
<style>
    body { font-family: Georgia, serif; background: #fdfaf3; margin: 0; padding: 40px; }
    .container { max-width: 600px; margin: auto; }
    h2 { color: #5b3a29; }
    .article-box { background: #fff; border-left: 5px solid #a67c52; padding: 20px; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 25px; }
    .meta { color: #7d6b5d; font-size: 14px; margin-bottom: 10px; }
    .lines { font-size: 13px; color: #999; text-align: right; }
    form { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    input, textarea { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 5px; }
    button { margin-top: 15px; background: #a67c52; color: #fff; border: none; padding: 10px 18px; border-radius: 5px; cursor: pointer; }
    .message { color: #2e7d32; font-weight: bold; }
</style>
</head>
<body>
<div class="container">
    <h2>📰 Article Reader</h2>
    <?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>

    <div class="article-box">
        <?php foreach ($lines as $index => $line): ?>
            <?php if ($line === "-----"): ?>
                <hr>
            <?php elseif (strpos($line, "Title:") === 0 || strpos($line, "Author:") === 0 || strpos($line, "Date:") === 0): ?>
                <div class="meta"><?php echo htmlspecialchars($line); ?></div>
            <?php else: ?>
                <p><?php echo htmlspecialchars($line); ?></p>
            <?php endif; ?>
        <?php endforeach; ?>
        <div class="lines">Total lines in file: <?php echo $lineCount; ?></div>
    </div>

    <form method="POST" action="article_reader.php">
        <h3>Add / Replace Article</h3>
        <label>Title</label>
        <input type="text" name="title" required>
        <label>Author</label>
        <input type="text" name="author" required>
        <label>Content</label>
        <textarea name="article_body" rows="4" required></textarea>
        <button type="submit">Save Article</button>
    </form>
</div>
</body>
</html>