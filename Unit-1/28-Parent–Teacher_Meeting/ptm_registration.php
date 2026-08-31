<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Parent-Teacher Meeting Registration</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',Arial,sans-serif; }
body { background:#fff8e1; padding:40px 15px; }
.container { background:white; max-width:500px; margin:0 auto; padding:30px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
h1 { text-align:center; color:#f57f17; margin-bottom:20px; font-size:1.5rem; }
label { display:block; margin-top:15px; margin-bottom:5px; font-weight:bold; color:#333; }
input, select { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; }
button { width:100%; margin-top:20px; padding:12px; background:#f57f17; color:white; border:none; border-radius:6px; font-size:1rem; cursor:pointer; }
button:hover { background:#e65100; }
.error-box { background:#ffebee; color:#c62828; padding:12px; border-radius:6px; margin-top:15px; }
.confirmation { margin-top:25px; padding:20px; background:#fff3e0; border-radius:8px; }
table { width:100%; border-collapse:collapse; margin-top:10px; }
th, td { text-align:left; padding:10px; border-bottom:1px solid #eee; }
th { width:45%; color:#666; font-weight:normal; }
td { font-weight:bold; color:#333; }
</style>
</head>
<body>
<div class="container">
<h1>📅 Parent-Teacher Meeting Registration</h1>
<form method="POST">
<label>Parent Name *</label>
<input type="text" name="parent_name" value="<?php echo isset($_POST['parent_name']) ? htmlspecialchars($_POST['parent_name']) : ''; ?>">
<label>Student Name *</label>
<input type="text" name="student_name" value="<?php echo isset($_POST['student_name']) ? htmlspecialchars($_POST['student_name']) : ''; ?>">
<label>Class / Section *</label>
<input type="text" name="student_class" value="<?php echo isset($_POST['student_class']) ? htmlspecialchars($_POST['student_class']) : ''; ?>">
<label>Contact Number *</label>
<input type="text" name="contact" value="<?php echo isset($_POST['contact']) ? htmlspecialchars($_POST['contact']) : ''; ?>">
<label>Preferred Date *</label>
<input type="date" name="meeting_date" value="<?php echo isset($_POST['meeting_date']) ? htmlspecialchars($_POST['meeting_date']) : ''; ?>">
<label>Preferred Time Slot *</label>
<select name="time_slot">
<option value="">-- Select Slot --</option>
<option value="9:00 AM - 9:30 AM">9:00 AM - 9:30 AM</option>
<option value="9:30 AM - 10:00 AM">9:30 AM - 10:00 AM</option>
<option value="10:00 AM - 10:30 AM">10:00 AM - 10:30 AM</option>
<option value="11:00 AM - 11:30 AM">11:00 AM - 11:30 AM</option>
<option value="2:00 PM - 2:30 PM">2:00 PM - 2:30 PM</option>
</select>
<label>Topic to Discuss</label>
<input type="text" name="topic" value="<?php echo isset($_POST['topic']) ? htmlspecialchars($_POST['topic']) : ''; ?>">
<button type="submit">Book Appointment</button>
</form>

<?php
function validatePTM($data) {
    $errors = [];
    if (empty($data['parent_name'])) $errors[] = "Parent name is required.";
    if (empty($data['student_name'])) $errors[] = "Student name is required.";
    if (empty($data['student_class'])) $errors[] = "Class/Section is required.";
    if (empty($data['contact']) || !preg_match('/^[0-9]{10}$/', $data['contact'])) $errors[] = "Valid 10-digit contact number is required.";
    if (empty($data['meeting_date'])) $errors[] = "Please select a meeting date.";
    if (empty($data['time_slot'])) $errors[] = "Please select a time slot.";
    return $errors;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'parent_name' => trim($_POST['parent_name'] ?? ''),
        'student_name' => trim($_POST['student_name'] ?? ''),
        'student_class' => trim($_POST['student_class'] ?? ''),
        'contact' => trim($_POST['contact'] ?? ''),
        'meeting_date' => trim($_POST['meeting_date'] ?? ''),
        'time_slot' => trim($_POST['time_slot'] ?? ''),
        'topic' => trim($_POST['topic'] ?? ''),
    ];

    $errors = validatePTM($data);

    if (!empty($errors)) {
        echo '<div class="error-box"><strong>Please fix the following:</strong><ul style="margin-left:20px;margin-top:5px;">';
        foreach ($errors as $err) echo '<li>' . htmlspecialchars($err) . '</li>';
        echo '</ul></div>';
    } else {
        $appointment_id = "PTM" . rand(10000, 99999);
        echo '<div class="confirmation"><h2 style="color:#f57f17;margin-bottom:10px;">Appointment Confirmed!</h2>';
        echo '<p style="margin-bottom:15px;">Appointment ID: <strong>' . $appointment_id . '</strong></p><table>';
        echo '<tr><th>Parent Name</th><td>' . htmlspecialchars($data['parent_name']) . '</td></tr>';
        echo '<tr><th>Student Name</th><td>' . htmlspecialchars($data['student_name']) . '</td></tr>';
        echo '<tr><th>Class</th><td>' . htmlspecialchars($data['student_class']) . '</td></tr>';
        echo '<tr><th>Contact</th><td>' . htmlspecialchars($data['contact']) . '</td></tr>';
        echo '<tr><th>Meeting Date</th><td>' . htmlspecialchars($data['meeting_date']) . '</td></tr>';
        echo '<tr><th>Time Slot</th><td>' . htmlspecialchars($data['time_slot']) . '</td></tr>';
        echo '<tr><th>Topic</th><td>' . ($data['topic'] !== '' ? htmlspecialchars($data['topic']) : '-') . '</td></tr>';
        echo '</table></div>';
    }
}
?>
</div>
</body>
</html>