<?php
$message = "";
$reportFile = "datetime_reports.txt";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_title'])) {
    $title = trim($_POST['report_title']);
    if ($title === "") {
        $message = "Please enter a report title.";
    } else {
        try {
            $entry = date("Y-m-d H:i:s") . " | Report: $title\n";
            file_put_contents($reportFile, $entry, FILE_APPEND | LOCK_EX);
            $message = "Report '$title' generated and logged.";
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}

$now = time();
$formats = [
    "Standard" => date("Y-m-d H:i:s", $now),
    "12-Hour" => date("d M Y, h:i A", $now),
    "Day + Date" => date("l, d F Y", $now),
    "ISO 8601" => date(DATE_ISO8601, $now),
    "Unix Timestamp" => $now,
    "Short" => date("d/m/y", $now),
];

$history = file_exists($reportFile) ? array_slice(file($reportFile, FILE_IGNORE_NEW_LINES), -5) : [];
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Date and Time Report Generator</title>
<style>
body{font-family:Arial;background:#eaf2f8;margin:0;padding:40px;}
.container{max-width:550px;margin:auto;}
h2{color:#1a5276;}
.card{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px;}
table{width:100%;border-collapse:collapse;}
td{padding:8px;border-bottom:1px solid #eee;font-size:14px;}
td:first-child{font-weight:600;color:#1a5276;}
input{width:100%;padding:8px;margin-top:5px;box-sizing:border-box;border:1px solid #ccc;border-radius:5px;}
button{margin-top:15px;background:#2874a6;color:#fff;border:none;padding:10px 16px;border-radius:5px;cursor:pointer;}
.message{color:#1e8449;font-weight:bold;}
.hist{font-size:13px;color:#555;padding:4px 0;border-bottom:1px solid #eee;}
</style></head><body>
<div class="container">
<h2>🕓 Date and Time Report Generator</h2>
<?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<div class="card">
<h3>Current Date & Time (Multiple Formats)</h3>
<table>
<?php foreach ($formats as $label => $value): ?>
<tr><td><?php echo htmlspecialchars($label); ?></td><td><?php echo htmlspecialchars($value); ?></td></tr>
<?php endforeach; ?>
</table>
</div>
<div class="card">
<h3>Generate & Log Report</h3>
<form method="POST" action="datetime_report.php">
<input type="text" name="report_title" placeholder="Report title" required>
<button type="submit">Generate Report</button>
</form>
</div>
<div class="card">
<h3>Recent Report Log</h3>
<?php foreach ($history as $h): ?><div class="hist"><?php echo htmlspecialchars($h); ?></div><?php endforeach; ?>
</div>
</div>
</body></html>