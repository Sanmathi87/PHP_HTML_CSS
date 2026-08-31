<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Examination Result Analysis</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',Arial,sans-serif; }
body { background:#f3e5f5; padding:40px 15px; }
.container { background:white; max-width:480px; margin:0 auto; padding:30px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
h1 { text-align:center; color:#6a1b9a; margin-bottom:20px; font-size:1.5rem; }
label { display:block; margin-top:15px; margin-bottom:5px; font-weight:bold; color:#333; }
input { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; }
button { width:100%; margin-top:20px; padding:12px; background:#6a1b9a; color:white; border:none; border-radius:6px; font-size:1rem; cursor:pointer; }
button:hover { background:#4a148c; }
.result { margin-top:25px; padding:20px; border-radius:8px; text-align:center; background:#f3e5f5; }
.percentage { font-size:2rem; font-weight:bold; color:#6a1b9a; }
.class-obtained { font-size:1.2rem; font-weight:bold; margin-top:5px; }
</style>
</head>
<body>
<div class="container">
<h1>Examination Result Analysis</h1>
<form method="POST">
<label>Student Name</label>
<input type="text" name="student_name" required>
<label>Number of Subjects</label>
<input type="number" name="num_subjects" min="1" max="10" value="5" required>
<label>Total Marks Obtained</label>
<input type="number" name="marks_obtained" min="0" required>
<label>Maximum Marks (per subject)</label>
<input type="number" name="max_per_subject" min="1" value="100" required>
<button type="submit">Analyze Result</button>
</form>

<?php
function calculatePercentage($obtained, $maxTotal) {
    if ($maxTotal == 0) return 0;
    return ($obtained / $maxTotal) * 100;
}

function determineClass($percentage) {
    if ($percentage >= 75) {
        return "First Class with Distinction";
    } elseif ($percentage >= 60) {
        return "First Class";
    } elseif ($percentage >= 50) {
        return "Second Class";
    } elseif ($percentage >= 35) {
        return "Third Class (Pass)";
    } else {
        return "Fail";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_name = trim($_POST['student_name'] ?? '');
    $numSubjects = intval($_POST['num_subjects'] ?? 0);
    $obtained = floatval($_POST['marks_obtained'] ?? 0);
    $maxPerSubject = floatval($_POST['max_per_subject'] ?? 100);

    if (empty($student_name) || $numSubjects <= 0) {
        echo '<p style="color:red;margin-top:15px;">Please enter valid details.</p>';
    } else {
        $maxTotal = $numSubjects * $maxPerSubject;
        $percentage = calculatePercentage($obtained, $maxTotal);
        $classObtained = determineClass($percentage);

        echo '<div class="result">';
        echo '<p>' . htmlspecialchars($student_name) . '</p>';
        echo '<div class="percentage">' . number_format($percentage, 2) . '%</div>';
        echo '<div class="class-obtained">' . $classObtained . '</div>';
        echo '<p style="margin-top:10px;color:#777;">Marks: ' . $obtained . ' / ' . $maxTotal . '</p>';
        echo '</div>';
    }
}
?>
</div>
</body>
</html>