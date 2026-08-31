<?php
    // Collect mandatory fields
    $fullname   = trim($_POST['fullname'] ?? '');
    $dob        = trim($_POST['dob'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $course     = trim($_POST['course'] ?? '');
    $percentage = trim($_POST['percentage'] ?? '');

    // Non-mandatory fields
    $gender      = $_POST['gender'] ?? '';
    $address     = trim($_POST['address'] ?? '');
    $institution = trim($_POST['institution'] ?? '');

    // Validation
    if (empty($fullname) || empty($dob) || empty($email) || empty($phone) || empty($course) || empty($percentage)) {
        header("Location: admission_form.html?error=1");
        exit();
    }

    // Simple email format check
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format. <a href='admission_form.html'>Go back</a>";
        exit();
    }

    // Generate a simple application number
    $application_no = "APP" . rand(1000, 9999);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admission Acknowledgement</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="ack-container">
        <h1>Application Submitted Successfully!</h1>
        <p class="app-no">Application Number: <strong><?php echo $application_no; ?></strong></p>

        <table>
            <tr><th>Full Name</th><td><?php echo htmlspecialchars($fullname); ?></td></tr>
            <tr><th>Date of Birth</th><td><?php echo htmlspecialchars($dob); ?></td></tr>
            <tr><th>Gender</th><td><?php echo htmlspecialchars($gender); ?></td></tr>
            <tr><th>Email</th><td><?php echo htmlspecialchars($email); ?></td></tr>
            <tr><th>Phone</th><td><?php echo htmlspecialchars($phone); ?></td></tr>
            <tr><th>Address</th><td><?php echo htmlspecialchars($address); ?></td></tr>
            <tr><th>Course Applied</th><td><?php echo htmlspecialchars($course); ?></td></tr>
            <tr><th>Previous Institution</th><td><?php echo htmlspecialchars($institution); ?></td></tr>
            <tr><th>Percentage / CGPA</th><td><?php echo htmlspecialchars($percentage); ?></td></tr>
        </table>

        <p class="note">Please save your application number for future reference.</p>
    </div>

</body>
</html>