<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Insurance Premium Calculator</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',Arial,sans-serif; }
body { background:#e0f7fa; padding:40px 15px; }
.container { background:white; max-width:480px; margin:0 auto; padding:30px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
h1 { text-align:center; color:#00796b; margin-bottom:20px; font-size:1.5rem; }
label { display:block; margin-top:15px; margin-bottom:5px; font-weight:bold; color:#333; }
input, select { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; }
button { width:100%; margin-top:20px; padding:12px; background:#00796b; color:white; border:none; border-radius:6px; font-size:1rem; cursor:pointer; }
button:hover { background:#004d40; }
.summary { margin-top:25px; border-top:2px dashed #ccc; padding-top:15px; }
.summary p { display:flex; justify-content:space-between; margin-bottom:8px; }
.premium { font-size:1.2rem; font-weight:bold; color:#00796b; border-top:1px solid #ccc; padding-top:10px; }
</style>
</head>
<body>
<div class="container">
<h1>Insurance Premium Calculator</h1>
<form method="POST">
<label>Applicant Name</label>
<input type="text" name="applicant_name" required>
<label>Age</label>
<input type="number" name="age" min="18" max="70" required>
<label>Policy Term (Years)</label>
<select name="term" required>
<option value="10">10 Years</option>
<option value="15">15 Years</option>
<option value="20">20 Years</option>
<option value="25">25 Years</option>
</select>
<label>Coverage Amount (₹)</label>
<input type="number" name="coverage" min="100000" step="1000" required>
<button type="submit">Calculate Premium</button>
</form>

<?php
function getAgeFactor($age) {
    if ($age < 25) return 0.02;
    elseif ($age < 35) return 0.03;
    elseif ($age < 45) return 0.045;
    elseif ($age < 55) return 0.06;
    else return 0.09;
}

function getTermDiscount($term) {
    if ($term >= 25) return 0.10;
    elseif ($term >= 20) return 0.07;
    elseif ($term >= 15) return 0.04;
    else return 0;
}

function calculatePremium($coverage, $ageFactor, $termDiscount, $term) {
    $basePremium = ($coverage * $ageFactor) / $term;
    $discountAmount = $basePremium * $termDiscount;
    return $basePremium - $discountAmount;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['applicant_name'] ?? '');
    $age = intval($_POST['age'] ?? 0);
    $term = intval($_POST['term'] ?? 10);
    $coverage = floatval($_POST['coverage'] ?? 0);

    if (empty($name) || $age < 18 || $age > 70 || $coverage <= 0) {
        echo '<p style="color:red;margin-top:15px;">Please enter valid details (age 18-70).</p>';
    } else {
        $ageFactor = getAgeFactor($age);
        $termDiscount = getTermDiscount($term);
        $annualPremium = calculatePremium($coverage, $ageFactor, $termDiscount, $term);
        $totalPayable = $annualPremium * $term;

        echo '<div class="summary">';
        echo '<p><span>Applicant:</span><span>' . htmlspecialchars($name) . '</span></p>';
        echo '<p><span>Age:</span><span>' . $age . ' years</span></p>';
        echo '<p><span>Policy Term:</span><span>' . $term . ' years</span></p>';
        echo '<p><span>Coverage Amount:</span><span>₹' . number_format($coverage,2) . '</span></p>';
        echo '<p><span>Term Discount:</span><span>' . ($termDiscount*100) . '%</span></p>';
        echo '<p class="premium"><span>Annual Premium:</span><span>₹' . number_format($annualPremium,2) . '</span></p>';
        echo '<p><span>Total Payable (over term):</span><span>₹' . number_format($totalPayable,2) . '</span></p>';
        echo '</div>';
    }
}
?>
</div>
</body>
</html>