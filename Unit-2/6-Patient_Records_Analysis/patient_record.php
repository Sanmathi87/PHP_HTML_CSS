<?php
// Task 6: Patient Records Analysis Using Multidimensional Arrays

$patients = [
    ["name" => "Ramesh", "age" => 45, "department" => "Cardiology", "treatment" => "Angioplasty"],
    ["name" => "Lakshmi", "age" => 32, "department" => "Orthopedics", "treatment" => "Physiotherapy"],
    ["name" => "Vijay",   "age" => 60, "department" => "Cardiology", "treatment" => "Bypass Surgery"],
    ["name" => "Anjali",  "age" => 28, "department" => "Dermatology", "treatment" => "Skin Treatment"],
    ["name" => "Suresh",  "age" => 50, "department" => "Orthopedics", "treatment" => "Knee Replacement"],
    ["name" => "Deepa",   "age" => 40, "department" => "Cardiology", "treatment" => "ECG Monitoring"]
];

// Department-wise grouping
$departments = [];
foreach ($patients as $p) {
    $departments[$p["department"]][] = $p;
}

// Department-wise patient count and average age
$deptReport = [];
foreach ($departments as $dept => $list) {
    $totalAge = 0;
    foreach ($list as $p) {
        $totalAge += $p["age"];
    }
    $deptReport[$dept] = [
        "count" => count($list),
        "avgAge" => round($totalAge / count($list), 1)
    ];
}

// Treatment statistics (count of each treatment type)
$treatmentStats = [];
foreach ($patients as $p) {
    $treatmentStats[$p["treatment"]] = ($treatmentStats[$p["treatment"]] ?? 0) + 1;
}

$totalPatients = count($patients);
$overallAvgAge = round(array_sum(array_column($patients, "age")) / $totalPatients, 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Patient Records Analysis</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Patient Records Analysis</h1>

<div class="container">
    <h2>All Patient Records</h2>
    <table>
        <tr><th>Name</th><th>Age</th><th>Department</th><th>Treatment</th></tr>
        <?php foreach ($patients as $p) : ?>
        <tr>
            <td><?php echo $p["name"]; ?></td>
            <td><?php echo $p["age"]; ?></td>
            <td><?php echo $p["department"]; ?></td>
            <td><?php echo $p["treatment"]; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container">
    <h2>Department-wise Report</h2>
    <table>
        <tr><th>Department</th><th>Patient Count</th><th>Average Age</th></tr>
        <?php foreach ($deptReport as $dept => $data) : ?>
        <tr>
            <td><?php echo $dept; ?></td>
            <td><?php echo $data["count"]; ?></td>
            <td><?php echo $data["avgAge"]; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container">
    <h2>Treatment Statistics</h2>
    <table>
        <tr><th>Treatment</th><th>Number of Patients</th></tr>
        <?php foreach ($treatmentStats as $treatment => $count) : ?>
        <tr>
            <td><?php echo $treatment; ?></td>
            <td><?php echo $count; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container summary">
    <h2>Overall Summary</h2>
    <p>Total Patients: <?php echo $totalPatients; ?></p>
    <p>Overall Average Age: <?php echo $overallAvgAge; ?></p>
</div>

</body>
</html>