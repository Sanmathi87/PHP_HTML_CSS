<?php
// Task 9: Customer Information Validation Using Regular Expressions

$report = [];
$submitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted = true;

    $name = trim($_POST["name"]);
    $phone = trim($_POST["phone"]);
    $email = trim($_POST["email"]);
    $account = trim($_POST["account"]);

    // Regex rules
    $namePattern = '/^[A-Za-z ]{3,40}$/';
    $phonePattern = '/^[6-9]\d{9}$/';
    $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    $accountPattern = '/^\d{9,12}$/';

    $report["Name"] = preg_match($namePattern, $name)
        ? "Valid" : "Invalid (letters and spaces only, 3-40 characters)";

    $report["Phone Number"] = preg_match($phonePattern, $phone)
        ? "Valid" : "Invalid (must be a 10-digit number starting with 6-9)";

    $report["Email"] = preg_match($emailPattern, $email)
        ? "Valid" : "Invalid email format";

    $report["Account Number"] = preg_match($accountPattern, $account)
        ? "Valid" : "Invalid (must be 9-12 digits)";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer Information Validation</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Customer Information Validation</h1>

    <form method="POST" action="">
        <label for="name">Customer Name:</label>
        <input type="text" id="name" name="name" placeholder="e.g. Priya Kumar" required>

        <label for="phone">Phone Number:</label>
        <input type="text" id="phone" name="phone" placeholder="e.g. 9876543210" required>

        <label for="email">Email ID:</label>
        <input type="text" id="email" name="email" placeholder="e.g. priya@example.com" required>

        <label for="account">Account Number:</label>
        <input type="text" id="account" name="account" placeholder="e.g. 123456789" required>

        <button type="submit">Validate</button>
    </form>

    <?php if ($submitted) : ?>
        <div class="result">
            <h2>Validation Report</h2>
            <table>
                <tr><th>Field</th><th>Status</th></tr>
                <?php foreach ($report as $field => $status) : ?>
                <tr>
                    <td><?php echo $field; ?></td>
                    <td class="<?php echo ($status == 'Valid') ? 'valid' : 'invalid'; ?>"><?php echo $status; ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>