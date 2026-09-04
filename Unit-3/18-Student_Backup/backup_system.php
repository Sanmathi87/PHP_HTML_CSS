<?php
$dataFile = "student_data.txt";
$backupDir = "backups";
if (!is_dir($backupDir)) mkdir($backupDir, 0777, true);
$message = "";

if (!file_exists($dataFile)) {
    file_put_contents($dataFile, "24SBCS001|Ammu|CS\n24SBCS002|Divya|IT\n24SBCS003|Kavya|ECE\n");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === 'add_student') {
            $regNo = trim($_POST['reg_no']);
            $name = trim($_POST['student_name']);
            $dept = trim($_POST['dept']);
            if ($regNo === "" || $name === "") throw new Exception("Register number and name are required.");
            file_put_contents($dataFile, "$regNo|$name|$dept\n", FILE_APPEND | LOCK_EX);
            $message = "Student record added.";
        } elseif ($_POST['action'] === 'backup') {
            if (!file_exists($dataFile)) throw new Exception("No data file to back up.");
            $timestamp = date("Ymd_His");
            $backupFile = $backupDir . "/backup_" . $timestamp . ".txt";
            if (!copy($dataFile, $backupFile)) throw new Exception("Backup failed.");
            $logEntry = "Backup created: $backupFile at " . date("Y-m-d H:i:s") . "\n";
            file_put_contents($backupDir . "/backup_log.txt", $logEntry, FILE_APPEND | LOCK_EX);
            $message = "Backup created successfully: backup_$timestamp.txt";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

$students = file_exists($dataFile) ? file($dataFile, FILE_IGNORE_NEW_LINES) : [];
$backups = glob($backupDir . "/backup_*.txt");
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Student Records Backup</title>
<style>
body{font-family:Arial;background:#f4ecf7;margin:0;padding:40px;}
.container{max-width:600px;margin:auto;}
h2{color:#6c3483;}
.card{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px;}
label{font-weight:600;color:#6c3483;display:block;margin-top:10px;}
input{width:100%;padding:8px;margin-top:5px;box-sizing:border-box;border:1px solid #ccc;border-radius:5px;}
button{margin-top:15px;background:#8e44ad;color:#fff;border:none;padding:10px 16px;border-radius:5px;cursor:pointer;}
table{width:100%;border-collapse:collapse;}
td{padding:6px;border-bottom:1px solid #eee;font-size:14px;}
.message{color:#1e8449;font-weight:bold;}
.backup-item{font-size:13px;color:#666;padding:4px 0;}
</style></head><body>
<div class="container">
<h2>💾 Student Records Backup System</h2>
<?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<div class="card">
<h3>Add Student Record</h3>
<form method="POST" action="backup_system.php">
<input type="hidden" name="action" value="add_student">
<label>Register No</label><input type="text" name="reg_no" required>
<label>Name</label><input type="text" name="student_name" required>
<label>Department</label><input type="text" name="dept" required>
<button type="submit">Add Record</button>
</form>
</div>
<div class="card">
<h3>Current Records (<?php echo count($students); ?>)</h3>
<table><?php foreach ($students as $s): $p = explode("|", $s); ?>
<tr><td><?php echo htmlspecialchars($p[0] ?? ''); ?></td><td><?php echo htmlspecialchars($p[1] ?? ''); ?></td><td><?php echo htmlspecialchars($p[2] ?? ''); ?></td></tr>
<?php endforeach; ?></table>
</div>
<div class="card">
<h3>Create Backup</h3>
<form method="POST" action="backup_system.php">
<input type="hidden" name="action" value="backup">
<button type="submit">Backup Now</button>
</form>
<h4>Backup History</h4>
<?php foreach ($backups as $b): ?><div class="backup-item"><?php echo htmlspecialchars(basename($b)) . " — " . date("Y-m-d H:i:s", filemtime($b)); ?></div><?php endforeach; ?>
</div>
</div>
</body></html>