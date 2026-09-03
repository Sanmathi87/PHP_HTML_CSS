<?php
// Task 15: Browser History Using Stack
session_start();

if (!isset($_SESSION["history"])) {
    $_SESSION["history"] = ["Home Page"];
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["visit"]) && trim($_POST["page"]) != "") {
        // Push new page onto the stack
        array_push($_SESSION["history"], trim($_POST["page"]));
        $message = "Visited: " . htmlspecialchars(trim($_POST["page"]));
    } elseif (isset($_POST["back"])) {
        if (count($_SESSION["history"]) > 1) {
            // Pop the most recent page off the stack
            $left = array_pop($_SESSION["history"]);
            $message = "Went back from: " . htmlspecialchars($left);
        } else {
            $message = "Already at the first page. Cannot go back further.";
        }
    } elseif (isset($_POST["reset"])) {
        $_SESSION["history"] = ["Home Page"];
        $message = "Browser history has been reset.";
    }
}

$history = $_SESSION["history"];
$currentPage = end($history);
$reversedHistory = array_reverse($history);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Browser History Using Stack</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #eaf2f8; display: flex; justify-content: center; padding: 40px 20px; margin: 0; }
    .container { background: #fff; max-width: 500px; width: 100%; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.12); }
    h1 { color: #1a5276; text-align: center; margin-bottom: 5px; font-size: 21px; }
    .current { text-align: center; color: #196f3d; font-weight: bold; margin-bottom: 20px; }
    .inline-form { display: flex; gap: 10px; margin-bottom: 12px; }
    input[type="text"] { flex: 1; padding: 9px; border: 1px solid #aed6f1; border-radius: 6px; font-size: 14px; }
    button { background-color: #1a5276; color: white; border: none; padding: 9px 16px; border-radius: 6px; font-size: 14px; cursor: pointer; }
    button:hover { background-color: #154360; }
    .back-btn { background-color: #b9770e; width: 100%; margin-bottom: 8px; }
    .back-btn:hover { background-color: #9c640c; }
    .reset-btn { background-color: #b03a2e; width: 100%; margin-bottom: 15px; }
    .reset-btn:hover { background-color: #922b21; }
    .message { background-color: #d6eaf8; color: #1a5276; border: 1px solid #aed6f1; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; }
    h2 { color: #1a5276; font-size: 16px; border-bottom: 2px solid #d6eaf8; padding-bottom: 5px; }
    ol { padding-left: 20px; }
    .top { font-weight: bold; color: #196f3d; }
</style>
</head>
<body>

<div class="container">
    <h1>Browser History Using Stack</h1>
    <p class="current">Current Page: <?php echo htmlspecialchars($currentPage); ?></p>

    <form method="POST" action="" class="inline-form">
        <input type="text" name="page" placeholder="e.g. Product Details">
        <button type="submit" name="visit">Visit Page</button>
    </form>

    <form method="POST" action="">
        <button type="submit" name="back" class="back-btn">Go Back</button>
    </form>

    <form method="POST" action="">
        <button type="submit" name="reset" class="reset-btn">Reset History</button>
    </form>

    <?php if ($message != "") : ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <h2>History Stack (Top = Most Recent)</h2>
    <ol>
        <?php foreach ($reversedHistory as $index => $page) : ?>
            <li class="<?php echo ($index == 0) ? 'top' : ''; ?>"><?php echo htmlspecialchars($page); ?></li>
        <?php endforeach; ?>
    </ol>
</div>

</body>
</html>