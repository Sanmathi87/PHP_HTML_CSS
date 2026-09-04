<?php
// Assignment Submission and File Validation
$baseDir = "uploads";
$allowedTypes = ["pdf", "doc", "docx", "zip"];
$maxSize = 5 * 1024 * 1024; // 5 MB
$message = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['assignment_file'])) {
    $studentName = trim($_POST['student_name']);
    $department = trim($_POST['department']);
    $file = $_FILES['assignment_file'];

    try {
        if ($studentName === "" || $department === "") {
            throw new Exception("Student name and department are required.");
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("File upload error occurred.");
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedTypes)) {
            throw new Exception("Invalid file type. Allowed: " . implode(", ", $allowedTypes));
        }
        if ($file['size'] > $maxSize) {
            throw new Exception("File exceeds maximum size of 5 MB.");
        }

        $deptDir = $baseDir . "/" . strtolower(str_replace(" ", "_", $department));
        if (!is_dir($deptDir)) {
            mkdir($deptDir, 0777, true);
        }

        $safeName = preg_replace("/[^a-zA-Z0-9_.-]/", "_", $studentName);
        $destination = $deptDir . "/" . $safeName . "_" . time() . "." . $ext;

        // Prevent duplicate upload (same student, same dept, same day)
        $duplicatePattern = $deptDir . "/" . $safeName . "_*." . $ext;
        $existing = glob($duplicatePattern);
        if (count($existing) > 0) {
            throw new Exception("A submission from this student already exists in this department today.");
        }

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("Failed to move uploaded file.");
        }

        $message = "Assignment uploaded successfully to $department directory.";
        $success = true;
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Assignment Upload</title>
<style>
    body { font-family: Arial, sans-serif; background: #f5f0fa; margin: 0; padding: 40px; }
    .container { max-width: 480px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { color: #6c3483; text-align: center; }
    label { display: block; margin-top: 15px; font-weight: 600; color: #4a235a; }
    input, select { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; }
    button { margin-top: 20px; width: 100%; padding: 12px; background: #8e44ad; color: #fff; border: none; border-radius: 6px; font-size: 16px; cursor: pointer; }
    .success { color: #1e8449; text-align: center; font-weight: bold; }
    .error { color: #c0392b; text-align: center; font-weight: bold; }
    .hint { font-size: 12px; color: #888; margin-top: 5px; }
</style>
</head>
<body>
<div class="container">
    <h2>📎 Assignment Submission</h2>
    <?php if ($message): ?>
        <p class="<?php echo $success ? 'success' : 'error'; ?>"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="POST" action="assignment_upload.php" enctype="multipart/form-data">
        <label>Student Name</label>
        <input type="text" name="student_name" required>

        <label>Department</label>
        <select name="department">
            <option>Computer Science</option>
            <option>Electronics</option>
            <option>Mechanical</option>
            <option>Commerce</option>
        </select>

        <label>Assignment File</label>
        <input type="file" name="assignment_file" required>
        <div class="hint">Allowed: PDF, DOC, DOCX, ZIP (Max 5MB)</div>

        <button type="submit">Upload Assignment</button>
    </form>
</div>
</body>
</html>