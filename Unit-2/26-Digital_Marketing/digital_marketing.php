<?php
// Task 26: Digital Marketing Campaign Analysis

$campaigns = [
    "Instagram Ads" => ["impressions" => 50000, "clicks" => 2500, "conversions" => 180, "cost" => 15000],
    "Google Ads"    => ["impressions" => 80000, "clicks" => 4200, "conversions" => 310, "cost" => 22000],
    "Facebook Ads"  => ["impressions" => 60000, "clicks" => 2100, "conversions" => 140, "cost" => 12000],
    "Email Campaign"=> ["impressions" => 20000, "clicks" => 1800, "conversions" => 220, "cost" => 4000]
];

$report = [];
foreach ($campaigns as $name => $data) {
    $ctr = round(($data["clicks"] / $data["impressions"]) * 100, 2);
    $conversionRate = round(($data["conversions"] / $data["clicks"]) * 100, 2);
    $costPerConversion = round($data["cost"] / $data["conversions"], 2);

    $report[$name] = [
        "impressions" => $data["impressions"],
        "clicks" => $data["clicks"],
        "conversions" => $data["conversions"],
        "cost" => $data["cost"],
        "ctr" => $ctr,
        "conversionRate" => $conversionRate,
        "costPerConversion" => $costPerConversion
    ];
}

// Identify best campaign by conversion rate
uasort($report, function ($a, $b) {
    return $b["conversionRate"] <=> $a["conversionRate"];
});

$bestCampaign = array_key_first($report);
$totalSpend = array_sum(array_column($campaigns, "cost"));
$totalConversions = array_sum(array_column($campaigns, "conversions"));
$overallCostPerConversion = round($totalSpend / $totalConversions, 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Digital Marketing Campaign Analysis</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #f4ecf7; margin: 0; padding: 30px; color: #4a235a; }
    h1 { text-align: center; color: #6c3483; }
    .container { max-width: 800px; margin: 0 auto 25px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 13px; }
    th, td { padding: 8px; text-align: center; border: 1px solid #e8daef; }
    th { background-color: #6c3483; color: white; }
    tr:nth-child(even) { background-color: #f4ecf7; }
    .best { background-color: #d7bde2; font-weight: bold; }
    .summary p { font-size: 15px; margin: 6px 0; }
</style>
</head>
<body>

<h1>Digital Marketing Campaign Analysis</h1>

<div class="container">
    <h2>Campaign Performance Report (Ranked by Conversion Rate)</h2>
    <table>
        <tr>
            <th>Campaign</th><th>Impressions</th><th>Clicks</th><th>CTR (%)</th>
            <th>Conversions</th><th>Conv. Rate (%)</th><th>Cost (₹)</th><th>Cost/Conversion (₹)</th>
        </tr>
        <?php foreach ($report as $name => $data) : ?>
        <tr class="<?php echo ($name == $bestCampaign) ? 'best' : ''; ?>">
            <td><?php echo $name; ?></td>
            <td><?php echo number_format($data["impressions"]); ?></td>
            <td><?php echo number_format($data["clicks"]); ?></td>
            <td><?php echo $data["ctr"]; ?>%</td>
            <td><?php echo $data["conversions"]; ?></td>
            <td><?php echo $data["conversionRate"]; ?>%</td>
            <td><?php echo number_format($data["cost"]); ?></td>
            <td><?php echo number_format($data["costPerConversion"], 2); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container summary">
    <h2>Key Performance Indicators (KPIs)</h2>
    <p>Best Performing Campaign (by conversion rate): <strong><?php echo $bestCampaign; ?></strong></p>
    <p>Total Marketing Spend: ₹<?php echo number_format($totalSpend); ?></p>
    <p>Total Conversions: <?php echo $totalConversions; ?></p>
    <p>Overall Cost per Conversion: ₹<?php echo number_format($overallCostPerConversion, 2); ?></p>
</div>

</body>
</html>