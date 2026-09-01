<?php
// Task 3: Employee Password Validation Using Regular Expressions

$result = "";
$resultClass = "";
$enteredPassword = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredPassword = $_POST["password"];

    // Rules: min 8 chars, 1 uppercase, 1 lowercase, 1 digit, 1 special character
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/';

    if (empty($enteredPassword)) {
        $result = "Password field cannot be empty.";
        $resultClass = "error";
    } elseif (preg_match($pattern, $enteredPassword)) {
        $result = "Password is strong and meets all security rules.";
        $resultClass = "success";
    } else {
        $result = "Password is weak. It must have at least 8 characters, "
                 . "one uppercase letter, one lowercase letter, one digit, and one special character.";
        $resultClass = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Password Validation</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Employee Password Validation</h1>
    <p class="subtitle">Password must contain: 8+ characters, uppercase, lowercase, digit, special character</p>

    <form method="POST" action="">
        <label for="password">Enter Employee Password:</label>
        <input type="password" id="password" name="password" value="" required>
        <button type="submit">Validate</button>
    </form>

    <?php if ($result !== "") : ?>
        <div class="message <?php echo $resultClass; ?>">
            <?php echo $result; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>