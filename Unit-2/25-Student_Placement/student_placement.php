<?php
// Task 25: Student Placement Statistics

$placements = [
    ["name" => "Anand",  "department" => "CSE", "package" => 8.5],
    ["name" => "Divya",  "department" => "ECE", "package" => 6.2],
    ["name" => "Karthik","department" => "CSE", "package" => 12.0],
    ["name" => "Meena",  "department" => "MECH","package" => 4.8],
    ["name" => "Rahul",  "department" => "CSE", "package" => 7.3],
    ["name" => "Sneha",  "department" => "ECE", "package" => 9.1],
    ["name" => "Vikram", "department" => "MECH","package" => 5.5]
];

// Department-wise grouping
$deptGroups = [];
foreach ($placements as $p) {
    $deptGroups[$p["department"]][] = $p;
}

// Department-wise average package and highest package
$deptReport = [];
foreach ($deptGroups as $dept => $students) {
    $packages = array_column($students, "package");
    $deptReport[$dept] = [
        "count" => count($students),
        "average" => round(array_sum($packages) / count($packages), 2),
        "highest" => max($packages)
    ];
}

// Sort departments by average package, descending (ranking)
uasort($deptReport, function ($a, $b) {
    return $b["average"] <=> $a["average"];
});

// Overall top placements (sorted by package, descending)
$topPlacements = $placements;
usort($topPlacements, function ($a, $b) {
    return $b["package"] <=> $a["package"];
});

$overallAverage = round(array_sum(array_column($placements, "package")) / count($placements), 2);
$highestOverall = max(array_column($placements, "package"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Placement Statistics</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #eaf2f8; margin: 0; padding: 30px; color: #1b2631; }
    h1 { text-align: center; color: #1a5276; }
    .container { max-width: 700px; margin: 0 auto 25px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { padding: 10px; text-align: center; border: 1px solid #d6eaf8; }
    th { background-color: #1a5276; color: white; }
    tr:nth-child(even) { background-color: #eaf2f8; }
    .rank { font-weight: bold; color: #1a5276; }
    .summary p { font-size: 15px; margin: 6px 0; }
</style>
</head>
<body>

<h1>Student Placement Statistics</h1>

<div class="container">
    <h2>Department-wise Ranking (by Average Package)</h2>
    <table>
        <tr><th>Rank</th><th>Department</th><th>Students Placed</th><th>Average Package (LPA)</th><th>Highest Package (LPA)</th></tr>
        <?php $rank = 1; foreach ($deptReport as $dept => $data) : ?>
        <tr>
            <td class="rank">#<?php echo $rank++; ?></td>
            <td><?php echo $dept; ?></td>
            <td><?php echo $data["count"]; ?></td>
            <td><?php echo $data["average"]; ?></td>
            <td><?php echo $data["highest"]; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container">
    <h2>Top Individual Placements</h2>
    <table>
        <tr><th>Student</th><th>Department</th><th>Package (LPA)</th></tr>
        <?php foreach ($topPlacements as $p) : ?>
        <tr>
            <td><?php echo $p["name"]; ?></td>
            <td><?php echo $p["department"]; ?></td>
            <td><?php echo $p["package"]; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container summary">
    <h2>Overall Summary</h2>
    <p>Total Students Placed: <?php echo count($placements); ?></p>
    <p>Overall Average Package: <?php echo $overallAverage; ?> LPA</p>
    <p>Highest Package Overall: <?php echo $highestOverall; ?> LPA</p>
</div>

</body>
</html>