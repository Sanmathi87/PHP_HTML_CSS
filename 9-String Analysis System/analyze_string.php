<?php

    // Function to count vowels
    function countVowels($str) {
        $count = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            if (stripos("aeiou", $str[$i]) !== false) {
                $count++;
            }
        }
        return $count;
    }

    // Function to count consonants
    function countConsonants($str) {
        $count = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            $ch = $str[$i];
            if (ctype_alpha($ch) && stripos("aeiou", $ch) === false) {
                $count++;
            }
        }
        return $count;
    }

    // Function to count digits
    function countDigits($str) {
        $count = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            if (ctype_digit($str[$i])) {
                $count++;
            }
        }
        return $count;
    }

    // Function to count special characters (not letter, digit, or space)
    function countSpecialChars($str) {
        $count = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            $ch = $str[$i];
            if (!ctype_alnum($ch) && $ch !== ' ') {
                $count++;
            }
        }
        return $count;
    }

    // Collect input
    $title = trim($_POST['title'] ?? '');

    if (empty($title)) {
        echo "Please enter a title. <a href='string_form.html'>Go back</a>";
        exit();
    }

    // Perform analysis
    $totalLength   = strlen($title);
    $vowelCount    = countVowels($title);
    $consonantCount = countConsonants($title);
    $digitCount    = countDigits($title);
    $specialCount  = countSpecialChars($title);
    $spaceCount    = substr_count($title, ' ');
    $wordCount     = str_word_count($title);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Analysis Result</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>

    <div class="wrapper">
        <div class="box result-box">
            <h1>Analysis Result</h1>
            <p class="input-title">"<?php echo htmlspecialchars($title); ?>"</p>

            <div class="stats-grid">
                <div class="stat-card vowel">
                    <span class="stat-number"><?php echo $vowelCount; ?></span>
                    <span class="stat-label">Vowels</span>
                </div>
                <div class="stat-card consonant">
                    <span class="stat-number"><?php echo $consonantCount; ?></span>
                    <span class="stat-label">Consonants</span>
                </div>
                <div class="stat-card digit">
                    <span class="stat-number"><?php echo $digitCount; ?></span>
                    <span class="stat-label">Digits</span>
                </div>
                <div class="stat-card special">
                    <span class="stat-number"><?php echo $specialCount; ?></span>
                    <span class="stat-label">Special Characters</span>
                </div>
            </div>

            <div class="extra-info">
                <p><span>Total Characters:</span> <span><?php echo $totalLength; ?></span></p>
                <p><span>Word Count:</span> <span><?php echo $wordCount; ?></span></p>
                <p><span>Spaces:</span> <span><?php echo $spaceCount; ?></span></p>
            </div>

            <a href="string_form.html" class="back-btn">Analyze Another</a>
        </div>
    </div>

</body>
</html>