<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Performance Evaluation</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',Arial,sans-serif; }
body { background:#fff3e0; padding:40px 15px; }
.container { background:white; max-width:450px; margin:0 auto; padding:30px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
h1 { text-align:center; color:#e65100; margin-bottom:20px; font-size:1.5rem; }
label { display:block; margin-top:15px; margin-bottom:5px; font-weight:bold; color:#333; }
input { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; }
button { width:100%; margin-top:20px; padding:12px; background:#e65100; color:white; border:none; border-radius:6px; font-size:1rem; cursor:pointer; }
button:hover { background:#bf360c; }
.result { margin-top:25px; padding:20px; border-radius:8px; text-align:center; }
.result.outstanding { background:#e8f5e9; }
.result.good { background:#e3f2fd; }
.result.average { background:#fff3e0; }
.result.poor { background:#ffebee; }
.score { font-size:2rem; font-weight:bold; margin-bottom:5px; }
.rating { font-size:1.2rem; font-weight:bold; }
</style>
</head>
<body>
<div class="container">
<h1>Employee Performance Evaluation</h1>
<form method="POST">
<label>Employee Name</label>
<input type="text" name="emp_name" required>
<label>Quality of Work (out of 25)</label>
<input type="number" name="quality" min="0" max="25" required>
<label>Punctuality (out of 25)</label>
<input type="number" name="punctuality" min="0" max="25" required>
<label>Teamwork (out of 25)</label>
<input type="number" name="teamwork" min="0" max="25" required>
<label>Initiative (out of 25)</label>
<input type="number" name="initiative" min="0" max="25" required>
<button type="submit">Evaluate</button>
</form>

<?php
function calculateTotalScore($quality, $punctuality, $teamwork, $initiative) {
    return $quality + $punctuality + $teamwork + $initiative;
}

function determineRating($score) {
    if ($score >= 90) {
        return ["label" => "Outstanding", "class" => "outstanding"];
    } elseif ($score >= 75) {
        return ["label" => "Good", "class" => "good"];
    } elseif ($score >= 50) {
        return ["label" => "Average", "class" => "average"];
    } else {
        return ["label" => "Needs Improvement", "class" => "poor"];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_name = trim($_POST['emp_name'] ?? '');
    $quality = intval($_POST['quality'] ?? 0);
    $punctuality = intval($_POST['punctuality'] ?? 0);
    $teamwork = intval($_POST['teamwork'] ?? 0);
    $initiative = intval($_POST['initiative'] ?? 0);

    if (empty($emp_name)) {
        echo '<p style="color:red;margin-top:15px;">Please enter employee name.</p>';
    } else {
        $total = calculateTotalScore($quality, $punctuality, $teamwork, $initiative);
        $rating = determineRating($total);

        echo '<div class="result ' . $rating['class'] . '">';
        echo '<div class="score">' . $total . ' / 100</div>';
        echo '<div class="rating">' . htmlspecialchars($emp_name) . ' - ' . $rating['label'] . '</div>';
        echo '</div>';
    }
}
?>
</div>
</body>
</html>