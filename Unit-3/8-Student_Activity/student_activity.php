<?php
session_start();
$fileName = "activity_log.txt";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_name'])) {
    $name = trim($_POST['student_name']);
    $activity = trim($_POST['activity']);
    if ($name === "" || $activity === "") {
        $message = "Name and activity are required.";
    } else {
        try {
            $record = date("Y-m-d H:i:s") . "|" . $name . "|" . $activity . "\n";
            file_put_contents($fileName, $record, FILE_APPEND | LOCK_EX);
            $_SESSION['current_student'] = $name;
            $_SESSION['activities'][] = $activity;
            $message = "Activity recorded for $name.";
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}

$summary = [];
if (file_exists($fileName)) {
    $lines = file($fileName, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        $parts = explode("|", $line);
        if (count($parts) === 3) {
            $summary[$parts[1]][] = ["time" => $parts[0], "activity" => $parts[2]];
        }
    }
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Student Activity Report</title>
<style>
body{font-family:Arial;background:#f4f9f9;margin:0;padding:40px;}
.container{max-width:650px;margin:auto;}
h2{color:#117864;}
.card{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px;}
label{font-weight:600;color:#117864;display:block;margin-top:10px;}
input{width:100%;padding:8px;margin-top:5px;box-sizing:border-box;border:1px solid #ccc;border-radius:5px;}
button{margin-top:15px;background:#117864;color:#fff;border:none;padding:10px 16px;border-radius:5px;cursor:pointer;}
.message{color:#1e8449;font-weight:bold;}
.student{margin-bottom:15px;}
.student h4{margin-bottom:5px;color:#0e6251;}
ul{margin:0;padding-left:20px;}
</style></head><body>
<div class="container">
<h2>📊 Student Activity Report System</h2>
<?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<div class="card">
<h3>Log Activity</h3>
<form method="POST" action="student_activity.php">
<label>Student Name</label>
<input type="text" name="student_name" required>
<label>Activity Description</label>
<input type="text" name="activity" required>
<button type="submit">Record Activity</button>
</form>
</div>
<div class="card">
<h3>Activity Summary</h3>
<?php foreach ($summary as $student => $acts): ?>
<div class="student">
<h4><?php echo htmlspecialchars($student); ?> (<?php echo count($acts); ?> activities)</h4>
<ul>
<?php foreach ($acts as $a): ?>
<li><?php echo htmlspecialchars($a['time']) . " - " . htmlspecialchars($a['activity']); ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endforeach; ?>
</div>
</div>
</body></html>