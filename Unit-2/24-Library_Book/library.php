<?php
// Task 24: Library Book Search System

$books = [
    ["title" => "The Alchemist", "author" => "Paulo Coelho", "copies" => 3],
    ["title" => "Clean Code", "author" => "Robert C. Martin", "copies" => 0],
    ["title" => "Atomic Habits", "author" => "James Clear", "copies" => 5],
    ["title" => "1984", "author" => "George Orwell", "copies" => 2],
    ["title" => "Sapiens", "author" => "Yuval Noah Harari", "copies" => 0],
    ["title" => "Ikigai", "author" => "Hector Garcia", "copies" => 4]
];

$searchResult = null;
$searched = false;
$searchTerm = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searched = true;
    $searchTerm = trim($_POST["title"]);

    foreach ($books as $book) {
        if (strcasecmp($book["title"], $searchTerm) == 0) {
            $searchResult = $book;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Library Book Search System</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #fef5e7; display: flex; justify-content: center; padding: 40px 20px; margin: 0; }
    .container { background: #fff; max-width: 600px; width: 100%; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.12); }
    h1 { color: #7e5109; text-align: center; margin-bottom: 20px; font-size: 21px; }
    form { display: flex; gap: 10px; margin-bottom: 20px; }
    input[type="text"] { flex: 1; padding: 10px; border: 1px solid #f0d9a8; border-radius: 6px; font-size: 14px; }
    button { background-color: #b9770e; color: white; border: none; padding: 10px 18px; border-radius: 6px; font-size: 14px; cursor: pointer; }
    button:hover { background-color: #935116; }
    .message { margin-bottom: 20px; padding: 12px; border-radius: 6px; font-size: 14px; }
    .available { background-color: #d4efdf; color: #196f3d; border: 1px solid #a9dfbf; }
    .unavailable { background-color: #fadbd8; color: #922b21; border: 1px solid #f5b7b1; }
    h2 { color: #7e5109; border-bottom: 2px solid #f0d9a8; padding-bottom: 5px; font-size: 17px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
    th, td { padding: 8px; text-align: center; border: 1px solid #f0d9a8; }
    th { background-color: #b9770e; color: white; }
    tr:nth-child(even) { background-color: #fef5e7; }
</style>
</head>
<body>

<div class="container">
    <h1>Library Book Search System</h1>

    <form method="POST" action="">
        <input type="text" name="title" placeholder="e.g. Atomic Habits" required>
        <button type="submit">Search</button>
    </form>

    <?php if ($searched) : ?>
        <?php if ($searchResult) : ?>
            <?php if ($searchResult["copies"] > 0) : ?>
                <div class="message available">
                    <strong><?php echo $searchResult["title"]; ?></strong> by <?php echo $searchResult["author"]; ?><br>
                    Available - <?php echo $searchResult["copies"]; ?> copies in stock.
                </div>
            <?php else : ?>
                <div class="message unavailable">
                    <strong><?php echo $searchResult["title"]; ?></strong> by <?php echo $searchResult["author"]; ?><br>
                    Currently unavailable - all copies are issued.
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="message unavailable">
                No book titled "<?php echo htmlspecialchars($searchTerm); ?>" found in the library.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <h2>Library Catalogue</h2>
    <table>
        <tr><th>Title</th><th>Author</th><th>Copies</th></tr>
        <?php foreach ($books as $book) : ?>
        <tr>
            <td><?php echo $book["title"]; ?></td>
            <td><?php echo $book["author"]; ?></td>
            <td><?php echo $book["copies"]; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>