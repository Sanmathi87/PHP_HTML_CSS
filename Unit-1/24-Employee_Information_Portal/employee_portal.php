<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Information Portal</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',Arial,sans-serif; }
body { background:#eceff1; padding:40px 15px; }
.container { background:white; max-width:550px; margin:0 auto; padding:30px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
h1 { text-align:center; color:#455a64; margin-bottom:20px; font-size:1.5rem; }
label { display:block; margin-top:15px; margin-bottom:5px; font-weight:bold; color:#333; }
input, select { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; }
button { width:100%; margin-top:20px; padding:12px; background:#455a64; color:white; border:none; border-radius:6px; font-size:1rem; cursor:pointer; }
button:hover { background:#263238; }
.error-box { background:#ffebee; color:#c62828; padding:12px; border-radius:6px; margin-top:15px; }
.error-box ul { margin-left:20px; margin-top:5px; }
.profile-card { margin-top:25px; }
table { width:100%; border-collapse:collapse; margin-top:10px; }
th, td { text-align:left; padding:10px; border-bottom:1px solid #eee; }
th { width:40%; color:#666; font-weight:normal; }
td { font-weight:bold; color:#333; }
</style>
</head>
<body>
<div class="container">
<h1>Employee Information Portal</h1>
<form method="POST">
<label>Employee Name *</label>
<input type="text" name="emp_name" value="<?php echo isset($_POST['emp_name']) ? htmlspecialchars($_POST['emp_name']) : ''; ?>">
<label>Employee ID *</label>
<input type="text" name="emp_id" value="<?php echo isset($_POST['emp_id']) ? htmlspecialchars($_POST['emp_id']) : ''; ?>">
<label>Email *</label>
<input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
<label>Phone *</label>
<input type="text" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
<label>Department *</label>
<select name="department">
<option value="">-- Select --</option>
<option value="HR">HR</option>
<option value="IT">IT</option>
<option value="Finance">Finance</option>
<option value="Operations">Operations</option>
</select>
<label>Designation *</label>
<input type="text" name="designation" value="<?php echo isset($_POST['designation']) ? htmlspecialchars($_POST['designation']) : ''; ?>">
<label>Date of Joining *</label>
<input type="date" name="doj" value="<?php echo isset($_POST['doj']) ? htmlspecialchars($_POST['doj']) : ''; ?>">
<button type="submit">Submit</button>
</form>

<?php
function validateEmployee($data) {
    $errors = [];
    if (empty($data['emp_name'])) $errors[] = "Employee name is required.";
    if (empty($data['emp_id'])) $errors[] = "Employee ID is required.";
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "A valid email is required.";
    if (empty($data['phone']) || !preg_match('/^[0-9]{10}$/', $data['phone'])) $errors[] = "Phone number must be exactly 10 digits.";
    if (empty($data['department'])) $errors[] = "Please select a department.";
    if (empty($data['designation'])) $errors[] = "Designation is required.";
    if (empty($data['doj'])) $errors[] = "Date of joining is required.";
    return $errors;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'emp_name' => trim($_POST['emp_name'] ?? ''),
        'emp_id' => trim($_POST['emp_id'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'department' => trim($_POST['department'] ?? ''),
        'designation' => trim($_POST['designation'] ?? ''),
        'doj' => trim($_POST['doj'] ?? ''),
    ];

    $errors = validateEmployee($data);

    if (!empty($errors)) {
        echo '<div class="error-box"><strong>Please fix the following:</strong><ul>';
        foreach ($errors as $err) echo '<li>' . htmlspecialchars($err) . '</li>';
        echo '</ul></div>';
    } else {
        echo '<div class="profile-card"><h2 style="color:#455a64;">Employee Profile</h2><table>';
        echo '<tr><th>Name</th><td>' . htmlspecialchars($data['emp_name']) . '</td></tr>';
        echo '<tr><th>Employee ID</th><td>' . htmlspecialchars($data['emp_id']) . '</td></tr>';
        echo '<tr><th>Email</th><td>' . htmlspecialchars($data['email']) . '</td></tr>';
        echo '<tr><th>Phone</th><td>' . htmlspecialchars($data['phone']) . '</td></tr>';
        echo '<tr><th>Department</th><td>' . htmlspecialchars($data['department']) . '</td></tr>';
        echo '<tr><th>Designation</th><td>' . htmlspecialchars($data['designation']) . '</td></tr>';
        echo '<tr><th>Date of Joining</th><td>' . htmlspecialchars($data['doj']) . '</td></tr>';
        echo '</table></div>';
    }
}
?>
</div>
</body>
</html>