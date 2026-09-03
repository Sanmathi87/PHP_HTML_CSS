<?php
// Task 20: Payroll Exception Handling

$employees = [
    ["name" => "Anand",  "hours" => 160, "rate" => 250],
    ["name" => "Divya",  "hours" => -10, "rate" => 300],   // invalid: negative hours
    ["name" => "Karthik","hours" => 150, "rate" => "abc"], // invalid: non-numeric rate
    ["name" => "Meena",  "hours" => 175, "rate" => 280],
    ["name" => "Rahul",  "hours" => 0,   "rate" => 260],
    ["name" => "Sneha",  "hours" => 165, "rate" => -50]    // invalid: negative rate
];

$payrollReport = [];

foreach ($employees as $emp) {
    try {
        if (!is_numeric($emp["hours"]) || !is_numeric($emp["rate"])) {
            throw new InvalidArgumentException("Hours and rate must be numeric.");
        }
        if ($emp["hours"] < 0) {
            throw new RangeException("Working hours cannot be negative.");
        }
        if ($emp["rate"] < 0) {
            throw new RangeException("Hourly rate cannot be negative.");
        }

        $salary = $emp["hours"] * $emp["rate"];
        $payrollReport[] = [
            "name" => $emp["name"],
            "status" => "Processed",
            "detail" => "₹" . number_format($salary, 2)
        ];

    } catch (Exception $e) {
        // Record the error but continue processing remaining employees
        $payrollReport[] = [
            "name" => $emp["name"],
            "status" => "Error",
            "detail" => $e->getMessage()
        ];
        continue;
    }
}

$processedCount = count(array_filter($payrollReport, fn($r) => $r["status"] == "Processed"));
$errorCount = count($payrollReport) - $processedCount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payroll Exception Handling</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #eafaf1; margin: 0; padding: 30px; color: #145a32; }
    h1 { text-align: center; color: #145a32; }
    .container { max-width: 650px; margin: 0 auto 25px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { padding: 10px; text-align: center; border: 1px solid #d5f5e3; }
    th { background-color: #1e8449; color: white; }
    tr:nth-child(even) { background-color: #eafaf1; }
    .processed { color: #196f3d; font-weight: bold; }
    .error-row { color: #b03a2e; font-weight: bold; }
    .summary p { font-size: 15px; margin: 6px 0; }
</style>
</head>
<body>

<h1>Payroll Exception Handling</h1>

<div class="container">
    <h2>Payroll Processing Report</h2>
    <table>
        <tr><th>Employee</th><th>Status</th><th>Salary / Error</th></tr>
        <?php foreach ($payrollReport as $row) : ?>
        <tr>
            <td><?php echo $row["name"]; ?></td>
            <td class="<?php echo ($row["status"] == 'Processed') ? 'processed' : 'error-row'; ?>"><?php echo $row["status"]; ?></td>
            <td><?php echo $row["detail"]; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container summary">
    <h2>Summary</h2>
    <p>Total Records: <?php echo count($payrollReport); ?></p>
    <p>Successfully Processed: <?php echo $processedCount; ?></p>
    <p>Errors Encountered: <?php echo $errorCount; ?></p>
    <p>Payroll processing completed without interruption despite errors.</p>
</div>

</body>
</html>