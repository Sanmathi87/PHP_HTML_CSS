<?php

    // Function to validate all patient inputs, returns array of errors
    function validatePatient($data) {
        $errors = [];

        if (empty($data['fullname'])) {
            $errors[] = "Full name is required.";
        } elseif (!preg_match('/^[a-zA-Z ]+$/', $data['fullname'])) {
            $errors[] = "Full name should contain only letters and spaces.";
        }

        if ($data['age'] === '') {
            $errors[] = "Age is required.";
        } elseif ($data['age'] < 0 || $data['age'] > 120) {
            $errors[] = "Please enter a valid age.";
        }

        if (empty($data['gender'])) {
            $errors[] = "Gender is required.";
        }

        if (empty($data['phone'])) {
            $errors[] = "Phone number is required.";
        } elseif (!preg_match('/^[0-9]{10}$/', $data['phone'])) {
            $errors[] = "Phone number must be exactly 10 digits.";
        }

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (empty($data['reason'])) {
            $errors[] = "Reason for visit is required.";
        }

        if (empty($data['department'])) {
            $errors[] = "Please select a preferred department.";
        }

        return $errors;
    }

    // Collect input
    $data = [
        'fullname'    => trim($_POST['fullname'] ?? ''),
        'age'         => trim($_POST['age'] ?? ''),
        'gender'      => trim($_POST['gender'] ?? ''),
        'phone'       => trim($_POST['phone'] ?? ''),
        'email'       => trim($_POST['email'] ?? ''),
        'address'     => trim($_POST['address'] ?? ''),
        'blood_group' => trim($_POST['blood_group'] ?? ''),
        'reason'      => trim($_POST['reason'] ?? ''),
        'department'  => trim($_POST['department'] ?? ''),
    ];

    // Validate
    $errors = validatePatient($data);

    if (!empty($errors)) {
        $errorMsg = implode(" ", $errors);
        header("Location: patient_form.html?error=" . urlencode($errorMsg) . "&fullname=" . urlencode($data['fullname']));
        exit();
    }

    // Generate patient ID
    $patient_id = "PAT" . rand(10000, 99999);
    $reg_date = date("d-m-Y H:i");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="result-container">
        <h1>Patient Registered Successfully!</h1>
        <p class="student-name">Patient ID: <strong><?php echo $patient_id; ?></strong></p>

        <table>
            <tr><th>Field</th><th>Details</th></tr>
            <tr><td>Full Name</td><td><?php echo htmlspecialchars($data['fullname']); ?></td></tr>
            <tr><td>Age</td><td><?php echo htmlspecialchars($data['age']); ?></td></tr>
            <tr><td>Gender</td><td><?php echo htmlspecialchars($data['gender']); ?></td></tr>
            <tr><td>Phone</td><td><?php echo htmlspecialchars($data['phone']); ?></td></tr>
            <tr><td>Email</td><td><?php echo $data['email'] !== '' ? htmlspecialchars($data['email']) : '-'; ?></td></tr>
            <tr><td>Address</td><td><?php echo $data['address'] !== '' ? htmlspecialchars($data['address']) : '-'; ?></td></tr>
            <tr><td>Blood Group</td><td><?php echo $data['blood_group'] !== '' ? htmlspecialchars($data['blood_group']) : '-'; ?></td></tr>
            <tr><td>Reason for Visit</td><td><?php echo htmlspecialchars($data['reason']); ?></td></tr>
            <tr><td>Department</td><td><?php echo htmlspecialchars($data['department']); ?></td></tr>
            <tr><td>Registration Date</td><td><?php echo $reg_date; ?></td></tr>
        </table>

        <p class="note">Please save your Patient ID for future reference and hospital visits.</p>
    </div>

</body>
</html>