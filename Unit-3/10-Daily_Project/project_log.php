<?php
$logDir = "project_logs";
if (!is_dir($logDir)) mkdir($logDir, 0777, true);
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_name'])) {
    $project = trim($_POST['project_name']);
    $update = trim($_POST['update_text']);

    if ($project === "" || $update === "") {
        $message = "Project name and update are required.";
    } else {
        try {
            $today = date("Y-m-d");
            $logFile = $logDir . "/log_" . $today . ".txt";
            $entry = "[" . date("H:i:s") . "] Project: $project - $update\n";
            if (file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX) === false) {
                throw new Exception("Failed to write log entry.");
            }
            $message = "Log entry added to today's file: log_$today.txt";
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}

$todayLog = $logDir . "/log_" . date("Y-m-d") . ".txt";
$entries = file_exists($todayLog) ? file($todayLog, FILE_IGNORE_NEW_LINES) : [];
$allLogFiles = glob($logDir . "/*.txt");
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Daily Project Log Generator</title>
<style>
body{font-family:Arial;background:#f4f6f7;margin:0;padding:40px;}
.container{max-width:600px;margin:auto;}
h2{color:#7d3c98;}
.card{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px;}
label{font-weight:600;color:#6c3483;display:block;margin-top:10px;}
input,textarea{width:100%;padding:8px;margin-top:5px;box-sizing:border-box;border:1px solid #ccc;border-radius:5px;}
button{margin-top:15px;background:#8e44ad;color:#fff;border:none;padding:10px 16px;border-radius:5px;cursor:pointer;}
.entry{padding:6px 0;border-bottom:1px solid #eee;font-size:14px;}
.message{color:#1e8449;font-weight:bold;}
.files{font-size:13px;color:#666;}
</style></head><body>
<div class="container">
<h2>📅 Daily Project Log Generator</h2>
<?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<div class="card">
<h3>Add Log Entry</h3>
<form method="POST" action="project_log.php">
<label>Project Name</label>
<input type="text" name="project_name" required>
<label>Update</label>
<textarea name="update_text" rows="3" required></textarea>
<button type="submit">Add Entry</button>
</form>
</div>
<div class="card">
<h3>Today's Log (<?php echo date("Y-m-d"); ?>)</h3>
<?php foreach ($entries as $e): ?>
<div class="entry"><?php echo htmlspecialchars($e); ?></div>
<?php endforeach; ?>
</div>
<div class="card files">
<strong>All log files:</strong>
<?php foreach ($allLogFiles as $f): ?>
<div><?php echo htmlspecialchars(basename($f)); ?></div>
<?php endforeach; ?>
</div>
</div>
</body></html>