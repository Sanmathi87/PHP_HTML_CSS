<?php
    $name = $_POST['name'];
    $regno = $_POST['regno'];
    $department = $_POST['department'];
    $year = $_POST['year'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Details</title>
    <style>
        table {
            border-collapse: collapse;
            width: 50%;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Submitted Student Details</h2>
    <table>
        <tr><th>Field</th><th>Value</th></tr>
        <tr><td>Name</td><td><?php echo htmlspecialchars($name); ?></td></tr>
        <tr><td>Register Number</td><td><?php echo htmlspecialchars($regno); ?></td></tr>
        <tr><td>Department</td><td><?php echo htmlspecialchars($department); ?></td></tr>
        <tr><td>Year</td><td><?php echo htmlspecialchars($year); ?></td></tr>
        <tr><td>Email</td><td><?php echo htmlspecialchars($email); ?></td></tr>
        <tr><td>Phone</td><td><?php echo htmlspecialchars($phone); ?></td></tr>
    </table>
</body>
</html>
