<?php
$baseDir = "media_files";
if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['media_file'])) {
    $file = $_FILES['media_file'];
    $imageTypes = ["jpg", "jpeg", "png", "gif"];
    $videoTypes = ["mp4", "avi", "mov"];

    try {
        if ($file['error'] !== UPLOAD_ERR_OK) throw new Exception("Upload failed.");
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $imageTypes)) {
            $category = "images";
        } elseif (in_array($ext, $videoTypes)) {
            $category = "videos";
        } else {
            throw new Exception("Unsupported file type: .$ext");
        }

        $categoryDir = $baseDir . "/" . $category;
        if (!is_dir($categoryDir)) mkdir($categoryDir, 0777, true);

        $destination = $categoryDir . "/" . basename($file['name']);
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("Failed to store file.");
        }
        $message = "File uploaded and categorized under '$category'.";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

$searchTerm = $_GET['search'] ?? '';
$images = glob($baseDir . "/images/*");
$videos = glob($baseDir . "/videos/*");

if ($searchTerm !== '') {
    $images = array_filter($images, fn($f) => stripos(basename($f), $searchTerm) !== false);
    $videos = array_filter($videos, fn($f) => stripos(basename($f), $searchTerm) !== false);
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Multimedia File Management</title>
<style>
body{font-family:Arial;background:#fdf2e9;margin:0;padding:40px;}
.container{max-width:600px;margin:auto;}
h2{color:#a04000;}
.card{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px;}
input{width:100%;padding:8px;margin-top:5px;box-sizing:border-box;border:1px solid #ccc;border-radius:5px;}
button{margin-top:15px;background:#ca6f1e;color:#fff;border:none;padding:10px 16px;border-radius:5px;cursor:pointer;}
.message{color:#1e8449;font-weight:bold;}
.file-item{padding:5px 0;border-bottom:1px solid #eee;font-size:14px;}
</style></head><body>
<div class="container">
<h2>🎬 Multimedia File Management System</h2>
<?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<div class="card">
<h3>Upload Media File</h3>
<form method="POST" action="multimedia_management.php" enctype="multipart/form-data">
<input type="file" name="media_file" required>
<button type="submit">Upload</button>
</form>
</div>
<div class="card">
<h3>Search Files</h3>
<form method="GET" action="multimedia_management.php">
<input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Search by filename">
<button type="submit">Search</button>
</form>
</div>
<div class="card">
<h3>Images (<?php echo count($images); ?>)</h3>
<?php foreach ($images as $img): ?><div class="file-item"><?php echo htmlspecialchars(basename($img)); ?></div><?php endforeach; ?>
<h3>Videos (<?php echo count($videos); ?>)</h3>
<?php foreach ($videos as $vid): ?><div class="file-item"><?php echo htmlspecialchars(basename($vid)); ?></div><?php endforeach; ?>
</div>
</div>
</body></html>