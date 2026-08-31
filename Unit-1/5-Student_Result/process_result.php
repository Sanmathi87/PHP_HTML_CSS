<?php

    // Function to calculate total marks
    function calculateTotal($marksArray) {
        $total = 0;
        foreach ($marksArray as $mark) {
            $total += $mark;
        }
        return $total;
    }

    // Function to calculate average
    function calculateAverage($total, $count) {
        return $total / $count;
    }

    // Function to determine grade using decision-making statements
    function determineGrade($average) {
        if ($average >= 90) {
            return "A+";
        } elseif ($average >= 80) {
            return "A";
        } elseif ($average >= 70) {
            return "B";
        } elseif ($average >= 60) {
            return "C";
        } elseif ($average >= 50) {
            return "D";
        } elseif ($average >= 35) {
            return "E (Pass)";
        } else {
            return "F (Fail)";
        }
    }

    // Function to determine pass/fail status per subject
    function subjectStatus($mark) {
        return $mark >= 35 ? "Pass" : "Fail";
    }

    // Collect input
    $student_name = trim($_POST['student_name'] ?? '');
    $subjects = [
        "Tamil" => intval($_POST['marks1'] ?? 0),
        "English" => intval($_POST['marks2'] ?? 0),
        "Mathematics" => intval($_POST['marks3'] ?? 0),
        "Science" => intval($_POST['marks4'] ?? 0),
        "Social Science" => intval($_POST['marks5'] ?? 0),
    ];

    // Validation
    if (empty($student_name)) {
        echo "Student name is required. <a href='marks_form.html'>Go back</a>";
        exit();
    }

    $marksArray = array_values($subjects);
    $total = calculateTotal($marksArray);
    $average = calculateAverage($total, count($marksArray));
    $grade = determineGrade($average);
    $overallResult = ($grade === "F (Fail)") ? "FAIL" : "PASS";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="result-container">
        <h1>Student Result</h1>
        <p class="student-name">Name: <strong><?php echo htmlspecialchars($student_name); ?></strong></p>

        <table>
            <tr>
                <th>Subject</th>
                <th>Marks</th>
                <th>Status</th>
            </tr>
            <?php foreach ($subjects as $subjectName => $mark): ?>
            <tr>
                <td><?php echo $subjectName; ?></td>
                <td><?php echo $mark; ?></td>
                <td class="<?php echo (subjectStatus($mark) === 'Pass') ? 'pass' : 'fail'; ?>">
                    <?php echo subjectStatus($mark); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="summary">
            <p><span>Total Marks:</span> <span><?php echo $total; ?> / <?php echo count($marksArray) * 100; ?></span></p>
            <p><span>Average:</span> <span><?php echo number_format($average, 2); ?>%</span></p>
            <p><span>Grade:</span> <span><?php echo $grade; ?></span></p>
            <p class="overall <?php echo strtolower($overallResult); ?>">
                <span>Overall Result:</span> <span><?php echo $overallResult; ?></span>
            </p>
        </div>
    </div>

</body>
</html>