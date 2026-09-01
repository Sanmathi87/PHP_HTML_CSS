<?php
// Task 4: Employee Salary Analysis Using Array Functions

$employees = [
    "Anand"  => 45000,
    "Divya"  => 62000,
    "Karthik"=> 38000,
    "Meena"  => 71000,
    "Rahul"  => 55000,
    "Sneha"  => 48000
];

$salaries = array_values($employees);

$highestSalary = max($salaries);
$lowestSalary = min($salaries);
$averageSalary = round(array_sum($salaries) / count($salaries), 2);

$highestEmployee = array_search($highestSalary, $employees);
$lowestEmployee = array_search($lowestSalary, $employees);

// Sort employees by salary descending, keep names
arsort($employees);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Salary Analysis</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Employee Salary Analysis</h1>

<div class="container">
    <h2>Employee List (Sorted by Salary, Descending)</h2>
    <table>
        <tr><th>Employee</th><th>Salary (₹)</th></tr>
        <?php foreach ($employees as $name => $sal) : ?>
        <tr>
            <td><?php echo $name; ?></td>
            <td><?php echo number_format($sal); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container summary">
    <h2>Summary Report</h2>
    <p>Highest Salary: <span class="high"><?php echo $highestEmployee; ?></span> - ₹<?php echo number_format($highestSalary); ?></p>
    <p>Lowest Salary: <span class="low"><?php echo $lowestEmployee; ?></span> - ₹<?php echo number_format($lowestSalary); ?></p>
    <p>Average Salary: ₹<?php echo number_format($averageSalary); ?></p>
</div>

</body>
</html>