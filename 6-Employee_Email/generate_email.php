<?php

    // Function to clean and format name (remove spaces, special chars, lowercase)
    function cleanName($name) {
        $name = trim($name);
        $name = strtolower($name);
        $name = preg_replace('/[^a-z]/', '', $name); // keep only alphabets
        return $name;
    }

    // Function to generate email in firstname.lastname@domain format
    function generateEmail($firstName, $lastName, $domain) {
        return $firstName . "." . $lastName . "@" . $domain;
    }

    // Function to generate a short username-style email (first letter + lastname)
    function generateShortEmail($firstName, $lastName, $domain) {
        $initial = substr($firstName, 0, 1);
        return $initial . $lastName . "@" . $domain;
    }

    // Function to generate department-based email
    function generateDeptEmail($firstName, $lastName, $dept, $domain) {
        return $firstName . "." . $lastName . "." . $dept . "@" . $domain;
    }

    // Collect input
    $first_name_raw = trim($_POST['first_name'] ?? '');
    $last_name_raw  = trim($_POST['last_name'] ?? '');
    $department     = trim($_POST['department'] ?? '');
    $domain         = trim($_POST['domain'] ?? 'company.com');

    // Validation
    if (empty($first_name_raw) || empty($last_name_raw)) {
        echo "First name and last name are required. <a href='email_generator.html'>Go back</a>";
        exit();
    }

    // Clean names using string functions
    $first_name = cleanName($first_name_raw);
    $last_name = cleanName($last_name_raw);

    // Generate different email formats
    $standard_email = generateEmail($first_name, $last_name, $domain);
    $short_email     = generateShortEmail($first_name, $last_name, $domain);
    $dept_email      = generateDeptEmail($first_name, $last_name, $department, $domain);

    // Full name for display (using ucfirst for proper casing)
    $full_name = ucfirst($first_name_raw) . " " . ucfirst($last_name_raw);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generated Employee Email</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="result-container">
        <div class="icon-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#1a7a4c" stroke-width="1.5">
                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                <path d="M22 6l-10 7L2 6"></path>
                <path d="M2 18l6-5"></path>
                <path d="M22 18l-6-5"></path>
            </svg>
        </div>
        <h1>Employee Email Generated</h1>

        <p class="student-name">Employee Name: <strong><?php echo htmlspecialchars($full_name); ?></strong></p>

        <table>
            <tr>
                <th>Format</th>
                <th>Generated Email ID</th>
            </tr>
            <tr>
                <td>Standard</td>
                <td><?php echo htmlspecialchars($standard_email); ?></td>
            </tr>
            <tr>
                <td>Short</td>
                <td><?php echo htmlspecialchars($short_email); ?></td>
            </tr>
            <tr>
                <td>Department-based</td>
                <td><?php echo htmlspecialchars($dept_email); ?></td>
            </tr>
        </table>

        <div class="summary">
            <p><span>Recommended Email:</span> <span><?php echo htmlspecialchars($standard_email); ?></span></p>
        </div>
    </div>

</body>
</html>