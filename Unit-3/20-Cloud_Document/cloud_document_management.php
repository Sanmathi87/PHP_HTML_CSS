<?php
$baseDir = "cloud_docs";
$categories = ["Personal", "Work", "Shared"];
foreach ($categories as $c) {
    if (!is_dir($baseDir . "/" . $c)) mkdir($baseDir . "/" . $c, 0777, true);
}
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['doc_file'])) {
    $file = $_FILES['doc_file'];
    $category = in_array($_POST['category'], $categories) ? $_POST['category'] : "Personal";
    try {
        if ($file['error'] !== UPLOAD_ERR_OK) throw new Exception("Upload failed.");
        $destination = $baseDir . "/" . $category . "/" . basename($file['name']);
        if (file_exists($destination)) throw new Exception("A file with this name already exists in $category.");
        if (!move_uploaded_file($file['tmp_name'], $destination)) throw new Exception("Failed to store document.");
        $message = "Document uploaded to '$category' folder.";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

if (isset($_GET['delete']) && isset($_GET['category'])) {
    $delFile = $baseDir . "/" . $_GET['category'] . "/" . basename($_GET['delete']);
    try {
        if (!file_exists($delFile)) throw new Exception("File not found.");
        if (!unlink($delFile)) throw new Exception("Failed to delete file.");
        $message = "Document deleted successfully.";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Cloud Document Management</title>
<style>
body{font-family:Arial;background:#eaf2f8;margin:0;padding:40px;}
.container{max-width:600px;margin:auto;}
h2{color:#21618c;}
.card{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px;}
select,input{width:100%;padding:8px;margin-top:5px;box-sizing:border-box;border:1px solid #ccc;border-radius:5px;}
button{margin-top:15px;background:#2874a6;color:#fff;border:none;padding:10px 16px;border-radius:5px;cursor:pointer;}
.message{color:#1e8449;font-weight:bold;}
.file-row{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #eee;font-size:14px;}
.file-row a{color:#c0392b;text-decoration:none;}
.cat-title{color:#21618c;margin-top:15px;}
</style></head><body>
<div class="container">
<h2>☁️ Cloud Document Directory Management</h2>
<?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<div class="card">
<h3>Upload Document</h3>
<form method="POST" action="cloud_document_management.php" enctype="multipart/form-data">
<label>Category</label>
<select name="category"><?php foreach ($categories as $c) echo "<option>$c</option>"; ?></select>
<input type="file" name="doc_file" required>
<button type="submit">Upload</button>
</form>
</div>
<div class="card">
<h3>Stored Documents</h3>
<?php foreach ($categories as $c): $files = glob($baseDir . "/" . $c . "/*"); ?>
<h4 class="cat-title"><?php echo htmlspecialchars($c); ?> (<?php echo count($files); ?>)</h4>
<?php foreach ($files as $f): ?>
<div class="file-row"><span><?php echo htmlspecialchars(basename($f)); ?></span>
<a href="?delete=<?php echo urlencode(basename($f)); ?>&category=<?php echo urlencode($c); ?>" onclick="return confirm('Delete this file?');">Delete</a></div>
<?php endforeach; ?>
<?php endforeach; ?>
</div>
</div>
</body></html>