<?php
// Task 11: Customer Support Queue System (FIFO using array functions)
session_start();

if (!isset($_SESSION["queue"])) {
    $_SESSION["queue"] = [];
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add"]) && trim($_POST["ticket"]) != "") {
        // Add new request to the end of the queue
        array_push($_SESSION["queue"], trim($_POST["ticket"]));
        $message = "New support request added to the queue.";
    } elseif (isset($_POST["process"])) {
        if (count($_SESSION["queue"]) > 0) {
            // Process the request at the front of the queue (FIFO)
            $processed = array_shift($_SESSION["queue"]);
            $message = "Processed request: " . htmlspecialchars($processed);
        } else {
            $message = "Queue is empty. Nothing to process.";
        }
    } elseif (isset($_POST["reset"])) {
        $_SESSION["queue"] = [];
        $message = "Queue has been reset.";
    }
}

$queue = $_SESSION["queue"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer Support Queue System</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Customer Support Queue System</h1>

    <form method="POST" action="" class="add-form">
        <input type="text" name="ticket" placeholder="e.g. Login issue - Customer #204">
        <button type="submit" name="add">Add Request</button>
    </form>

    <div class="actions">
        <form method="POST" action="">
            <button type="submit" name="process">Process Next (FIFO)</button>
        </form>
        <form method="POST" action="">
            <button type="submit" name="reset" class="reset-btn">Reset Queue</button>
        </form>
    </div>

    <?php if ($message != "") : ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <h2>Current Queue Status (Front &rarr; Back)</h2>
    <?php if (count($queue) > 0) : ?>
        <ol>
            <?php foreach ($queue as $ticket) : ?>
                <li><?php echo htmlspecialchars($ticket); ?></li>
            <?php endforeach; ?>
        </ol>
    <?php else : ?>
        <p class="empty">Queue is currently empty.</p>
    <?php endif; ?>
    <p class="count">Requests waiting: <?php echo count($queue); ?></p>
</div>

</body>
</html>