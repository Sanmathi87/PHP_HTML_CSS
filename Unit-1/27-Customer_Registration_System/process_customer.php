<?php
function validateCustomer($data) {
    $errors = [];
    if (empty($data['fullname']) || !preg_match('/^[a-zA-Z ]+$/', $data['fullname'])) $errors[] = "Valid full name is required.";
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (empty($data['phone']) || !preg_match('/^[0-9]{10}$/', $data['phone'])) $errors[] = "10-digit phone number is required.";
    if (empty($data['dob'])) $errors[] = "Date of birth is required.";
    if (empty($data['address'])) $errors[] = "Address is required.";
    if (empty($data['city'])) $errors[] = "City is required.";
    if (empty($data['pincode']) || !preg_match('/^[0-9]{6}$/', $data['pincode'])) $errors[] = "Valid 6-digit pincode is required.";
    return $errors;
}

$data = [
    'fullname' => trim($_POST['fullname'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'phone' => trim($_POST['phone'] ?? ''),
    'dob' => trim($_POST['dob'] ?? ''),
    'address' => trim($_POST['address'] ?? ''),
    'city' => trim($_POST['city'] ?? ''),
    'pincode' => trim($_POST['pincode'] ?? ''),
];

$errors = validateCustomer($data);

if (!empty($errors)) {
    header("Location: customer_form.html?error=" . urlencode(implode(" ", $errors)));
    exit();
}

$customer_id = "CUST" . rand(10000, 99999);
$reg_date = date("d-m-Y H:i");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Registration Successful</title>
<link rel="stylesheet" href="style_customer.css">
</head>
<body>
<div class="container">
<h1>Registration Successful!</h1>
<p class="customer-id">Customer ID: <strong><?php echo $customer_id; ?></strong></p>
<table>
<tr><th>Full Name</th><td><?php echo htmlspecialchars($data['fullname']); ?></td></tr>
<tr><th>Email</th><td><?php echo htmlspecialchars($data['email']); ?></td></tr>
<tr><th>Phone</th><td><?php echo htmlspecialchars($data['phone']); ?></td></tr>
<tr><th>Date of Birth</th><td><?php echo htmlspecialchars($data['dob']); ?></td></tr>
<tr><th>Address</th><td><?php echo htmlspecialchars($data['address']); ?></td></tr>
<tr><th>City</th><td><?php echo htmlspecialchars($data['city']); ?></td></tr>
<tr><th>Pincode</th><td><?php echo htmlspecialchars($data['pincode']); ?></td></tr>
<tr><th>Registered On</th><td><?php echo $reg_date; ?></td></tr>
</table>
<a href="customer_form.html" class="back-btn">Register Another Customer</a>
</div>
</body>
</html>