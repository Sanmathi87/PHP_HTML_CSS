<?php
// Task 18: Stock Performance Analysis

$stocks = [
    "TCS"      => ["open" => 3450, "close" => 3512],
    "Infosys"  => ["open" => 1480, "close" => 1465],
    "Reliance" => ["open" => 2870, "close" => 2955],
    "HDFC Bank"=> ["open" => 1620, "close" => 1608],
    "Wipro"    => ["open" => 455,  "close" => 468]
];

$report = [];
foreach ($stocks as $name => $price) {
    $change = $price["close"] - $price["open"];
    $percentChange = round(($change / $price["open"]) * 100, 2);
    $report[$name] = [
        "open" => $price["open"],
        "close" => $price["close"],
        "change" => $change,
        "percent" => $percentChange,
        "status" => $change >= 0 ? "Gain" : "Loss"
    ];
}

// Sort by percentage change, descending (best performer first)
uasort($report, function ($a, $b) {
    return $b["percent"] <=> $a["percent"];
});

$bestPerformer = array_key_first($report);
$worstPerformer = array_key_last($report);
$avgPercentChange = round(array_sum(array_column($report, "percent")) / count($report), 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Stock Performance Analysis</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #eaeded; margin: 0; padding: 30px; color: #212f3d; }
    h1 { text-align: center; color: #212f3d; }
    .container { max-width: 700px; margin: 0 auto 25px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { padding: 10px; text-align: center; border: 1px solid #d5d8dc; }
    th { background-color: #212f3d; color: white; }
    tr:nth-child(even) { background-color: #f4f6f6; }
    .gain { color: #196f3d; font-weight: bold; }
    .loss { color: #b03a2e; font-weight: bold; }
    .summary p { font-size: 15px; margin: 6px 0; }
</style>
</head>
<body>

<h1>Stock Performance Analysis</h1>

<div class="container">
    <h2>Stock Report (Ranked by % Change)</h2>
    <table>
        <tr><th>Stock</th><th>Open (₹)</th><th>Close (₹)</th><th>Change (₹)</th><th>Change (%)</th><th>Status</th></tr>
        <?php foreach ($report as $name => $data) : ?>
        <tr>
            <td><?php echo $name; ?></td>
            <td><?php echo number_format($data["open"], 2); ?></td>
            <td><?php echo number_format($data["close"], 2); ?></td>
            <td><?php echo number_format($data["change"], 2); ?></td>
            <td><?php echo $data["percent"]; ?>%</td>
            <td class="<?php echo strtolower($data["status"]); ?>"><?php echo $data["status"]; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container summary">
    <h2>Investor Summary</h2>
    <p>Best Performer: <span class="gain"><?php echo $bestPerformer; ?></span> (<?php echo $report[$bestPerformer]["percent"]; ?>%)</p>
    <p>Worst Performer: <span class="loss"><?php echo $worstPerformer; ?></span> (<?php echo $report[$worstPerformer]["percent"]; ?>%)</p>
    <p>Average Change Across Portfolio: <?php echo $avgPercentChange; ?>%</p>
</div>

</body>
</html>