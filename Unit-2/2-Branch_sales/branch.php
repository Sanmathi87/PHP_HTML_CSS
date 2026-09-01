<?php
// Task 2: Branch-wise Sales Analysis Using Multidimensional Arrays

$months = ["Jan", "Feb", "Mar", "Apr"];

$branchSales = [
    "Coimbatore" => [120000, 135000, 110000, 145000],
    "Chennai"    => [200000, 190000, 210000, 225000],
    "Madurai"    => [90000, 95000, 100000, 98000],
    "Salem"      => [70000, 72000, 68000, 75000]
];

// Total sales per branch
$branchTotals = [];
foreach ($branchSales as $branch => $sales) {
    $branchTotals[$branch] = array_sum($sales);
}

// Highest and lowest performing branch
$highestBranch = array_search(max($branchTotals), $branchTotals);
$lowestBranch = array_search(min($branchTotals), $branchTotals);

// Month-wise total across all branches
$monthTotals = array_fill(0, count($months), 0);
foreach ($branchSales as $sales) {
    foreach ($sales as $index => $value) {
        $monthTotals[$index] += $value;
    }
}

// Overall average sale per branch
$overallAverage = round(array_sum($branchTotals) / count($branchTotals), 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Branch-wise Sales Analysis</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Branch-wise Sales Analysis</h1>

<div class="container">
    <h2>Monthly Sales by Branch (₹)</h2>
    <table>
        <tr>
            <th>Branch</th>
            <?php foreach ($months as $m) : ?><th><?php echo $m; ?></th><?php endforeach; ?>
            <th>Total</th>
        </tr>
        <?php foreach ($branchSales as $branch => $sales) : ?>
        <tr>
            <td><?php echo $branch; ?></td>
            <?php foreach ($sales as $val) : ?><td><?php echo number_format($val); ?></td><?php endforeach; ?>
            <td><?php echo number_format($branchTotals[$branch]); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container">
    <h2>Consolidated Report</h2>
    <p>Best performing branch: <span class="highlight"><?php echo $highestBranch; ?></span> (₹<?php echo number_format($branchTotals[$highestBranch]); ?>)</p>
    <p>Lowest performing branch: <span class="low"><?php echo $lowestBranch; ?></span> (₹<?php echo number_format($branchTotals[$lowestBranch]); ?>)</p>
    <p>Overall average sales per branch: ₹<?php echo number_format($overallAverage); ?></p>

    <h2>Month-wise Total Sales (All Branches)</h2>
    <table>
        <tr>
            <?php foreach ($months as $m) : ?><th><?php echo $m; ?></th><?php endforeach; ?>
        </tr>
        <tr>
            <?php foreach ($monthTotals as $t) : ?><td>₹<?php echo number_format($t); ?></td><?php endforeach; ?>
        </tr>
    </table>
</div>

</body>
</html>