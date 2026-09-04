<?php
session_start();
$docDir = "secure_docs";
if (!is_dir($docDir)) mkdir($docDir, 0777, true);
$message = "";
$isAuthenticated = isset($_SESSION['doc_user']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $user = trim($_POST['username']);
    if ($user === "admin") {
        $_SESSION['doc_user'] = $user;
        $isAuthenticated = true;
    } else {
        $message = "Access denied. Invalid user.";
    }
}
if (isset($_GET['logout'])) { session_destroy(); header("Location: secure_document_management.php"); exit(); }

if ($isAuthenticated && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['secure_file'])) {
    $file = $_FILES['secure_file'];
    try {
        if ($file['error'] !== UPLOAD_ERR_OK) throw new Exception("Upload failed.");
        $hash = md5_file($file['tmp_name']);
        $existingFiles = glob($docDir . "/*");
        foreach ($existingFiles as $ef) {
            if (md5_file($ef) === $hash) throw new Exception("Duplicate file detected — upload rejected.");
        }
        $safeName = preg_replace("/[^a-zA-Z0-9_.-]/", "_", $file['name']);
        $destination = $docDir . "/" . time() . "_" . $safeName;
        if (!move_uploaded_file($file['tmp_name'], $destination)) throw new Exception("Failed to store file securely.");
        chmod($destination, 0644);
        $message = "Document uploaded securely.";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

$files = $isAuthenticated ? glob($docDir . "/*") : [];
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Secure Document Management</title>
<style>
body{font-family:Arial;background:#1b2631;margin:0;padding:40px;color:#ecf0f1;}
.container{max-width:480px;margin:auto;background:#212f3d;padding:30px;border-radius:10px;}
h2{text-align:center;color:#5dade2;}
label{display:block;margin-top:15px;font-weight:600;}
input{width:100%;padding:10px;margin-top:5px;border:1px solid #444;border-radius:6px;box-sizing:border-box;background:#1b2631;color:#fff;}
button{margin-top:20px;width:100%;padding:12px;background:#2e86c1;color:#fff;border:none;border-radius:6px;cursor:pointer;}
.message{text-align:center;font-weight:bold;color:#f7dc6f;}
.file-row{background:#2c3e50;padding:8px;border-radius:5px;margin-top:6px;font-size:13px;}
.logout{color:#e74c3c;text-align:center;display:block;margin-top:10px;}
</style></head><body>
<div class="container">
<h2>🔐 Secure Document Management</h2>
<?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<?php if (!$isAuthenticated): ?>
<form method="POST" action="secure_document_management.php">
<input type="hidden" name="action" value="login">
<label>Username</label>
<input type="text" name="username" required>
<button type="submit">Login</button>
</form>
<p style="font-size:12px;text-align:center;color:#aab7b8;">Demo user: admin</p>
<?php else: ?>
<form method="POST" action="secure_document_management.php" enctype="multipart/form-data">
<label>Upload Secure Document</label>
<input type="file" name="secure_file" required>
<button type="submit">Upload Securely</button>
</form>
<h4>Stored Documents</h4>
<?php foreach ($files as $f): ?><div class="file-row"><?php echo htmlspecialchars(basename($f)); ?></div><?php endforeach; ?>
<a class="logout" href="secure_document_management.php?logout=1">Logout</a>
<?php endif; ?>
</div>
</body></html>