<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Salary Processing</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI',Arial,sans-serif; }
body { background:#e8eaf6; padding:40px 15px; }
.container { background:white; max-width:480px; margin:0 auto; padding:30px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
h1 { text-align:center; color:#283593; margin-bottom:20px; font-size:1.5rem; }
label { display:block; margin-top:15px; margin-bottom:5px; font-weight:bold; color:#333; }
input { width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; }
button { width:100%; margin-top:20px; padding:12px; background:#283593; color:white; border:none; border-radius:6px; font-size:1rem; cursor:pointer; }
button:hover { background:#1a237e; }
.summary { margin-top:25px; border-top:2px dashed #ccc; padding-top:15px; }
.summary p { display:flex; justify-content:space-between; margin-bottom:8px; }
.net { font-size:1.2rem; font-weight:bold; color:#283593; border-top:1px solid #ccc; padding-top:10px; }
</style>
</head>
<body>
<div class="container">
<h1>Employee Salary Processing</h1>
<form method="POST">
<label>Employee Name</label>
<input type="text" name="emp_name" required>
<label>Basic Salary (₹)</label>
<input type="number" name="basic" min="0" step="0.01" required>
<label>HRA (₹)</label>
<input type="number" name="hra" min="0" step="0.01" required>
<label>Other Allowances (₹)</label>
<input type="number" name="allowances" min="0" step="0.01" value="0">
<button type="submit">Calculate Salary</button>
</form>

<?php
function calculateGrossSalary($basic, $hra, $allowances) {
    return $basic + $hra + $allowances;
}

function calculateDeductions($basic) {
    $pf = $basic * 0.12;
    $professionalTax = 200;
    $incomeTax = ($basic > 50000) ? $basic * 0.05 : 0;
    return ["pf" => $pf, "pt" => $professionalTax, "tax" => $incomeTax, "total" => $pf + $professionalTax + $incomeTax];
}

function calculateNetSalary($gross, $deductions) {
    return $gross - $deductions['total'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_name = trim($_POST['emp_name'] ?? '');
    $basic = floatval($_POST['basic'] ?? 0);
    $hra = floatval($_POST['hra'] ?? 0);
    $allowances = floatval($_POST['allowances'] ?? 0);

    if (empty($emp_name) || $basic <= 0) {
        echo '<p style="color:red;margin-top:15px;">Please enter valid details.</p>';
    } else {
        $gross = calculateGrossSalary($basic, $hra, $allowances);
        $deductions = calculateDeductions($basic);
        $net = calculateNetSalary($gross, $deductions);

        echo '<div class="summary">';
        echo '<p><span>Employee:</span><span>' . htmlspecialchars($emp_name) . '</span></p>';
        echo '<p><span>Basic Salary:</span><span>₹' . number_format($basic,2) . '</span></p>';
        echo '<p><span>HRA:</span><span>₹' . number_format($hra,2) . '</span></p>';
        echo '<p><span>Other Allowances:</span><span>₹' . number_format($allowances,2) . '</span></p>';
        echo '<p><span>Gross Salary:</span><span>₹' . number_format($gross,2) . '</span></p>';
        echo '<p><span>PF (12%):</span><span>-₹' . number_format($deductions['pf'],2) . '</span></p>';
        echo '<p><span>Professional Tax:</span><span>-₹' . number_format($deductions['pt'],2) . '</span></p>';
        echo '<p><span>Income Tax:</span><span>-₹' . number_format($deductions['tax'],2) . '</span></p>';
        echo '<p class="net"><span>Net Salary:</span><span>₹' . number_format($net,2) . '</span></p>';
        echo '</div>';
    }
}
?>
</div>
</body>
</html>