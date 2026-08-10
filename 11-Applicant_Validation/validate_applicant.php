<?php

    // Function to validate email
    function validateEmail($email) {
        if (empty($email)) {
            return ["status" => false, "message" => "Email is required."];
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["status" => true, "message" => "Valid email address."];
        }
        return ["status" => false, "message" => "Invalid email format."];
    }

    // Function to validate password
    function validatePassword($password) {
        if (empty($password)) {
            return ["status" => false, "message" => "Password is required."];
        }
        if (strlen($password) < 8) {
            return ["status" => false, "message" => "Password must be at least 8 characters long."];
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return ["status" => false, "message" => "Password must contain at least one uppercase letter."];
        }
        if (!preg_match('/[0-9]/', $password)) {
            return ["status" => false, "message" => "Password must contain at least one digit."];
        }
        if (!preg_match('/[\W_]/', $password)) {
            return ["status" => false, "message" => "Password must contain at least one special character."];
        }
        return ["status" => true, "message" => "Strong password."];
    }

    // Function to validate mobile number
    function validateMobile($mobile) {
        if (empty($mobile)) {
            return ["status" => false, "message" => "Mobile number is required."];
        }
        if (!preg_match('/^[0-9]+$/', $mobile)) {
            return ["status" => false, "message" => "Mobile number must contain digits only."];
        }
        if (strlen($mobile) !== 10) {
            return ["status" => false, "message" => "Mobile number must be exactly 10 digits."];
        }
        if (!preg_match('/^[6-9]/', $mobile)) {
            return ["status" => false, "message" => "Mobile number must start with 6, 7, 8, or 9."];
        }
        return ["status" => true, "message" => "Valid mobile number."];
    }

    // Collect input
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $mobile   = trim($_POST['mobile'] ?? '');

    // Validate each field
    $emailResult    = validateEmail($email);
    $passwordResult = validatePassword($password);
    $mobileResult   = validateMobile($mobile);

    $allValid = $emailResult['status'] && $passwordResult['status'] && $mobileResult['status'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Validation Result</title>
    <link rel="stylesheet" href="style4.css">
</head>
<body>

    <div class="container">
        <h1>Validation Result</h1>

        <div class="result-item <?php echo $emailResult['status'] ? 'valid' : 'invalid'; ?>">
            <span class="icon"><?php echo $emailResult['status'] ? '✔' : '✘'; ?></span>
            <div>
                <strong>Email:</strong> <?php echo htmlspecialchars($email); ?>
                <p><?php echo $emailResult['message']; ?></p>
            </div>
        </div>

        <div class="result-item <?php echo $passwordResult['status'] ? 'valid' : 'invalid'; ?>">
            <span class="icon"><?php echo $passwordResult['status'] ? '✔' : '✘'; ?></span>
            <div>
                <strong>Password:</strong> <?php echo str_repeat("*", strlen($password)); ?>
                <p><?php echo $passwordResult['message']; ?></p>
            </div>
        </div>

        <div class="result-item <?php echo $mobileResult['status'] ? 'valid' : 'invalid'; ?>">
            <span class="icon"><?php echo $mobileResult['status'] ? '✔' : '✘'; ?></span>
            <div>
                <strong>Mobile:</strong> <?php echo htmlspecialchars($mobile); ?>
                <p><?php echo $mobileResult['message']; ?></p>
            </div>
        </div>

        <div class="overall <?php echo $allValid ? 'valid' : 'invalid'; ?>">
            <?php echo $allValid ? "All details are valid!" : "Some details are invalid. Please correct and try again."; ?>
        </div>

        <a href="validation_form.html" class="back-btn">Try Again</a>
    </div>

</body>
</html>