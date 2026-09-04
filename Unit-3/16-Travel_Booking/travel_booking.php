<?php
session_start();
$fileName = "bookings.txt";
$message = "";
if (!isset($_SESSION['customer_bookings'])) $_SESSION['customer_bookings'] = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customer_name'])) {
    $customer = trim($_POST['customer_name']);
    $destination = trim($_POST['destination']);
    $travelDate = trim($_POST['travel_date']);

    try {
        if ($customer === "" || $destination === "" || $travelDate === "") {
            throw new Exception("All fields are required.");
        }
        $dateObj = new DateTime($travelDate);
        $today = new DateTime();
        if ($dateObj < $today) {
            throw new Exception("Travel date must be in the future.");
        }
        $bookingId = "BK" . strtoupper(substr(md5(uniqid()), 0, 6));
        $record = "$bookingId|$customer|$destination|$travelDate|" . date("Y-m-d H:i:s") . "\n";
        file_put_contents($fileName, $record, FILE_APPEND | LOCK_EX);

        $_SESSION['customer_bookings'][] = $bookingId;
        $message = "Booking confirmed! Confirmation ID: $bookingId for $destination on $travelDate.";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

$bookings = [];
if (file_exists($fileName)) {
    $lines = file($fileName, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        $parts = explode("|", $line);
        if (count($parts) === 5) $bookings[] = $parts;
    }
}
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Travel Booking Management</title>
<style>
body{font-family:Arial;background:#eaf6f6;margin:0;padding:40px;}
.container{max-width:650px;margin:auto;}
h2{color:#0e6655;}
.card{background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);margin-bottom:20px;}
label{font-weight:600;color:#0e6655;display:block;margin-top:10px;}
input{width:100%;padding:8px;margin-top:5px;box-sizing:border-box;border:1px solid #ccc;border-radius:5px;}
button{margin-top:15px;background:#148f77;color:#fff;border:none;padding:10px 16px;border-radius:5px;cursor:pointer;}
table{width:100%;border-collapse:collapse;margin-top:10px;}
th,td{padding:8px;border-bottom:1px solid #ddd;text-align:left;font-size:13px;}
th{background:#d0ece7;}
.message{color:#0e6655;font-weight:bold;background:#d0ece7;padding:10px;border-radius:6px;}
</style></head><body>
<div class="container">
<h2>✈️ Travel Booking Management System</h2>
<?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>
<div class="card">
<h3>New Booking</h3>
<form method="POST" action="travel_booking.php">
<label>Customer Name</label>
<input type="text" name="customer_name" required>
<label>Destination</label>
<input type="text" name="destination" required>
<label>Travel Date</label>
<input type="date" name="travel_date" required>
<button type="submit">Book Now</button>
</form>
</div>
<div class="card">
<h3>All Bookings</h3>
<table>
<tr><th>ID</th><th>Customer</th><th>Destination</th><th>Travel Date</th><th>Booked On</th></tr>
<?php foreach ($bookings as $b): ?>
<tr><td><?php echo htmlspecialchars($b[0]); ?></td><td><?php echo htmlspecialchars($b[1]); ?></td><td><?php echo htmlspecialchars($b[2]); ?></td><td><?php echo htmlspecialchars($b[3]); ?></td><td><?php echo htmlspecialchars($b[4]); ?></td></tr>
<?php endforeach; ?>
</table>
</div>
</div>
</body></html>