<?php

    // Function to calculate bill based on slab rates
    function calculateBill($units) {
        $total = 0;
        $breakdown = [];

        if ($units <= 100) {
            $charge = $units * 2.50;
            $total += $charge;
            $breakdown[] = "0-100 units (" . $units . " x ₹2.50) = ₹" . number_format($charge, 2);
        } elseif ($units <= 200) {
            $slab1 = 100 * 2.50;
            $slab2 = ($units - 100) * 4.00;
            $total = $slab1 + $slab2;
            $breakdown[] = "0-100 units (100 x ₹2.50) = ₹" . number_format($slab1, 2);
            $breakdown[] = "101-200 units (" . ($units - 100) . " x ₹4.00) = ₹" . number_format($slab2, 2);
        } elseif ($units <= 400) {
            $slab1 = 100 * 2.50;
            $slab2 = 100 * 4.00;
            $slab3 = ($units - 200) * 6.00;
            $total = $slab1 + $slab2 + $slab3;
            $breakdown[] = "0-100 units (100 x ₹2.50) = ₹" . number_format($slab1, 2);
            $breakdown[] = "101-200 units (100 x ₹4.00) = ₹" . number_format($slab2, 2);
            $breakdown[] = "201-400 units (" . ($units - 200) . " x ₹6.00) = ₹" . number_format($slab3, 2);
        } else {
            $slab1 = 100 * 2.50;
            $slab2 = 100 * 4.00;
            $slab3 = 200 * 6.00;
            $slab4 = ($units - 400) * 8.00;
            $total = $slab1 + $slab2 + $slab3 + $slab4;
            $breakdown[] = "0-100 units (100 x ₹2.50) = ₹" . number_format($slab1, 2);
            $breakdown[] = "101-200 units (100 x ₹4.00) = ₹" . number_format($slab2, 2);
            $breakdown[] = "201-400 units (200 x ₹6.00) = ₹" . number_format($slab3, 2);
            $breakdown[] = "Above 400 units (" . ($units - 400) . " x ₹8.00) = ₹" . number_format($slab4, 2);
        }

        return ["total" => $total, "breakdown" => $breakdown];
    }

    // Function to calculate additional charges (fixed charge + tax)
    function calculateAdditionalCharges($baseAmount) {
        $fixedCharge = 50; // flat meter/service charge
        $tax = $baseAmount * 0.05; // 5% electricity tax
        return ["fixed" => $fixedCharge, "tax" => $tax];
    }

    // Collect input
    $consumer_name = trim($_POST['consumer_name'] ?? '');
    $units = floatval($_POST['units'] ?? 0);

    if (empty($consumer_name) || $units < 0) {
        echo "Please enter valid details. <a href='bill_form.html'>Go back</a>";
        exit();
    }

    $result = calculateBill($units);
    $baseAmount = $result['total'];
    $breakdown = $result['breakdown'];

    $additional = calculateAdditionalCharges($baseAmount);
    $grandTotal = $baseAmount + $additional['fixed'] + $additional['tax'];

    $bill_no = "BILL" . rand(10000, 99999);
    $bill_date = date("d-m-Y");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Electricity Bill</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>

    <div class="container">
        <h1>⚡ Electricity Bill</h1>

        <div class="bill-header">
            <p><strong>Bill No:</strong> <?php echo $bill_no; ?></p>
            <p><strong>Date:</strong> <?php echo $bill_date; ?></p>
            <p><strong>Consumer Name:</strong> <?php echo htmlspecialchars($consumer_name); ?></p>
            <p><strong>Units Consumed:</strong> <?php echo $units; ?> kWh</p>
        </div>

        <div class="breakdown">
            <h3>Slab-wise Charges</h3>
            <ul>
                <?php foreach ($breakdown as $line): ?>
                    <li><?php echo $line; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="summary">
            <p><span>Energy Charges:</span> <span>₹<?php echo number_format($baseAmount, 2); ?></span></p>
            <p><span>Fixed Charge:</span> <span>₹<?php echo number_format($additional['fixed'], 2); ?></span></p>
            <p><span>Electricity Tax (5%):</span> <span>₹<?php echo number_format($additional['tax'], 2); ?></span></p>
            <p class="grand-total"><span>Total Bill Amount:</span> <span>₹<?php echo number_format($grandTotal, 2); ?></span></p>
        </div>

        <a href="bill_form.html" class="back-btn">Calculate Another Bill</a>
    </div>

</body>
</html>