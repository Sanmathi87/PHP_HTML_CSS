<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Processing System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background: #fce4ec; padding: 40px 15px; }
        .container { background: white; max-width: 450px; margin: 0 auto; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #ad1457; margin-bottom: 20px; font-size: 1.5rem; }
        label { display: block; margin-top: 15px; margin-bottom: 5px; font-weight: bold; color: #333; }
        input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; }
        button { width: 100%; margin-top: 20px; padding: 12px; background: #ad1457; color: white; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; }
        button:hover { background: #880e4f; }
        .result { margin-top: 25px; padding: 20px; border-radius: 8px; text-align: center; }
        .result.eligible { background: #e8f5e9; }
        .result.condonation { background: #fff3e0; }
        .result.not-eligible { background: #ffebee; }
        .percentage { font-size: 2rem; font-weight: bold; margin-bottom: 5px; }
        .status { font-size: 1.1rem; font-weight: bold; margin-bottom: 10px; }
        .detail { font-size: 0.9rem; color: #555; }
    </style>
</head>
<body>

    <div class="container">
        <h1>📋 Attendance Processing System</h1>

        <form method="POST">
            <label>Student Name</label>
            <input type="text" name="student_name" required>

            <label>Total Classes Held</label>
            <input type="number" name="total_classes" min="1" required>

            <label>Classes Attended</label>
            <input type="number" name="attended_classes" min="0" required>

            <button type="submit">Process Attendance</button>
        </form>

        <?php
            // Function to calculate attendance percentage
            function calculateAttendancePercentage($attended, $total) {
                if ($total == 0) return 0;
                return ($attended / $total) * 100;
            }

            // Function to determine examination eligibility
            function determineEligibility($percentage) {
                if ($percentage >= 75) {
                    return ["status" => "Eligible", "class" => "eligible", "message" => "You meet the minimum 75% attendance requirement and are eligible to appear for the examination."];
                } elseif ($percentage >= 65) {
                    return ["status" => "Condonation Required", "class" => "condonation", "message" => "Your attendance is below 75%. You need to apply for condonation to be eligible for the examination."];
                } else {
                    return ["status" => "Not Eligible", "class" => "not-eligible", "message" => "Your attendance is below 65%. You are not eligible to appear for the examination this semester."];
                }
            }

            // Function to calculate classes needed to reach 75%
            function classesNeededFor75Percent($attended, $total) {
                $needed = ceil((0.75 * $total - $attended) / 0.25);
                return $needed > 0 ? $needed : 0;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $student_name = trim($_POST['student_name'] ?? '');
                $total = intval($_POST['total_classes'] ?? 0);
                $attended = intval($_POST['attended_classes'] ?? 0);

                if (empty($student_name) || $total <= 0 || $attended < 0 || $attended > $total) {
                    echo '<p style="color:red; margin-top:15px;">Please enter valid attendance details.</p>';
                } else {
                    $percentage = calculateAttendancePercentage($attended, $total);
                    $eligibility = determineEligibility($percentage);

                    echo '<div class="result ' . $eligibility['class'] . '">';
                    echo '<div class="percentage">' . number_format($percentage, 2) . '%</div>';
                    echo '<div class="status">' . $eligibility['status'] . '</div>';
                    echo '<p class="detail">' . $eligibility['message'] . '</p>';

                    if ($percentage < 75) {
                        $needed = classesNeededFor75Percent($attended, $total);
                        echo '<p class="detail" style="margin-top:10px;">Attend ' . $needed . ' more consecutive classes to reach 75%.</p>';
                    }

                    echo '</div>';
                }
            }
        ?>
    </div>

</body>
</html>