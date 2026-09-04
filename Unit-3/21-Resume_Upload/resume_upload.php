<?php
$uploadDir = "resumes";
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
$allowedTypes = ["pdf", "doc", "docx"];
$maxSize = 2 * 1024 * 1024; // 2MB
$message = "";
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['resume'])) {
    $applicantName = trim($_POST['applicant_name']);
    $file = $_FILES['resume'];

    try {
        if ($applicantName === "") throw new Exception("Applicant name is required.");
        if ($file['error'] !== UPLOAD_ERR_OK) throw new Exception("File upload error.");

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedTypes)) {
            throw new Exception("Invalid file type '.$ext'. Only PDF, DOC, DOCX are accepted.");
        }
        if ($file['size'] > $maxSize) {
            throw new Exception("File size exceeds the 2MB limit.");
        }
        // Basic MIME check for extra validation
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        $allowedMimes = ["application/pdf", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"];
        if (!in_array($mime, $allowedMimes)) {
            throw new Exception("File content does not match an accepted document type.");
        }

        $safeName = preg_replace("/[^a-zA-Z0-9_-]/", "_", $applicantName);
        $destination = $uploadDir . "/" . $safeName . "_" . time() . "." . $ext;
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("Failed to save resume.");
        }
        $message = "Resume uploaded successfully for $applicantName.";
        $success = true;
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Resume Upload Validation</title>
<style>
body{font-family:Arial;background:#eaeded;margin:0;padding:40px;}
.container{max-width:460px;margin:auto;background:#fff;padding:30px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.1);}
h2{color:#154360;text-align:center;}
label{display:block;margin-top:15px;font-weight:600;color:#154360;}
input{width:100%;padding:10px;margin-top:5px;border:1px solid #ccc;border-radius:6px;box-sizing:border-box;}
button{margin-top:20px;width:100%;padding:12px;background:#2874a6;color:#fff;border:none;border-radius:6px;font-size:16px;cursor:pointer;}
.success{color:#1e8449;text-align:center;font-weight:bold;}
.error{color:#c0392b;text-align:center;font-weight:bold;}
.hint{font-size:12px;color:#888;margin-top:5px;}
</style></head><body>
<div class="container">
<h2>📄 Resume Upload Validation</h2>
<?php if ($message): ?><p class="<?php echo $success ? 'success' : 'error'; ?>"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<form method="POST" action="resume_upload.php" enctype="multipart/form-data">
<label>Applicant Name</label>
<input type="text" name="applicant_name" required>
<label>Resume File</label>
<input type="file" name="resume" required>
<div class="hint">Accepted: PDF, DOC, DOCX (Max 2MB)</div>
<button type="submit">Upload Resume</button>
</form>
</div>
</body></html>