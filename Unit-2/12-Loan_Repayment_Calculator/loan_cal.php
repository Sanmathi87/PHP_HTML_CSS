<?php
// Task 12: Loan Repayment Calculator

$submitted = false;
$emi = 0;
$totalPayment = 0;
$totalInterest = 0;
$schedule = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted = true;

    $principal = floatval($_POST["principal"]);
    $annualRate = floatval($_POST["rate"]);
    $tenureMonths = intval($_POST["tenure"]);

    $monthlyRate = ($annualRate / 12) / 100;

    if ($monthlyRate == 0) {
        $emi = $principal / $tenureMonths;
    } else {
        $emi = $principal * $monthlyRate * pow(1 + $monthlyRate, $tenureMonths)
             / (pow(1 + $monthlyRate, $tenureMonths) - 1);
    }
    $emi = round($emi, 2);
    $totalPayment = round($emi * $tenureMonths, 2);
    $totalInterest = round($totalPayment - $principal, 2);

    // Month-wise repayment schedule
    $balance = $principal;
    for ($month = 1; $month <= $tenureMonths; $month++) {
        $interestPart = round($balance * $monthlyRate, 2);
        $principalPart = round($emi - $interestPart, 2);
        $balance = round($balance - $principalPart, 2);
        if ($balance < 0) { $balance = 0; }

        $schedule[] = [
            "month" => $month,
            "principalPart" => $principalPart,
            "interestPart" => $interestPart,
            "balance" => $balance
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Loan Repayment Calculator</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Loan Repayment Calculator</h1>

    <form method="POST" action="">
        <label for="principal">Loan Amount (₹):</label>
        <input type="number" id="principal" name="principal" step="0.01" placeholder="e.g. 500000" required>

        <label for="rate">Annual Interest Rate (%):</label>
        <input type="number" id="rate" name="rate" step="0.01" placeholder="e.g. 9.5" required>

        <label for="tenure">Tenure (months):</label>
        <input type="number" id="tenure" name="tenure" placeholder="e.g. 24" required>

        <button type="submit">Calculate EMI</button>
    </form>

    <?php if ($submitted) : ?>
        <div class="summary">
            <h2>Loan Summary</h2>
            <p>Monthly EMI: <strong>₹<?php echo number_format($emi, 2); ?></strong></p>
            <p>Total Payment: ₹<?php echo number_format($totalPayment, 2); ?></p>
            <p>Total Interest Payable: ₹<?php echo number_format($totalInterest, 2); ?></p>
        </div>

        <h2>Repayment Schedule</h2>
        <div class="table-scroll">
        <table>
            <tr><th>Month</th><th>Principal (₹)</th><th>Interest (₹)</th><th>Balance (₹)</th></tr>
            <?php foreach ($schedule as $row) : ?>
            <tr>
                <td><?php echo $row["month"]; ?></td>
                <td><?php echo number_format($row["principalPart"], 2); ?></td>
                <td><?php echo number_format($row["interestPart"], 2); ?></td>
                <td><?php echo number_format($row["balance"], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>