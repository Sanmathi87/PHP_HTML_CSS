<?php
session_start();
$fileName = "events.txt";
$message = "";

if (!isset($_SESSION['registrations'])) $_SESSION['registrations'] = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_name'])) {
    $eventName = trim($_POST['event_name']);
    $eventDate = trim($_POST['event_date']);
    $participant = trim($_POST['participant']);

    try {
        if ($eventName === "" || $eventDate === "" || $participant === "") {
            throw new Exception("All fields are required.");
        }
        $dateObj = new DateTime($eventDate);
        $today = new DateTime();
        if ($dateObj < $today) {
            throw new Exception("Event date cannot be in the past.");
        }
        $record = "$eventName|$eventDate|$participant|" . date("Y-m-d H:i:s") . "\n";
        file_put_contents($fileName, $record, FILE_APPEND | LOCK_EX);
        $_SESSION['registrations'][] = $eventName;
        $message = "$participant registered for '$eventName' on $eventDate.";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

$events = [];
if (file_exists($fileName)) {
    $lines = file($fileName, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        $parts = explode("|", $line);
        if (count($parts) === 4) $events[] = $parts;
    }
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Event Registration System</title>
<style>
body{font-family:Arial;background:#fef5e7;margin:0;padding:40px;}
.container{max-width:650px;margin:auto;}
h2{color:#9c640c;}
.card{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px;}
label{font-weight:600;color:#9c640c;display:block;margin-top:10px;}
input{width:100%;padding:8px;margin-top:5px;box-sizing:border-box;border:1px solid #ccc;border-radius:5px;}
button{margin-top:15px;background:#b9770e;color:#fff;border:none;padding:10px 16px;border-radius:5px;cursor:pointer;}
table{width:100%;border-collapse:collapse;margin-top:10px;}
th,td{padding:8px;border-bottom:1px solid #ddd;text-align:left;font-size:14px;}
th{background:#fcf3cf;}
.message{color:#1e8449;font-weight:bold;}
.session-info{font-size:13px;color:#666;}
</style></head><body>
<div class="container">
<h2>🎫 Event Registration & Scheduling</h2>
<?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<div class="card">
<h3>Register for Event</h3>
<form method="POST" action="event_registration.php">
<label>Event Name</label>
<input type="text" name="event_name" required>
<label>Event Date</label>
<input type="date" name="event_date" required>
<label>Participant Name</label>
<input type="text" name="participant" required>
<button type="submit">Register</button>
</form>
</div>
<div class="card">
<h3>Scheduled Events</h3>
<table>
<tr><th>Event</th><th>Date</th><th>Participant</th><th>Registered On</th></tr>
<?php foreach ($events as $e): ?>
<tr><td><?php echo htmlspecialchars($e[0]); ?></td><td><?php echo htmlspecialchars($e[1]); ?></td><td><?php echo htmlspecialchars($e[2]); ?></td><td><?php echo htmlspecialchars($e[3]); ?></td></tr>
<?php endforeach; ?>
</table>
</div>
<div class="card session-info">
Your session registrations this visit: <?php echo count($_SESSION['registrations']); ?>
</div>
</div>
</body></html>