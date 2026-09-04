<?php
session_start();
$message = "";
$validStudents = ["24SBCS087" => "Ammu", "24SBCS001" => "Divya"];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $studentId = trim($_POST['student_id']);
    $accessCode = trim($_POST['access_code']);

    try {
        if ($studentId === "" || $accessCode === "") {
            throw new Exception("Student ID and access code are required.");
        }
        if (!array_key_exists($studentId, $validStudents)) {
            throw new Exception("Student ID not recognized. Access denied.");
        }
        if ($accessCode !== "EXAM2026") {
            throw new Exception("Invalid access code.");
        }
        $_SESSION['exam_student'] = $studentId;
        setcookie("exam_last_id", $studentId, time() + (24 * 60 * 60), "/");
        header("Location: exam_access_control.php?access=granted");
        exit();
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: exam_access_control.php");
    exit();
}

$accessGranted = isset($_SESSION['exam_student']);
if (isset($_GET['access']) && $_GET['access'] === 'granted' && !$accessGranted) {
    header("Location: exam_access_control.php");
    exit();
}
$lastId = $_COOKIE['exam_last_id'] ?? "";
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Secure Examination Access Control</title>
<style>
body{font-family:Arial;background:#17202a;margin:0;padding:40px;color:#eaeded;}
.container{max-width:450px;margin:auto;background:#212f3d;padding:30px;border-radius:10px;}
h2{text-align:center;color:#f1c40f;}
label{display:block;margin-top:15px;font-weight:600;}
input{width:100%;padding:10px;margin-top:5px;border:1px solid #444;border-radius:6px;box-sizing:border-box;background:#17202a;color:#fff;}
button{margin-top:20px;width:100%;padding:12px;background:#d4ac0d;color:#1c2833;border:none;border-radius:6px;font-weight:bold;cursor:pointer;}
.error{color:#e74c3c;text-align:center;font-weight:bold;}
.granted{text-align:center;}
.granted h3{color:#58d68d;}
</style></head><body>
<div class="container">
<?php if ($accessGranted): ?>
<div class="granted">
<h2>🎓 Examination Portal</h2>
<h3>Access Granted</h3>
<p>Welcome, <?php echo htmlspecialchars($validStudents[$_SESSION['exam_student']]); ?> (<?php echo htmlspecialchars($_SESSION['exam_student']); ?>)</p>
<p>You may now proceed to the examination.</p>
<a href="exam_access_control.php?logout=1"><button type="button">Logout</button></a>
</div>
<?php else: ?>
<h2>🔐 Secure Examination Access</h2>
<?php if ($message): ?><p class="error"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<form method="POST" action="exam_access_control.php">
<input type="hidden" name="action" value="login">
<label>Student ID</label>
<input type="text" name="student_id" value="<?php echo htmlspecialchars($lastId); ?>" required>
<label>Access Code</label>
<input type="password" name="access_code" required>
<button type="submit">Enter Examination</button>
</form>
<p style="font-size:12px;text-align:center;color:#aab7b8;">Demo: ID 24SBCS087, code EXAM2026</p>
<?php endif; ?>
</div>
</body></html>