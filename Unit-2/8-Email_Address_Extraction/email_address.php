<?php
// Task 8: Email Address Extraction Using Regular Expressions

$extractedEmails = [];
$inputText = "";
$submitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted = true;
    $inputText = $_POST["records"];

    $pattern = '/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/';
    preg_match_all($pattern, $inputText, $matches);
    $extractedEmails = $matches[0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Email Address Extraction</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Email Address Extraction</h1>
    <p class="subtitle">Paste employee records below; valid email addresses will be extracted using regex.</p>

    <form method="POST" action="">
        <label for="records">Employee Records:</label>
        <textarea id="records" name="records" rows="6" placeholder="e.g. John Doe, john.doe@company.com, HR Dept" required><?php echo htmlspecialchars($inputText); ?></textarea>
        <button type="submit">Extract Emails</button>
    </form>

    <?php if ($submitted) : ?>
        <div class="result">
            <h2>Extracted Email Addresses (<?php echo count($extractedEmails); ?> found)</h2>
            <?php if (count($extractedEmails) > 0) : ?>
                <ul>
                    <?php foreach ($extractedEmails as $email) : ?>
                        <li><?php echo htmlspecialchars($email); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p class="error">No valid email addresses found in the given text.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>