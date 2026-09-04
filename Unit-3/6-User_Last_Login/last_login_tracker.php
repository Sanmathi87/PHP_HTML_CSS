<?php
// User Last Login Tracking Using Cookies
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username']);

    if ($username === "") {
        $message = "Please enter a username.";
    } else {
        // Read previous login time before overwriting
        $previousLogin = $_COOKIE['last_login_' . md5($username)] ?? null;

        // Store current login time in a cookie (expires in 90 days)
        $currentTime = date("Y-m-d H:i:s");
        setcookie("last_login_" . md5($username), $currentTime, time() + (90 * 24 * 60 * 60), "/");
        setcookie("current_user", $username, time() + (90 * 24 * 60 * 60), "/");

        $_SESSION_MSG = $previousLogin
            ? "Welcome back, $username! Your previous login was on $previousLogin."
            : "Welcome, $username! This is your first recorded login.";

        // Store message in a temporary cookie to show after redirect
        setcookie("login_message", $_SESSION_MSG, time() + 30, "/");
        header("Location: last_login_tracker.php");
        exit();
    }
}

$loginMessage = $_COOKIE['login_message'] ?? "";
$currentUser = $_COOKIE['current_user'] ?? "";
if ($currentUser) {
    $lastLogin = $_COOKIE['last_login_' . md5($currentUser)] ?? "N/A";
} else {
    $lastLogin = "N/A";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Last Login Tracker</title>
<style>
    body { font-family: 'Segoe UI', Arial, sans-serif; background: #fef9e7; margin: 0; padding: 40px; }
    .container { max-width: 460px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { color: #b9770e; text-align: center; }
    label { display: block; margin-top: 15px; font-weight: 600; color: #7d6608; }
    input { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; }
    button { margin-top: 20px; width: 100%; padding: 12px; background: #d4ac0d; color: #fff; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; }
    button:hover { background: #b7950b; }
    .info { background: #fcf3cf; padding: 15px; border-radius: 8px; margin-bottom: 20px; color: #7d6608; }
    .error { color: #c0392b; text-align: center; }
</style>
</head>
<body>
<div class="container">
    <h2>🔑 Last Login Tracker</h2>

    <?php if ($loginMessage): ?>
        <div class="info"><?php echo htmlspecialchars($loginMessage); ?></div>
    <?php endif; ?>

    <?php if ($currentUser): ?>
        <div class="info">
            Current User: <strong><?php echo htmlspecialchars($currentUser); ?></strong><br>
            Last Login Timestamp: <strong><?php echo htmlspecialchars($lastLogin); ?></strong><br>
            Server Date/Time Now: <strong><?php echo date("Y-m-d H:i:s"); ?></strong>
        </div>
    <?php endif; ?>

    <?php if ($message): ?><p class="error"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>

    <form method="POST" action="last_login_tracker.php">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>