<?php
session_start();
$recordDir = "medical_records";
if (!is_dir($recordDir)) mkdir($recordDir, 0777, true);
$message = "";
$isDoctor = isset($_SESSION['doctor_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $doctorId = trim($_POST['doctor_id']);
    if ($doctorId === "") {
        $message = "Doctor ID is required.";
    } else {
        session_regenerate_id(true);
        $_SESSION['doctor_id'] = $doctorId;
        $isDoctor = true;
        $message = "Logged in as Dr. $doctorId.";
    }
}
if (isset($_GET['logout'])) { session_destroy(); header("Location: medical_record_management.php"); exit(); }

if ($isDoctor && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_record') {
    $patientId = trim($_POST['patient_id']);
    $diagnosis = trim($_POST['diagnosis']);
    try {
        if ($patientId === "" || $diagnosis === "") throw new Exception("Patient ID and diagnosis are required.");
        $safeId = preg_replace("/[^a-zA-Z0-9_-]/", "_", $patientId);
        $file = $recordDir . "/" . $safeId . ".txt";
        $entry = date("Y-m-d H:i:s") . " | Dr. " . $_SESSION['doctor_id'] . " | $diagnosis\n";
        if (file_put_contents($file, $entry, FILE_APPEND | LOCK_EX) === false) {
            throw new Exception("Failed to save medical record.");
        }
        chmod($file, 0600);
        $message = "Record added for patient $patientId.";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

$viewRecord = "";
if ($isDoctor && isset($_GET['view_patient'])) {
    $safeId = preg_replace("/[^a-zA-Z0-9_-]/", "_", $_GET['view_patient']);
    $file = $recordDir . "/" . $safeId . ".txt";
    try {
        if (!file_exists($file)) throw new Exception("No records found for this patient.");
        $viewRecord = file_get_contents($file);
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Secure Medical Record Management</title>
<style>
body{font-family:Arial;background:#1b2631;margin:0;padding:40px;color:#ecf0f1;}
.container{max-width:500px;margin:auto;background:#212f3d;padding:30px;border-radius:10px;}
h2{text-align:center;color:#48c9b0;}
label{display:block;margin-top:15px;font-weight:600;}
input{width:100%;padding:10px;margin-top:5px;border:1px solid #444;border-radius:6px;box-sizing:border-box;background:#1b2631;color:#fff;}
button{margin-top:15px;width:100%;padding:12px;background:#17a589;color:#fff;border:none;border-radius:6px;cursor:pointer;}
.message{text-align:center;font-weight:bold;color:#f4d03f;}
.viewer{background:#2c3e50;padding:10px;border-radius:5px;white-space:pre-wrap;font-size:13px;margin-top:10px;}
.logout{color:#e74c3c;text-align:center;display:block;margin-top:10px;}
</style></head><body>
<div class="container">
<h2>🏥 Secure Medical Record Management</h2>
<?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<?php if (!$isDoctor): ?>
<form method="POST" action="medical_record_management.php">
<input type="hidden" name="action" value="login">
<label>Doctor ID</label>
<input type="text" name="doctor_id" required>
<button type="submit">Login</button>
</form>
<?php else: ?>
<form method="POST" action="medical_record_management.php">
<input type="hidden" name="action" value="add_record">
<label>Patient ID</label>
<input type="text" name="patient_id" required>
<label>Diagnosis / Notes</label>
<input type="text" name="diagnosis" required>
<button type="submit">Add Record</button>
</form>
<form method="GET" action="medical_record_management.php" style="margin-top:15px;">
<label>View Records for Patient ID</label>
<input type="text" name="view_patient" required>
<button type="submit">View Records</button>
</form>
<?php if ($viewRecord): ?><div class="viewer"><?php echo htmlspecialchars($viewRecord); ?></div><?php endif; ?>
<a class="logout" href="medical_record_management.php?logout=1">Logout</a>
<?php endif; ?>
</div>
</body></html>