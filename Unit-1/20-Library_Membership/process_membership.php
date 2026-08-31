<?php

    // Function to validate member details
    function validateMember($data) {
        $errors = [];

        if (empty($data['fullname'])) {
            $errors[] = "Full name is required.";
        } elseif (!preg_match('/^[a-zA-Z ]+$/', $data['fullname'])) {
            $errors[] = "Name should contain only letters and spaces.";
        }

        if ($data['age'] === '' || $data['age'] < 5 || $data['age'] > 100) {
            $errors[] = "Please enter a valid age (5-100).";
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "A valid email address is required.";
        }

        if (empty($data['phone']) || !preg_match('/^[0-9]{10}$/', $data['phone'])) {
            $errors[] = "Phone number must be exactly 10 digits.";
        }

        if (empty($data['address'])) {
            $errors[] = "Address is required.";
        }

        if (empty($data['membership_type'])) {
            $errors[] = "Please select a membership type.";
        }

        return $errors;
    }

    // Function to get membership fee
    function getMembershipFee($type) {
        $fees = [
            "Student" => 200,
            "General" => 500,
            "Senior Citizen" => 150
        ];
        return $fees[$type] ?? 0;
    }

    // Function to calculate membership validity
    function getExpiryDate() {
        return date("d-m-Y", strtotime("+1 year"));
    }

    // Collect input
    $data = [
        'fullname'        => trim($_POST['fullname'] ?? ''),
        'age'             => trim($_POST['age'] ?? ''),
        'email'           => trim($_POST['email'] ?? ''),
        'phone'           => trim($_POST['phone'] ?? ''),
        'address'         => trim($_POST['address'] ?? ''),
        'membership_type' => trim($_POST['membership_type'] ?? ''),
    ];

    $errors = validateMember($data);

    if (!empty($errors)) {
        header("Location: library_form.html?error=" . urlencode(implode(" ", $errors)));
        exit();
    }

    $member_id = "LIB" . rand(10000, 99999);
    $join_date = date("d-m-Y");
    $expiry_date = getExpiryDate();
    $fee = getMembershipFee($data['membership_type']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Membership Confirmation</title>
    <link rel="stylesheet" href="style10.css">
</head>
<body>

    <div class="container">
        <h1>🎉 Membership Registered!</h1>
        <p class="member-id">Member ID: <strong><?php echo $member_id; ?></strong></p>

        <table>
            <tr><th>Full Name</th><td><?php echo htmlspecialchars($data['fullname']); ?></td></tr>
            <tr><th>Age</th><td><?php echo htmlspecialchars($data['age']); ?></td></tr>
            <tr><th>Email</th><td><?php echo htmlspecialchars($data['email']); ?></td></tr>
            <tr><th>Phone</th><td><?php echo htmlspecialchars($data['phone']); ?></td></tr>
            <tr><th>Address</th><td><?php echo htmlspecialchars($data['address']); ?></td></tr>
            <tr><th>Membership Type</th><td><?php echo htmlspecialchars($data['membership_type']); ?></td></tr>
            <tr><th>Annual Fee</th><td>₹<?php echo number_format($fee, 2); ?></td></tr>
            <tr><th>Join Date</th><td><?php echo $join_date; ?></td></tr>
            <tr><th>Valid Until</th><td><?php echo $expiry_date; ?></td></tr>
        </table>

        <p class="note">Please retain your Member ID for library visits and renewals.</p>
        <a href="library_form.html" class="back-btn">Register Another Member</a>
    </div>

</body>
</html>