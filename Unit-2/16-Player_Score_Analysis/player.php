<?php
// Task 16: Player Score Analysis

$playerScores = [
    "Arjun"  => 245,
    "Divya"  => 310,
    "Vikram" => 180,
    "Meena"  => 295,
    "Karthik"=> 260,
    "Nisha"  => 150
];

$scores = array_values($playerScores);

$highestScore = max($scores);
$lowestScore = min($scores);
$averageScore = round(array_sum($scores) / count($scores), 2);

$topPlayer = array_search($highestScore, $playerScores);
$bottomPlayer = array_search($lowestScore, $playerScores);

arsort($playerScores);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Player Score Analysis</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #eaf2f8; margin: 0; padding: 30px; color: #1b2631; }
    h1 { text-align: center; color: #1a5276; }
    .container { max-width: 650px; margin: 0 auto 25px auto; background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { padding: 10px; text-align: center; border: 1px solid #d6eaf8; }
    th { background-color: #1a5276; color: white; }
    tr:nth-child(even) { background-color: #eaf2f8; }
    .summary p { font-size: 15px; margin: 6px 0; }
    .high { color: #196f3d; font-weight: bold; }
    .low { color: #b03a2e; font-weight: bold; }
</style>
</head>
<body>

<h1>Player Score Analysis</h1>

<div class="container">
    <h2>Player Scores (Sorted, Descending)</h2>
    <table>
        <tr><th>Player</th><th>Score</th></tr>
        <?php foreach ($playerScores as $name => $score) : ?>
        <tr>
            <td><?php echo $name; ?></td>
            <td><?php echo $score; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="container summary">
    <h2>Score Summary</h2>
    <p>Highest Score: <span class="high"><?php echo $topPlayer; ?></span> - <?php echo $highestScore; ?></p>
    <p>Lowest Score: <span class="low"><?php echo $bottomPlayer; ?></span> - <?php echo $lowestScore; ?></p>
    <p>Average Score: <?php echo $averageScore; ?></p>
</div>

</body>
</html>