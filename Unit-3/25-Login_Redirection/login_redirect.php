<?php
session_start();
$message = "";
$validUser = "student";
$validPass = "psgr2026";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        if ($username === "" || $password === "") {
            throw new Exception("Username and password are required.");
        }
        if ($username === $validUser && $password === $validPass) {
            $_SESSION['dashboard_user'] = $username;
            header("Location: login_redirect.php?page=dashboard");
            exit();
        } else {
            throw new Exception("Invalid credentials. Access denied.");
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

$page = $_GET['page'] ?? 'login';
if ($page === 'dashboard' && !isset($_SESSION['dashboard_user'])) {
    header("Location: login_redirect.php?page=login");
    exit();
}
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login_redirect.php");
    exit();
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Login Redirection System</title>
<style>
body{font-family:Arial;background:#e8f8f5;margin:0;padding:40px;}
.container{max-width:450px;margin:auto;background:#fff;padding:30px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.1);}
h2{color:#117864;text-align:center;}
label{display:block;margin-top:15px;font-weight:600;color:#117864;}
input{width:100%;padding:10px;margin-top:5px;border:1px solid #ccc;border-radius:6px;box-sizing:border-box;}
button{margin-top:20px;width:100%;padding:12px;background:#16a085;color:#fff;border:none;border-radius:6px;cursor:pointer;}
.error{color:#c0392b;text-align:center;font-weight:bold;}
.dashboard{text-align:center;}
.dashboard h3{color:#117864;}
</style></head><body>
<div class="container">
<?php if ($page === 'dashboard' && isset($_SESSION['dashboard_user'])): ?>
<div class="dashboard">
<h2>✅ Dashboard</h2>
<h3>Welcome, <?php echo htmlspecialchars($_SESSION['dashboard_user']); ?>!</h3>
<p>You were redirected here after successful authentication via HTTP headers.</p>
<a href="login_redirect.php?logout=1"><button type="button">Logout</button></a>
</div>
<?php else: ?>
<h2>🔁 Login Redirection System</h2>
<?php if ($message): ?><p class="error"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<form method="POST" action="login_redirect.php">
<label>Username</label>
<input type="text" name="username" required>
<label>Password</label>
<input type="password" name="password" required>
<button type="submit">Login</button>
</form>
<p style="font-size:12px;text-align:center;color:#888;">Demo: student / psgr2026</p>
<?php endif; ?>
</div>
</body></html>