<?php
// Patient File Management System - separate files per department
$dataDir = "patient_records";
if (!is_dir($dataDir)) mkdir($dataDir, 0777, true);

$message = "";
$foundRecord = "";

// Add new patient
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $id = trim($_POST['patient_id']);
    $name = trim($_POST['patient_name']);
    $dept = trim($_POST['department']);
    $diagnosis = trim($_POST['diagnosis']);

    if ($id === "" || $name === "" || $dept === "") {
        $message = "Patient ID, Name, and Department are required.";
    } else {
        try {
            $deptFile = $dataDir . "/" . strtolower(str_replace(" ", "_", $dept)) . ".txt";
            $record = "$id|$name|$dept|$diagnosis|" . date("Y-m-d H:i:s") . "\n";
            if (file_put_contents($deptFile, $record, FILE_APPEND | LOCK_EX) === false) {
                throw new Exception("Failed to save record.");
            }
            $message = "Patient record added to $dept department.";
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}

// Search patient by ID across all department files
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'search') {
    $searchId = trim($_POST['search_id']);
    $found = false;
    try {
        $files = glob($dataDir . "/*.txt");
        foreach ($files as $file) {
            $lines = file($file, FILE_IGNORE_NEW_LINES);
            foreach ($lines as $line) {
                $parts = explode("|", $line);
                if (isset($parts[0]) && $parts[0] === $searchId) {
                    $foundRecord = "ID: $parts[0] | Name: $parts[1] | Dept: $parts[2] | Diagnosis: $parts[3] | Recorded: $parts[4]";
                    $found = true;
                    break 2;
                }
            }
        }
        if (!$found) $message = "No record found for Patient ID: $searchId";
    } catch (Exception $e) {
        $message = "Error searching records: " . $e->getMessage();
    }
}

$deptFiles = glob($dataDir . "/*.txt");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Patient File Management</title>
<style>
    body { font-family: Arial, sans-serif; background: #eef3f7; margin:0; padding: 40px; }
    .container { max-width: 650px; margin: auto; }
    h2 { color: #1a5276; }
    .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 20px; }
    input, select { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 5px; }
    label { font-weight: 600; margin-top: 10px; display: block; color: #1a5276;}
    button { margin-top: 15px; background: #2874a6; color: #fff; border: none; padding: 10px 16px; border-radius: 5px; cursor: pointer; }
    .message { color: #c0392b; font-weight: bold; }
    .result { background: #eafaf1; padding: 12px; border-radius: 6px; color: #1e8449; }
    .depts { font-size: 13px; color: #666; }
</style>
</head>
<body>
<div class="container">
    <h2>🏥 Patient File Management System</h2>
    <?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
    <?php if ($foundRecord): ?><div class="result"><?php echo htmlspecialchars($foundRecord); ?></div><?php endif; ?>

    <div class="card">
        <h3>Add Patient Record</h3>
        <form method="POST" action="patient_management.php">
            <input type="hidden" name="action" value="add">
            <label>Patient ID</label>
            <input type="text" name="patient_id" required>
            <label>Patient Name</label>
            <input type="text" name="patient_name" required>
            <label>Department</label>
            <select name="department">
                <option>Cardiology</option>
                <option>Neurology</option>
                <option>Orthopedics</option>
                <option>General</option>
            </select>
            <label>Diagnosis</label>
            <input type="text" name="diagnosis">
            <button type="submit">Save Record</button>
        </form>
    </div>

    <div class="card">
        <h3>Search Patient by ID</h3>
        <form method="POST" action="patient_management.php">
            <input type="hidden" name="action" value="search">
            <input type="text" name="search_id" placeholder="Enter Patient ID" required>
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="card depts">
        <strong>Department files stored:</strong>
        <?php foreach ($deptFiles as $f): echo "<div>" . htmlspecialchars(basename($f)) . "</div>"; endforeach; ?>
    </div>
</div>
</body>
</html>