<?php
// Task 10: E-Commerce Customer Registration Validation

$errors = [];
$submitted = false;
$registered = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted = true;

    $fullName = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $pincode = trim($_POST["pincode"]);

    if (!preg_match('/^[A-Za-z ]{3,40}$/', $fullName)) {
        $errors[] = "Full Name must contain only letters and spaces (3-40 characters).";
    }

    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        $errors[] = "Email address format is invalid.";
    }

    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d).{6,}$/', $password)) {
        $errors[] = "Password must be at least 6 characters and include letters and numbers.";
    }

    if (!preg_match('/^\d{6}$/', $pincode)) {
        $errors[] = "Pincode must be exactly 6 digits.";
    }

    if (empty($errors)) {
        $registered = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>E-Commerce Customer Registration</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Customer Registration</h1>

    <form method="POST" action="">
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" placeholder="e.g. Arjun Reddy" required>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" placeholder="e.g. arjun@example.com" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="min 6 chars, letters + numbers" required>

        <label for="pincode">Pincode:</label>
        <input type="text" id="pincode" name="pincode" placeholder="e.g. 641004" required>

        <button type="submit">Register</button>
    </form>

    <?php if ($submitted) : ?>
        <?php if ($registered) : ?>
            <div class="message success">
                Registration successful! Your account has been created.
            </div>
        <?php else : ?>
            <div class="message error">
                <strong>Registration failed. Please fix the following:</strong>
                <ul>
                    <?php foreach ($errors as $err) : ?>
                        <li><?php echo $err; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>