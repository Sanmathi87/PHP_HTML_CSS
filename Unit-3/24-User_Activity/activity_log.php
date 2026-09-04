<?php
// Task 24: User Activity and File Access Log System
session_start();
$logFile = "activity_logs.txt";

if (isset($_POST['login'])) {
    $user = htmlspecialchars($_POST['username']);
    $_SESSION['log_user'] = $user;
    setcookie("last_active_user", $user, time() + (86400 * 7), "/");
    file_put_contents($logFile, date("Y-m-d H:i:s") . "|$user|LOGIN" . PHP_EOL, FILE_APPEND | LOCK_EX);
}

if (isset($_POST['access_file'])) {
    $user = $_SESSION['log_user'] ?? 'Guest';
    $file = htmlspecialchars($_POST['file_name']);
    file_put_contents($logFile, date("Y-m-d H:i:s") . "|$user|ACCESSED: $file" . PHP_EOL, FILE_APPEND | LOCK_EX);
}

$logs = file_exists($logFile) ? array_reverse(file($logFile, FILE_IGNORE_NEW_LINES)) : [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Activity and Access Log</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>User Activity and File Access Log System</h2>

        <?php if (!isset($_SESSION['log_user'])): ?>
            <form method="POST" action="">
                <label>Username:</label>
                <input type="text" name="username" value="<?php echo $_COOKIE['last_active_user'] ?? ''; ?>" required>
                <button type="submit" name="login">Login</button>
            </form>
        <?php else: ?>
            <div class="message success">Logged in as <strong><?php echo $_SESSION['log_user']; ?></strong></div>
            <form method="POST" action="">
                <label>File Accessed:</label>
                <input type="text" name="file_name" placeholder="e.g. report.pdf" required>
                <button type="submit" name="access_file">Log Access</button>
            </form>
        <?php endif; ?>

        <h3>Activity Report (Most Recent First)</h3>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr><th>Timestamp</th><th>User</th><th>Action</th></tr>
            <?php foreach ($logs as $l): $f = explode("|", $l); ?>
                <tr><td><?php echo $f[0]; ?></td><td><?php echo $f[1]; ?></td><td><?php echo $f[2]; ?></td></tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
