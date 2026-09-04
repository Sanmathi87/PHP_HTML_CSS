<?php
session_start();
$message = "";
$validUser = "admin";
$validPass = "password123";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $remember = isset($_POST['remember']);

    try {
        if ($username === "" || $password === "") {
            throw new Exception("Username and password are required.");
        }
        if ($username === $validUser && $password === $validPass) {
            session_regenerate_id(true);
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['login_time'] = date("Y-m-d H:i:s");

            if ($remember) {
                $token = bin2hex(random_bytes(16));
                setcookie("remember_token", $token, time() + (7 * 24 * 60 * 60), "/", "", false, true);
                setcookie("remember_user", $username, time() + (7 * 24 * 60 * 60), "/", "", false, true);
            }
            $message = "Login successful.";
        } else {
            throw new Exception("Invalid username or password.");
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    setcookie("remember_token", "", time() - 3600, "/");
    setcookie("remember_user", "", time() - 3600, "/");
    header("Location: secure_login.php");
    exit();
}

$isAuthenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
$rememberedUser = $_COOKIE['remember_user'] ?? "";
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Secure Session Management</title>
<style>
body{font-family:Arial;background:#1c2833;margin:0;padding:40px;color:#eaeded;}
.container{max-width:450px;margin:auto;background:#273746;padding:30px;border-radius:10px;}
h2{text-align:center;color:#58d68d;}
label{display:block;margin-top:15px;font-weight:600;}
input{width:100%;padding:10px;margin-top:5px;border:1px solid #444;border-radius:6px;box-sizing:border-box;background:#1c2833;color:#fff;}
button{margin-top:20px;width:100%;padding:12px;background:#28b463;color:#fff;border:none;border-radius:6px;cursor:pointer;}
.logout-btn{background:#cb4335;}
.message{text-align:center;font-weight:bold;color:#f4d03f;}
.status{background:#154360;padding:15px;border-radius:8px;text-align:center;}
.remember-row{display:flex;align-items:center;gap:8px;margin-top:10px;}
.remember-row input{width:auto;}
</style></head><body>
<div class="container">
<h2>🔒 Secure Session Management</h2>
<?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<?php if ($isAuthenticated): ?>
<div class="status">
Logged in as <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong><br>
Session started: <?php echo htmlspecialchars($_SESSION['login_time']); ?><br>
<a href="secure_login.php?logout=1"><button class="logout-btn" type="button">Logout</button></a>
</div>
<?php else: ?>
<form method="POST" action="secure_login.php">
<input type="hidden" name="action" value="login">
<label>Username</label>
<input type="text" name="username" value="<?php echo htmlspecialchars($rememberedUser); ?>" required>
<label>Password</label>
<input type="password" name="password" required>
<div class="remember-row"><input type="checkbox" name="remember" id="remember"><label for="remember" style="margin:0;">Remember me (cookie-based)</label></div>
<button type="submit">Login</button>
</form>
<p style="font-size:12px;color:#aab7b8;text-align:center;">Demo credentials: admin / password123</p>
<?php endif; ?>
</div>
</body></html>