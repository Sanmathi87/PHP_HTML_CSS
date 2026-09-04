<?php
$baseDir = "department_docs";
if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    try {
        if ($action === 'create') {
            $folderName = trim($_POST['folder_name']);
            if ($folderName === "") throw new Exception("Folder name is required.");
            $path = $baseDir . "/" . preg_replace("/[^a-zA-Z0-9_-]/", "_", $folderName);
            if (is_dir($path)) throw new Exception("Folder already exists.");
            if (!mkdir($path)) throw new Exception("Failed to create folder.");
            $message = "Folder '$folderName' created successfully.";
        } elseif ($action === 'rename') {
            $oldName = trim($_POST['old_name']);
            $newName = trim($_POST['new_name']);
            $oldPath = $baseDir . "/" . $oldName;
            $newPath = $baseDir . "/" . preg_replace("/[^a-zA-Z0-9_-]/", "_", $newName);
            if (!is_dir($oldPath)) throw new Exception("Source folder does not exist.");
            if (is_dir($newPath)) throw new Exception("A folder with the new name already exists.");
            if (!rename($oldPath, $newPath)) throw new Exception("Failed to rename folder.");
            $message = "Folder renamed to '$newName'.";
        } elseif ($action === 'delete') {
            $folderName = trim($_POST['delete_name']);
            $path = $baseDir . "/" . $folderName;
            if (!is_dir($path)) throw new Exception("Folder does not exist.");
            if (count(scandir($path)) > 2) throw new Exception("Folder is not empty. Cannot delete.");
            if (!rmdir($path)) throw new Exception("Failed to delete folder.");
            $message = "Folder '$folderName' deleted successfully.";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

$folders = glob($baseDir . "/*", GLOB_ONLYDIR);
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Department Directory Management</title>
<style>
body{font-family:Arial;background:#ebedef;margin:0;padding:40px;}
.container{max-width:600px;margin:auto;}
h2{color:#2c3e50;}
.card{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px;}
label{font-weight:600;color:#2c3e50;display:block;margin-top:10px;}
input{width:100%;padding:8px;margin-top:5px;box-sizing:border-box;border:1px solid #ccc;border-radius:5px;}
button{margin-top:15px;background:#566573;color:#fff;border:none;padding:10px 16px;border-radius:5px;cursor:pointer;}
.message{color:#1e8449;font-weight:bold;}
.folder-list{list-style:none;padding:0;}
.folder-list li{padding:6px 10px;background:#f4f6f7;margin-bottom:4px;border-radius:4px;}
</style></head><body>
<div class="container">
<h2>📁 Department Document Directory Management</h2>
<?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<div class="card">
<h3>Create Folder</h3>
<form method="POST" action="directory_management.php">
<input type="hidden" name="action" value="create">
<input type="text" name="folder_name" placeholder="New folder name" required>
<button type="submit">Create</button>
</form>
</div>
<div class="card">
<h3>Rename Folder</h3>
<form method="POST" action="directory_management.php">
<input type="hidden" name="action" value="rename">
<label>Existing Folder</label>
<select name="old_name"><?php foreach ($folders as $f) echo "<option>" . htmlspecialchars(basename($f)) . "</option>"; ?></select>
<label>New Name</label>
<input type="text" name="new_name" required>
<button type="submit">Rename</button>
</form>
</div>
<div class="card">
<h3>Delete Folder</h3>
<form method="POST" action="directory_management.php">
<input type="hidden" name="action" value="delete">
<select name="delete_name"><?php foreach ($folders as $f) echo "<option>" . htmlspecialchars(basename($f)) . "</option>"; ?></select>
<button type="submit">Delete</button>
</form>
</div>
<div class="card">
<h3>Current Folders</h3>
<ul class="folder-list">
<?php foreach ($folders as $f): ?><li><?php echo htmlspecialchars(basename($f)); ?></li><?php endforeach; ?>
</ul>
</div>
</div>
</body></html>