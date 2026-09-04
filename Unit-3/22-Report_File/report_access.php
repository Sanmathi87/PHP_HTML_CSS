<?php
$baseDir = "reports";
$folders = ["Sales", "HR", "Finance"];
foreach ($folders as $f) {
    if (!is_dir($baseDir . "/" . $f)) mkdir($baseDir . "/" . $f, 0777, true);
}
$message = "";

// seed a sample file if empty
foreach ($folders as $f) {
    $sample = $baseDir . "/" . $f . "/sample_" . strtolower($f) . "_report.txt";
    if (count(glob($baseDir . "/" . $f . "/*")) === 0) {
        file_put_contents($sample, "$f Report generated on " . date("Y-m-d"));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['report_file'])) {
    $folder = in_array($_POST['folder'], $folders) ? $_POST['folder'] : "Sales";
    $file = $_FILES['report_file'];
    try {
        if ($file['error'] !== UPLOAD_ERR_OK) throw new Exception("Upload failed.");
        $destination = $baseDir . "/" . $folder . "/" . basename($file['name']);
        if (!move_uploaded_file($file['tmp_name'], $destination)) throw new Exception("Failed to store file.");
        $message = "Report uploaded to $folder.";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

$selectedFolder = $_GET['folder'] ?? "Sales";
if (!in_array($selectedFolder, $folders)) $selectedFolder = "Sales";
$files = glob($baseDir . "/" . $selectedFolder . "/*");

$viewFile = $_GET['view'] ?? '';
$viewContent = "";
if ($viewFile !== '') {
    $path = $baseDir . "/" . $selectedFolder . "/" . basename($viewFile);
    try {
        if (!file_exists($path)) throw new Exception("File not found.");
        $viewContent = file_get_contents($path);
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Report File Access System</title>
<style>
body{font-family:Arial;background:#f4f6f6;margin:0;padding:40px;}
.container{max-width:600px;margin:auto;}
h2{color:#1b2631;}
.card{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px;}
select,input{width:100%;padding:8px;margin-top:5px;box-sizing:border-box;border:1px solid #ccc;border-radius:5px;}
button{margin-top:15px;background:#34495e;color:#fff;border:none;padding:10px 16px;border-radius:5px;cursor:pointer;}
.message{color:#1e8449;font-weight:bold;}
.file-row{padding:6px 0;border-bottom:1px solid #eee;font-size:14px;}
.file-row a{color:#2874a6;text-decoration:none;margin-left:10px;}
.viewer{background:#f8f9f9;padding:10px;border-radius:5px;white-space:pre-wrap;font-size:13px;}
</style></head><body>
<div class="container">
<h2>🗂️ Report File Access System</h2>
<?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<div class="card">
<h3>Upload Report</h3>
<form method="POST" action="report_access.php" enctype="multipart/form-data">
<label>Folder</label>
<select name="folder"><?php foreach ($folders as $f) echo "<option>$f</option>"; ?></select>
<input type="file" name="report_file" required>
<button type="submit">Upload</button>
</form>
</div>
<div class="card">
<h3>Browse Reports</h3>
<form method="GET" action="report_access.php">
<select name="folder" onchange="this.form.submit()"><?php foreach ($folders as $f) echo "<option " . ($f === $selectedFolder ? "selected" : "") . ">$f</option>"; ?></select>
</form>
<?php foreach ($files as $f): ?>
<div class="file-row"><?php echo htmlspecialchars(basename($f)); ?>
<a href="?folder=<?php echo urlencode($selectedFolder); ?>&view=<?php echo urlencode(basename($f)); ?>">View</a></div>
<?php endforeach; ?>
</div>
<?php if ($viewContent): ?>
<div class="card">
<h3>Viewing: <?php echo htmlspecialchars($viewFile); ?></h3>
<div class="viewer"><?php echo htmlspecialchars($viewContent); ?></div>
</div>
<?php endif; ?>
</div>
</body></html>