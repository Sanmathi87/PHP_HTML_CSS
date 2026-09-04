<?php
// Employee Attendance File Management
$fileName = "attendance.txt";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'mark') {
    $empId = trim($_POST['emp_id']);
    $empName = trim($_POST['emp_name']);
    $status = trim($_POST['status']);

    if ($empId === "" || $empName === "") {
        $message = "Employee ID and Name are required.";
    } else {
        try {
            $record = date("Y-m-d") . "|" . $empId . "|" . $empName . "|" . $status . "\n";
            if (file_put_contents($fileName, $record, FILE_APPEND | LOCK_EX) === false) {
                throw new Exception("Unable to write attendance record.");
            }
            $message = "Attendance marked for $empName ($status).";
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}

// Retrieve records, optionally filtered by employee ID
$filterId = $_POST['filter_id'] ?? '';
$records = [];
try {
    if (file_exists($fileName)) {
        $allLines = file($fileName, FILE_IGNORE_NEW_LINES);
        foreach ($allLines as $line) {
            $parts = explode("|", $line);
            if (count($parts) === 4) {
                if ($filterId === '' || $parts[1] === $filterId) {
                    $records[] = $parts;
                }
            }
        }
    }
} catch (Exception $e) {
    $message = "Error reading attendance file: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Attendance</title>
<style>
    body { font-family: Arial, sans-serif; background: #f0f4f8; margin: 0; padding: 40px; }
    .container { max-width: 650px; margin: auto; }
    h2 { color: #1b4f72; }
    .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 20px; }
    label { font-weight: 600; color: #1b4f72; display: block; margin-top: 10px; }
    input, select { width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 5px; }
    button { margin-top: 15px; background: #2471a3; color: #fff; border: none; padding: 10px 16px; border-radius: 5px; cursor: pointer; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { padding: 8px; border-bottom: 1px solid #ddd; text-align: left; font-size: 14px; }
    th { background: #d6eaf8; }
    .present { color: #1e8449; font-weight: bold; }
    .absent { color: #c0392b; font-weight: bold; }
    .message { color: #1e8449; font-weight: bold; }
</style>
</head>
<body>
<div class="container">
    <h2>🕒 Employee Attendance Management</h2>
    <?php if ($message): ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php endif; ?>

    <div class="card">
        <h3>Mark Attendance</h3>
        <form method="POST" action="employee_attendance.php">
            <input type="hidden" name="action" value="mark">
            <label>Employee ID</label>
            <input type="text" name="emp_id" required>
            <label>Employee Name</label>
            <input type="text" name="emp_name" required>
            <label>Status</label>
            <select name="status">
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
                <option value="On Leave">On Leave</option>
            </select>
            <button type="submit">Mark Attendance</button>
        </form>
    </div>

    <div class="card">
        <h3>Attendance Records</h3>
        <form method="POST" action="employee_attendance.php">
            <label>Filter by Employee ID (optional)</label>
            <input type="text" name="filter_id" value="<?php echo htmlspecialchars($filterId); ?>">
            <button type="submit">View Records</button>
        </form>

        <table>
            <tr><th>Date</th><th>Emp ID</th><th>Name</th><th>Status</th></tr>
            <?php foreach ($records as $r): ?>
                <tr>
                    <td><?php echo htmlspecialchars($r[0]); ?></td>
                    <td><?php echo htmlspecialchars($r[1]); ?></td>
                    <td><?php echo htmlspecialchars($r[2]); ?></td>
                    <td class="<?php echo $r[3] === 'Present' ? 'present' : 'absent'; ?>">
                        <?php echo htmlspecialchars($r[3]); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
</body>
</html>