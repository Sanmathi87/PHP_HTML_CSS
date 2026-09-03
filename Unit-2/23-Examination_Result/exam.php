<?php
// Task 23: Examination Result Exception Handling

$results = [
    ["name" => "Anita",  "marks" => 88],
    ["name" => "Ravi",   "marks" => 105],   // invalid: exceeds 100
    ["name" => "Kavya",  "marks" => 92],
    ["name" => "Suresh", "marks" => -12],   // invalid: negative marks
    ["name" => "Priya",  "marks" => "NA"],  // invalid: non-numeric
    ["name" => "Manoj",  "marks" => 76]
];

$errorLog = [];
$finalResults = [];

foreach ($results as $r) {
    try {
        if (!is_numeric($r["marks"])) {
            throw new InvalidArgumentException("Marks value is not numeric.");
        }
        if ($r["marks"] < 0 || $r["marks"] > 100) {
            throw new OutOfRangeException("Marks must be between 0 and 100.");
        }

        $grade = "F";
        if ($r["marks"] >= 90) $grade = "A+";
        elseif ($r["marks"] >= 75) $grade = "A";
        elseif ($r["marks"] >= 60) $grade = "B";
        elseif ($r["marks"] >= 40) $grade = "C";

        $finalResults[] = ["name" => $r["name"], "status" => "Processed", "detail" => $r["marks"] . " marks - Grade " . $grade];

    } catch (Exception $e) {
        $errorLog[] = $r["name"] . ": " . $e->getMessage();
        $finalResults[] = ["name" => $r["name"], "status" => "Error", "detail" => $e->getMessage()];
        continue;
    }
}

$processedCount = count($results) - count($errorLog);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Examination Result Exception Handling</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #f4ecf7; margin: 0; padding: 30px; color: #4a235a; }
    h1 { text-align: center; color: #6c3483; }
    .container { max-width: 650px; margin: 0 auto 25px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { padding: 10px; text-align: center; border: 1px solid #e8daef; }
    th { background-color: #6c3483; color: white; }
    tr:nth-child(even) { background-color: #f4ecf7; }
    .processed { color: #196f3d; font-weight: bold; }
    .error-row { color: #b03a2e; font-weight: bold; }
    .summary p { font-size: 15px; margin: 6px 0; }
    .error-log { background-color: #fadbd8; color: #922b21; border: 1px solid #f5b7b1; padding: 10px; border-radius: 6px; margin-top: 10px; font-size: 13px; }
</style>
</head>
<body>

<h1>Examination Result Exception Handling</h1>

<div class="container">
    <h2>Result Processing Report</h2>
    <table>
        <tr><th>Student</th><th>Status</th><th>Detail</th></tr>
        <?php foreach ($finalResults as $row) : ?>
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
    <p>Total Records: <?php echo count($results); ?></p>
    <p>Successfully Processed: <?php echo $processedCount; ?></p>
    <p>Errors Logged: <?php echo count($errorLog); ?></p>
    <?php if (count($errorLog) > 0) : ?>
        <div class="error-log">
            <strong>Error Log:</strong><br>
            <?php echo implode("<br>", $errorLog); ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>