<?php
// Task 1: Student Performance Analysis Using Multidimensional Arrays
// Stores semester marks, finds subject-wise toppers, class averages, report

$subjects = ["Maths", "Physics", "Chemistry", "English"];

$students = [
    "Anita"  => [85, 78, 92, 88],
    "Ravi"   => [72, 65, 70, 75],
    "Kavya"  => [90, 95, 88, 91],
    "Suresh" => [60, 58, 65, 70],
    "Priya"  => [78, 82, 79, 80]
];

// Subject-wise toppers
$toppers = [];
foreach ($subjects as $index => $subject) {
    $topScore = -1;
    $topStudent = "";
    foreach ($students as $name => $marks) {
        if ($marks[$index] > $topScore) {
            $topScore = $marks[$index];
            $topStudent = $name;
        }
    }
    $toppers[$subject] = ["name" => $topStudent, "score" => $topScore];
}

// Class average per subject
$subjectAverages = [];
foreach ($subjects as $index => $subject) {
    $total = 0;
    foreach ($students as $marks) {
        $total += $marks[$index];
    }
    $subjectAverages[$subject] = round($total / count($students), 2);
}

// Individual student totals and averages
$studentReport = [];
foreach ($students as $name => $marks) {
    $total = array_sum($marks);
    $avg = round($total / count($marks), 2);
    $studentReport[$name] = ["total" => $total, "average" => $avg];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Performance Analysis</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Student Performance Analysis</h1>

<div class="container">
    <h2>Semester Marks</h2>
    <table>
        <tr>
            <th>Student</th>
            <?php foreach ($subjects as $subject) : ?>
                <th><?php echo $subject; ?></th>
            <?php endforeach; ?>
            <th>Total</th>
            <th>Average</th>
        </tr>
        <?php foreach ($students as $name => $marks) : ?>
        <tr>
            <td><?php echo $name; ?></td>
            <?php foreach ($marks as $mark) : ?>
                <td><?php echo $mark; ?></td>
            <?php endforeach; ?>
            <td><?php echo $studentReport[$name]["total"]; ?></td>
            <td><?php echo $studentReport[$name]["average"]; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container">
    <h2>Subject-wise Toppers</h2>
    <table>
        <tr>
            <th>Subject</th>
            <th>Topper</th>
            <th>Score</th>
        </tr>
        <?php foreach ($toppers as $subject => $data) : ?>
        <tr>
            <td><?php echo $subject; ?></td>
            <td><?php echo $data["name"]; ?></td>
            <td><?php echo $data["score"]; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container">
    <h2>Class Average per Subject</h2>
    <table>
        <tr>
            <th>Subject</th>
            <th>Class Average</th>
        </tr>
        <?php foreach ($subjectAverages as $subject => $avg) : ?>
        <tr>
            <td><?php echo $subject; ?></td>
            <td><?php echo $avg; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>