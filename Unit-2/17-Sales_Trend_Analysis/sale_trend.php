<?php
// Task 17: Sales Trend Analysis

$monthlySales = [
    "Jan" => 85000,
    "Feb" => 92000,
    "Mar" => 88000,
    "Apr" => 97000,
    "May" => 105000,
    "Jun" => 101000
];

$months = array_keys($monthlySales);
$values = array_values($monthlySales);

// Calculate month-over-month growth percentage and trend
$growth = [];
$trend = [];
for ($i = 1; $i < count($values); $i++) {
    $prev = $values[$i - 1];
    $current = $values[$i];
    $percent = round((($current - $prev) / $prev) * 100, 2);
    $growth[$months[$i]] = $percent;

    if ($percent > 0) {
        $trend[$months[$i]] = "Rising";
    } elseif ($percent < 0) {
        $trend[$months[$i]] = "Falling";
    } else {
        $trend[$months[$i]] = "Stable";
    }
}

$overallGrowth = round((($values[count($values) - 1] - $values[0]) / $values[0]) * 100, 2);
$averageSales = round(array_sum($values) / count($values), 2);
$peakMonth = array_search(max($values), $monthlySales);
$lowMonth = array_search(min($values), $monthlySales);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sales Trend Analysis</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #f4ecf7; margin: 0; padding: 30px; color: #4a235a; }
    h1 { text-align: center; color: #6c3483; }
    .container { max-width: 700px; margin: 0 auto 25px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { padding: 10px; text-align: center; border: 1px solid #e8daef; }
    th { background-color: #6c3483; color: white; }
    tr:nth-child(even) { background-color: #f4ecf7; }
    .summary p { font-size: 15px; margin: 6px 0; }
    .rising { color: #196f3d; font-weight: bold; }
    .falling { color: #b03a2e; font-weight: bold; }
    .stable { color: #7d6608; font-weight: bold; }
</style>
</head>
<body>

<h1>Sales Trend Analysis</h1>

<div class="container">
    <h2>Monthly Sales (₹)</h2>
    <table>
        <tr><th>Month</th><th>Sales</th><th>Growth (%)</th><th>Trend</th></tr>
        <?php foreach ($monthlySales as $month => $sales) : ?>
        <tr>
            <td><?php echo $month; ?></td>
            <td><?php echo number_format($sales); ?></td>
            <td><?php echo isset($growth[$month]) ? $growth[$month] . "%" : "--"; ?></td>
            <td class="<?php echo isset($trend[$month]) ? strtolower($trend[$month]) : ''; ?>">
                <?php echo $trend[$month] ?? "--"; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container summary">
    <h2>Analysis Summary</h2>
    <p>Overall Growth (Jan to Jun): <?php echo $overallGrowth; ?>%</p>
    <p>Average Monthly Sales: ₹<?php echo number_format($averageSales); ?></p>
    <p>Peak Month: <?php echo $peakMonth; ?> (₹<?php echo number_format(max($values)); ?>)</p>
    <p>Lowest Month: <?php echo $lowMonth; ?> (₹<?php echo number_format(min($values)); ?>)</p>
</div>

</body>
</html>