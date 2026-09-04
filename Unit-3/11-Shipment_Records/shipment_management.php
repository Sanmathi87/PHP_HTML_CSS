<?php
$baseDir = "shipments";
if (!is_dir($baseDir)) mkdir($baseDir, 0777, true);
$message = "";
$foundResults = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $shipmentId = trim($_POST['shipment_id']);
    $destination = trim($_POST['destination']);
    $status = trim($_POST['status']);

    if ($shipmentId === "" || $destination === "") {
        $message = "Shipment ID and destination are required.";
    } else {
        try {
            $folder = $baseDir . "/" . strtolower(str_replace(" ", "_", $destination));
            if (!is_dir($folder)) mkdir($folder, 0777, true);
            $file = $folder . "/shipments.txt";
            $record = "$shipmentId|$destination|$status|" . date("Y-m-d H:i:s") . "\n";
            file_put_contents($file, $record, FILE_APPEND | LOCK_EX);
            $message = "Shipment $shipmentId recorded for $destination.";
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'search') {
    $searchId = trim($_POST['search_id']);
    $folders = glob($baseDir . "/*", GLOB_ONLYDIR);
    foreach ($folders as $folder) {
        $file = $folder . "/shipments.txt";
        if (file_exists($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES);
            foreach ($lines as $line) {
                $parts = explode("|", $line);
                if (isset($parts[0]) && $parts[0] === $searchId) {
                    $foundResults[] = $parts;
                }
            }
        }
    }
    if (empty($foundResults)) $message = "No shipment found with ID: $searchId";
}

$destFolders = glob($baseDir . "/*", GLOB_ONLYDIR);
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Shipment Records Management</title>
<style>
body{font-family:Arial;background:#eef2f7;margin:0;padding:40px;}
.container{max-width:650px;margin:auto;}
h2{color:#1a5276;}
.card{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px;}
label{font-weight:600;color:#1a5276;display:block;margin-top:10px;}
input,select{width:100%;padding:8px;margin-top:5px;box-sizing:border-box;border:1px solid #ccc;border-radius:5px;}
button{margin-top:15px;background:#2874a6;color:#fff;border:none;padding:10px 16px;border-radius:5px;cursor:pointer;}
.message{color:#c0392b;font-weight:bold;}
.result{background:#eafaf1;padding:10px;border-radius:6px;margin-top:5px;color:#1e8449;font-size:14px;}
.dirs{font-size:13px;color:#666;}
</style></head><body>
<div class="container">
<h2>📦 Shipment Records File Management</h2>
<?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<?php foreach ($foundResults as $r): ?>
<div class="result">ID: <?php echo htmlspecialchars($r[0]); ?> | Destination: <?php echo htmlspecialchars($r[1]); ?> | Status: <?php echo htmlspecialchars($r[2]); ?> | Recorded: <?php echo htmlspecialchars($r[3]); ?></div>
<?php endforeach; ?>
<div class="card">
<h3>Add Shipment Record</h3>
<form method="POST" action="shipment_management.php">
<input type="hidden" name="action" value="add">
<label>Shipment ID</label>
<input type="text" name="shipment_id" required>
<label>Destination</label>
<input type="text" name="destination" required>
<label>Status</label>
<select name="status"><option>In Transit</option><option>Delivered</option><option>Pending</option></select>
<button type="submit">Save Shipment</button>
</form>
</div>
<div class="card">
<h3>Search Shipment by ID</h3>
<form method="POST" action="shipment_management.php">
<input type="hidden" name="action" value="search">
<input type="text" name="search_id" required>
<button type="submit">Search</button>
</form>
</div>
<div class="card dirs">
<strong>Destination folders:</strong>
<?php foreach ($destFolders as $d): ?><div><?php echo htmlspecialchars(basename($d)); ?></div><?php endforeach; ?>
</div>
</div>
</body></html>