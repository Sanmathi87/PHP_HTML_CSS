<?php

    // Function to get plan details
    function getPlanDetails($plan) {
        $plans = [
            "Basic"    => ["rental" => 199, "data_limit" => 30, "min_limit" => 100],
            "Standard" => ["rental" => 399, "data_limit" => 60, "min_limit" => 300],
            "Premium"  => ["rental" => 599, "data_limit" => 90, "min_limit" => 999999] // unlimited
        ];
        return $plans[$plan] ?? null;
    }

    // Function to calculate extra data charges
    function calculateExtraDataCharge($extraData) {
        $ratePerGB = 20;
        return $extraData * $ratePerGB;
    }

    // Function to calculate extra call charges
    function calculateExtraCallCharge($extraMinutes) {
        $ratePerMinute = 0.5;
        return $extraMinutes * $ratePerMinute;
    }

    // Function to calculate GST
    function calculateGST($amount) {
        return $amount * 0.18;
    }

    // Collect input
    $customer_name = trim($_POST['customer_name'] ?? '');
    $mobile        = trim($_POST['mobile'] ?? '');
    $plan          = trim($_POST['plan'] ?? '');
    $extra_data    = floatval($_POST['extra_data'] ?? 0);
    $extra_minutes = intval($_POST['extra_minutes'] ?? 0);

    $planDetails = getPlanDetails($plan);

    // Validation using control structures
    if (empty($customer_name) || empty($mobile) || empty($plan) || $planDetails === null) {
        echo "Please fill all required fields correctly. <a href='mobile_bill_form.html'>Go back</a>";
        exit();
    }

    $rental = $planDetails['rental'];

    // Extra data charge only if usage crosses plan limit conversion (simplified: any entered extra is charged)
    $dataCharge = ($extra_data > 0) ? calculateExtraDataCharge($extra_data) : 0;

    // Extra minutes charge only applicable for Basic and Standard (Premium has unlimited calls)
    if ($plan === "Premium") {
        $callCharge = 0;
    } else {
        $callCharge = ($extra_minutes > 0) ? calculateExtraCallCharge($extra_minutes) : 0;
    }

    $subtotal = $rental + $dataCharge + $callCharge;
    $gst = calculateGST($subtotal);
    $grandTotal = $subtotal + $gst;

    $bill_no = "MB" . rand(10000, 99999);
    $bill_date = date("d-m-Y");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mobile Bill Summary</title>
    <link rel="stylesheet" href="style8.css">
</head>
<body>

    <div class="container">
        <h1>📱 Mobile Bill Summary</h1>

        <div class="bill-header">
            <p><strong>Bill No:</strong> <?php echo $bill_no; ?></p>
            <p><strong>Date:</strong> <?php echo $bill_date; ?></p>
            <p><strong>Customer:</strong> <?php echo htmlspecialchars($customer_name); ?></p>
            <p><strong>Mobile:</strong> <?php echo htmlspecialchars($mobile); ?></p>
            <p><strong>Plan:</strong> <?php echo htmlspecialchars($plan); ?></p>
        </div>

        <div class="summary">
            <p><span>Plan Rental:</span> <span>₹<?php echo number_format($rental, 2); ?></span></p>
            <p><span>Extra Data Charge (<?php echo $extra_data; ?> GB):</span> <span>₹<?php echo number_format($dataCharge, 2); ?></span></p>
            <p><span>Extra Call Charge (<?php echo $extra_minutes; ?> mins):</span> <span>₹<?php echo number_format($callCharge, 2); ?></span></p>
            <p><span>Subtotal:</span> <span>₹<?php echo number_format($subtotal, 2); ?></span></p>
            <p><span>GST (18%):</span> <span>₹<?php echo number_format($gst, 2); ?></span></p>
            <p class="grand-total"><span>Total Bill Amount:</span> <span>₹<?php echo number_format($grandTotal, 2); ?></span></p>
        </div>

        <a href="mobile_bill_form.html" class="back-btn">Generate Another Bill</a>
    </div>

</body>
</html>