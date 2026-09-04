<?php
session_start();
// Online Shopping User Management - Cookies + Sessions
$message = "";

// Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = trim($_POST['username']);
    if ($username === "") {
        $message = "Please enter a username.";
    } else {
        $_SESSION['user'] = $username;
        $_SESSION['login_status'] = true;
        setcookie("last_user", $username, time() + (30 * 24 * 60 * 60), "/");
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if (!isset($_SESSION['history'])) $_SESSION['history'] = [];
    }
}

// Logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header("Location: online_shopping.php");
    exit();
}

$isLoggedIn = isset($_SESSION['login_status']) && $_SESSION['login_status'] === true;

// Add item to cart + browsing history
if ($isLoggedIn && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_item') {
    $item = trim($_POST['item_name']);
    if ($item !== "") {
        $_SESSION['cart'][] = $item;
        $_SESSION['history'][] = $item . " viewed at " . date("H:i:s");
        $message = "$item added to cart.";
    }
}

// Clear cart
if ($isLoggedIn && isset($_GET['clear_cart'])) {
    $_SESSION['cart'] = [];
    header("Location: online_shopping.php");
    exit();
}

$lastUser = $_COOKIE['last_user'] ?? "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Online Shopping Management</title>
<style>
    body { font-family: Arial, sans-serif; background: #eaf2f8; margin: 0; padding: 40px; }
    .container { max-width: 600px; margin: auto; }
    h2 { color: #154360; }
    .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 20px; }
    label { font-weight: 600; color: #154360; display: block; margin-top: 10px; }
    input { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 5px; }
    button { margin-top: 15px; background: #1f618d; color: #fff; border: none; padding: 10px 16px; border-radius: 5px; cursor: pointer; }
    .logout { background: #c0392b; }
    ul { padding-left: 20px; }
    .message { color: #1e8449; font-weight: bold; }
    .status { background: #d4efdf; padding: 10px; border-radius: 6px; color: #196f3d; }
</style>
</head>
<body>
<div class="container">
    <h2>🛒 Online Shopping User Management</h2>
    <?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>

    <?php if (!$isLoggedIn): ?>
        <div class="card">
            <h3>Login</h3>
            <form method="POST" action="online_shopping.php">
                <input type="hidden" name="action" value="login">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($lastUser); ?>" required>
                <button type="submit">Login</button>
            </form>
        </div>
    <?php else: ?>
        <div class="status">
            Logged in as <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong>
            &nbsp; <a href="online_shopping.php?logout=1">Logout</a>
        </div>

        <div class="card">
            <h3>Add Item to Cart</h3>
            <form method="POST" action="online_shopping.php">
                <input type="hidden" name="action" value="add_item">
                <input type="text" name="item_name" placeholder="Item name" required>
                <button type="submit">Add to Cart</button>
            </form>
        </div>

        <div class="card">
            <h3>Shopping Cart (<?php echo count($_SESSION['cart']); ?> items)</h3>
            <ul>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <li><?php echo htmlspecialchars($item); ?></li>
                <?php endforeach; ?>
            </ul>
            <a href="online_shopping.php?clear_cart=1"><button type="button">Clear Cart</button></a>
        </div>

        <div class="card">
            <h3>Browsing History</h3>
            <ul>
                <?php foreach (array_reverse($_SESSION['history']) as $h): ?>
                    <li><?php echo htmlspecialchars($h); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
</body>
</html>