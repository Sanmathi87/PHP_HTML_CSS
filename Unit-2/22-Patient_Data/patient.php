<?php
// Task 22: Patient Data Processing with Validation and Exception Handling

$patients = [
    ["name" => "Ramesh", "age" => 45, "bloodGroup" => "O+"],
    ["name" => "Lakshmi", "age" => -5, "bloodGroup" => "A+"],    // invalid: negative age
    ["name" => "Vijay",   "age" => 60, "bloodGroup" => "XZ"],    // invalid: bad blood group
    ["name" => "Anjali",  "age" => 28, "bloodGroup" => "B-"],
    ["name" => "Suresh",  "age" => "abc", "bloodGroup" => "O-"], // invalid: non-numeric age
    ["name" => "Deepa",   "age" => 40, "bloodGroup" => "AB+"]
];

$validGroups = ["A+", "A-", "B+", "B-", "O+", "O-", "AB+", "AB-"];
$processed = [];

foreach ($patients as $p) {
    try {
        if (!is_numeric($p["age"])) {
            throw new InvalidArgumentException("Age must be numeric.");
        }
        if ($p["age"] < 0 || $p["age"] > 120) {
            throw new RangeException("Age must be between 0 and 120.");
        }
        if (!in_array($p["bloodGroup"], $validGroups)) {
            throw new UnexpectedValueException("Invalid blood group: " . $p["bloodGroup"]);
        }

        $processed[] = [
            "name" => $p["name"],
            "status" => "Valid",
            "detail" => "Age " . $p["age"] . ", Blood Group " . $p["bloodGroup"]
        ];

    } catch (Exception $e) {
        $processed[] = [
            "name" => $p["name"],
            "status" => "Rejected",
            "detail" => $e->getMessage()
        ];
        continue;
    }
}

$validCount = count(array_filter($processed, fn($r) => $r["status"] == "Valid"));
$rejectedCount = count($processed) - $validCount;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Patient Data Processing</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #eaf2f8; margin: 0; padding: 30px; color: #1b2631; }
    h1 { text-align: center; color: #1a5276; }
    .container { max-width: 650px; margin: 0 auto 25px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { padding: 10px; text-align: center; border: 1px solid #d6eaf8; }
    th { background-color: #1a5276; color: white; }
    tr:nth-child(even) { background-color: #eaf2f8; }
    .valid { color: #196f3d; font-weight: bold; }
    .rejected { color: #b03a2e; font-weight: bold; }
    .summary p { font-size: 15px; margin: 6px 0; }
</style>
</head>
<body>

<h1>Patient Data Processing with Validation</h1>

<div class="container">
    <h2>Processing Report</h2>
    <table>
        <tr><th>Patient</th><th>Status</th><th>Detail</th></tr>
        <?php foreach ($processed as $row) : ?>
        <tr>
            <td><?php echo $row["name"]; ?></td>
            <td class="<?php echo strtolower($row["status"]); ?>"><?php echo $row["status"]; ?></td>
            <td><?php echo $row["detail"]; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container summary">
    <h2>Summary</h2>
    <p>Total Records: <?php echo count($processed); ?></p>
    <p>Valid Records: <?php echo $validCount; ?></p>
    <p>Rejected Records: <?php echo $rejectedCount; ?></p>
    <p>Processing completed reliably despite invalid entries.</p>
</div>

</body>
</html>