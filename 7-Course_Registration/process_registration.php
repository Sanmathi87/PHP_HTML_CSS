<?php

    // Function to validate all inputs, returns array of errors
    function validateRegistration($data) {
        $errors = [];

        if (empty($data['fullname'])) {
            $errors[] = "Full name is required.";
        } elseif (!preg_match('/^[a-zA-Z ]+$/', $data['fullname'])) {
            $errors[] = "Full name should contain only letters and spaces.";
        }

        if (empty($data['email'])) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (empty($data['phone'])) {
            $errors[] = "Phone number is required.";
        } elseif (!preg_match('/^[0-9]{10}$/', $data['phone'])) {
            $errors[] = "Phone number must be exactly 10 digits.";
        }

        if (empty($data['age'])) {
            $errors[] = "Age is required.";
        } elseif ($data['age'] < 15 || $data['age'] > 80) {
            $errors[] = "Age must be between 15 and 80.";
        }

        if (empty($data['course'])) {
            $errors[] = "Please select a course.";
        }

        if (empty($data['batch'])) {
            $errors[] = "Please select a preferred batch.";
        }

        return $errors;
    }

    // Collect input
    $data = [
        'fullname' => trim($_POST['fullname'] ?? ''),
        'email'    => trim($_POST['email'] ?? ''),
        'phone'    => trim($_POST['phone'] ?? ''),
        'age'      => trim($_POST['age'] ?? ''),
        'course'   => trim($_POST['course'] ?? ''),
        'batch'    => trim($_POST['batch'] ?? ''),
    ];

    // Validate
    $errors = validateRegistration($data);

    if (!empty($errors)) {
        $errorMsg = implode(" ", $errors);
        header("Location: registration_form.html?error=" . urlencode($errorMsg) . "&fullname=" . urlencode($data['fullname']));
        exit();
    }

    // Generate registration ID
    $registration_id = "REG" . rand(10000, 99999);
    $reg_date = date("d-m-Y H:i");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Successful</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="result-container">
        <h1>Registration Successful!</h1>
        <p class="student-name">Registration ID: <strong><?php echo $registration_id; ?></strong></p>

        <table>
            <tr><th>Field</th><th>Details</th></tr>
            <tr><td>Full Name</td><td><?php echo htmlspecialchars($data['fullname']); ?></td></tr>
            <tr><td>Email</td><td><?php echo htmlspecialchars($data['email']); ?></td></tr>
            <tr><td>Phone</td><td><?php echo htmlspecialchars($data['phone']); ?></td></tr>
            <tr><td>Age</td><td><?php echo htmlspecialchars($data['age']); ?></td></tr>
            <tr><td>Course</td><td><?php echo htmlspecialchars($data['course']); ?></td></tr>
            <tr><td>Batch</td><td><?php echo htmlspecialchars($data['batch']); ?></td></tr>
            <tr><td>Registration Date</td><td><?php echo $reg_date; ?></td></tr>
        </table>

        <p class="note">Please save your Registration ID for future reference.</p>
    </div>

</body>
</html>