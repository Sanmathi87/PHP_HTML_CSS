<?php
// Task 9: Student Records File Update System
$file = "students.txt";
$message = "";

if (isset($_POST['add_student'])) {
    $regNo = htmlspecialchars($_POST['reg_no']);
    $name = htmlspecialchars($_POST['student_name']);
    $dept = htmlspecialchars($_POST['dept']);
    $record = "$regNo|$name|$dept" . PHP_EOL;
    file_put_contents($file, $record, FILE_APPEND | LOCK_EX);
    $message = "Student record appended successfully.";
}

$records = [];
if (file_exists($file)) {
    $records = file($file, FILE_IGNORE_NEW_LINES);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Records</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 30px;
            color: #2c3e50;
        }
        
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        h2 {
            color: #1a5276;
            border-bottom: 2px solid #1a5276;
            padding-bottom: 10px;
        }
        
        h3, h4 {
            color: #2874a6;
            margin-top: 25px;
        }
        
        form {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin: 15px 0;
            padding: 15px;
            background: #f9fbfc;
            border: 1px solid #dfe6e9;
            border-radius: 6px;
        }
        
        label {
            font-weight: 600;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="password"],
        input[type="date"],
        input[type="number"],
        input[type="file"],
        select,
        textarea {
            padding: 8px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-size: 14px;
        }
        
        button {
            align-self: flex-start;
            background-color: #1a5276;
            color: white;
            border: none;
            padding: 9px 18px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 5px;
        }
        
        button:hover {
            background-color: #154360;
        }
        
        .message {
            padding: 12px 15px;
            border-radius: 5px;
            background-color: #eaf2f8;
            border-left: 4px solid #2980b9;
            margin: 15px 0;
        }
        
        .message.success {
            background-color: #eafaf1;
            border-left-color: #27ae60;
        }
        
        .message.error {
            background-color: #fdedec;
            border-left-color: #c0392b;
        }
        
        .article-box {
            background: #f9fbfc;
            border: 1px solid #dfe6e9;
            padding: 15px;
            border-radius: 6px;
            line-height: 1.6;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table, th, td {
            border: 1px solid #dfe6e9;
        }
        
        th, td {
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }
        
        th {
            background-color: #2874a6;
            color: white;
        }
        
        .nav-links {
            display: flex;
            gap: 12px;
            margin: 10px 0;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #1a5276;
            font-weight: 600;
        }
        
        .nav-links a:hover {
            text-decoration: underline;
        }
        
        ul {
            line-height: 1.8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Records File Update System</h2>
        <?php if ($message): ?><div class="message success"><?php echo $message; ?></div><?php endif; ?>

        <form method="POST" action="">
            <label>Register Number:</label>
            <input type="text" name="reg_no" required>
            <label>Student Name:</label>
            <input type="text" name="student_name" required>
            <label>Department:</label>
            <input type="text" name="dept" required>
            <button type="submit" name="add_student">Append Record</button>
        </form>

        <h3>Updated File Contents</h3>
        <table border="1" cellpadding="8" cellspacing="0">
            <tr><th>Reg No</th><th>Name</th><th>Department</th></tr>
            <?php foreach ($records as $line): $fields = explode("|", $line); ?>
                <tr>
                    <td><?php echo $fields[0]; ?></td>
                    <td><?php echo $fields[1]; ?></td>
                    <td><?php echo $fields[2]; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>
