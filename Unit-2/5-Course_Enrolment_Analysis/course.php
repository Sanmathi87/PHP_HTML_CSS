<?php
// Task 5: Course Enrolment Analysis

$enrolments = [
    "Web Development" => 120,
    "Data Science"     => 150,
    "UI/UX Design"      => 80,
    "Cloud Computing"   => 95,
    "Cyber Security"    => 110
];

$totalEnrolments = array_sum($enrolments);
$mostPopularCourse = array_search(max($enrolments), $enrolments);
$leastPopularCourse = array_search(min($enrolments), $enrolments);
$averageEnrolment = round($totalEnrolments / count($enrolments), 2);

// Percentage share of each course
$percentageShare = [];
foreach ($enrolments as $course => $count) {
    $percentageShare[$course] = round(($count / $totalEnrolments) * 100, 2);
}

arsort($enrolments);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Course Enrolment Analysis</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Course Enrolment Analysis</h1>

<div class="container">
    <h2>Enrolment Data (Sorted, Descending)</h2>
    <table>
        <tr><th>Course</th><th>Enrolments</th><th>Share (%)</th></tr>
        <?php foreach ($enrolments as $course => $count) : ?>
        <tr>
            <td><?php echo $course; ?></td>
            <td><?php echo $count; ?></td>
            <td><?php echo $percentageShare[$course]; ?>%</td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container summary">
    <h2>Enrolment Summary</h2>
    <p>Most Popular Course: <span class="top"><?php echo $mostPopularCourse; ?></span> (<?php echo $enrolments[$mostPopularCourse] ?? max($enrolments); ?> students)</p>
    <p>Least Popular Course: <?php echo $leastPopularCourse; ?></p>
    <p>Total Enrolments (all courses): <?php echo $totalEnrolments; ?></p>
    <p>Average Enrolment per Course: <?php echo $averageEnrolment; ?></p>
</div>

</body>
</html>