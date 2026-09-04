<?php
session_start();

if (!isset($_SESSION['pages_visited'])) {
    $_SESSION['pages_visited'] = [];
    $_SESSION['session_start'] = date("Y-m-d H:i:s");
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['page_name'])) {
    $page = trim($_POST['page_name']);
    if ($page === "") {
        $message = "Please enter a page name.";
    } else {
        $_SESSION['pages_visited'][] = ["page" => $page, "time" => date("H:i:s")];
        $message = "Visit to '$page' recorded.";
    }
}

if (isset($_GET['reset'])) {
    session_unset();
    header("Location: visitor_session_tracking.php");
    exit();
}

$visitCount = count($_SESSION['pages_visited']);
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Visitor Session Tracking</title>
<style>
body{font-family:Arial;background:#eaf2f8;margin:0;padding:40px;}
.container{max-width:500px;margin:auto;}
h2{color:#1a5276;}
.card{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px;}
label{font-weight:600;color:#1a5276;display:block;margin-top:10px;}
input{width:100%;padding:8px;margin-top:5px;box-sizing:border-box;border:1px solid #ccc;border-radius:5px;}
button{margin-top:15px;background:#2874a6;color:#fff;border:none;padding:10px 16px;border-radius:5px;cursor:pointer;}
.message{color:#1e8449;font-weight:bold;}
.counter{font-size:36px;text-align:center;color:#2874a6;font-weight:bold;}
.visit-item{font-size:13px;padding:4px 0;border-bottom:1px solid #eee;}
.reset{display:block;text-align:center;margin-top:10px;color:#c0392b;text-decoration:none;}
</style></head><body>
<div class="container">
<h2>👣 Visitor Session Tracking System</h2>
<?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<div class="card">
<div class="counter"><?php echo $visitCount; ?></div>
<p style="text-align:center;color:#666;">Pages visited this session (started <?php echo htmlspecialchars($_SESSION['session_start']); ?>)</p>
</div>
<div class="card">
<h3>Record a Page Visit</h3>
<form method="POST" action="visitor_session_tracking.php">
<input type="text" name="page_name" placeholder="e.g. Home, About, Contact" required>
<button type="submit">Track Visit</button>
</form>
</div>
<div class="card">
<h3>Session History</h3>
<?php foreach (array_reverse($_SESSION['pages_visited']) as $v): ?>
<div class="visit-item"><?php echo htmlspecialchars($v['page']) . " — " . htmlspecialchars($v['time']); ?></div>
<?php endforeach; ?>
<a class="reset" href="visitor_session_tracking.php?reset=1">Reset Session</a>
</div>
</div>
</body></html>