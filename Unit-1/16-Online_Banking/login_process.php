<?php

    // Simulated customer database (in real applications, this would be a database)
    function getCustomerRecords() {
        return [
            "sanmathi" => [
                "password" => "Pass@123",
                "name" => "Sanmathi R",
                "account_no" => "AC10023456",
                "account_type" => "Savings",
                "balance" => 45230.75,
                "branch" => "Coimbatore Main Branch"
            ],
            "arun" => [
                "password" => "Arun@456",
                "name" => "Arun Kumar",
                "account_no" => "AC10078912",
                "account_type" => "Current",
                "balance" => 128500.00,
                "branch" => "RS Puram Branch"
            ]
        ];
    }

    // Function to authenticate user
    function authenticate($username, $password, $records) {
        if (array_key_exists($username, $records)) {
            if ($records[$username]['password'] === $password) {
                return true;
            }
        }
        return false;
    }

    // Collect input
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $customers = getCustomerRecords();

    // Validate
    if (empty($username) || empty($password) || !authenticate($username, $password, $customers)) {
        header("Location: login_form.html?error=1");
        exit();
    }

    // Get logged-in customer's details
    $customer = $customers[$username];
    $login_time = date("d-m-Y h:i A");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="style7.css">
</head>
<body>

    <div class="dashboard-container">
        <div class="welcome-banner">
            <h1>Welcome, <?php echo htmlspecialchars($customer['name']); ?>!</h1>
            <p>Last login: <?php echo $login_time; ?></p>
        </div>

        <div class="account-card">
            <h2>Account Summary</h2>
            <table>
                <tr><th>Account Holder</th><td><?php echo htmlspecialchars($customer['name']); ?></td></tr>
                <tr><th>Account Number</th><td><?php echo htmlspecialchars($customer['account_no']); ?></td></tr>
                <tr><th>Account Type</th><td><?php echo htmlspecialchars($customer['account_type']); ?></td></tr>
                <tr><th>Branch</th><td><?php echo htmlspecialchars($customer['branch']); ?></td></tr>
                <tr><th>Available Balance</th><td class="balance">₹<?php echo number_format($customer['balance'], 2); ?></td></tr>
            </table>
        </div>

        <a href="login_form.html" class="logout-btn">Logout</a>
    </div>

</body>
</html>