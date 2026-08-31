<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Generator</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
        body { background: #263238; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .container { background: white; max-width: 420px; width: 100%; padding: 30px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.3); }
        h1 { text-align: center; color: #263238; margin-bottom: 20px; font-size: 1.5rem; }
        label { display: block; margin-top: 15px; margin-bottom: 5px; font-weight: bold; color: #333; }
        input[type="number"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; }
        .checkbox-row { display: flex; align-items: center; gap: 8px; margin-top: 10px; }
        button { width: 100%; margin-top: 20px; padding: 12px; background: #00838f; color: white; border: none; border-radius: 6px; font-size: 1rem; cursor: pointer; }
        button:hover { background: #005662; }
        .result { margin-top: 20px; padding: 15px; background: #e0f7fa; border-radius: 8px; word-break: break-all; font-family: monospace; font-size: 1.1rem; text-align: center; color: #00695c; font-weight: bold; }
    </style>
</head>
<body>

    <div class="container">
        <h1>🔐 Password Generator</h1>

        <form method="POST">
            <label>Password Length</label>
            <input type="number" name="length" min="6" max="32" value="12" required>

            <div class="checkbox-row">
                <input type="checkbox" name="uppercase" id="uppercase" checked>
                <label for="uppercase" style="margin:0;">Include Uppercase</label>
            </div>
            <div class="checkbox-row">
                <input type="checkbox" name="lowercase" id="lowercase" checked>
                <label for="lowercase" style="margin:0;">Include Lowercase</label>
            </div>
            <div class="checkbox-row">
                <input type="checkbox" name="digits" id="digits" checked>
                <label for="digits" style="margin:0;">Include Digits</label>
            </div>
            <div class="checkbox-row">
                <input type="checkbox" name="special" id="special" checked>
                <label for="special" style="margin:0;">Include Special Characters</label>
            </div>

            <button type="submit">Generate Password</button>
        </form>

        <?php
            // Function to build character pool based on selected options
            function buildCharacterPool($upper, $lower, $digits, $special) {
                $pool = "";
                if ($upper)   $pool .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                if ($lower)   $pool .= "abcdefghijklmnopqrstuvwxyz";
                if ($digits)  $pool .= "0123456789";
                if ($special) $pool .= "!@#$%^&*()_+-=[]{}";
                return $pool;
            }

            // Function to generate password ensuring at least one char from each selected type
            function generatePassword($length, $upper, $lower, $digits, $special) {
                $password = "";
                $required = [];

                if ($upper)   $required[] = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1);
                if ($lower)   $required[] = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 1);
                if ($digits)  $required[] = substr(str_shuffle("0123456789"), 0, 1);
                if ($special) $required[] = substr(str_shuffle("!@#$%^&*()_+-=[]{}"), 0, 1);

                $pool = buildCharacterPool($upper, $lower, $digits, $special);
                if ($pool === "") return "";

                $remainingLength = $length - count($required);
                for ($i = 0; $i < $remainingLength; $i++) {
                    $password .= $pool[rand(0, strlen($pool) - 1)];
                }

                $password .= implode("", $required);
                $password = str_shuffle($password); // shuffle so required chars aren't predictable

                return $password;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $length = intval($_POST['length'] ?? 12);
                $upper   = isset($_POST['uppercase']);
                $lower   = isset($_POST['lowercase']);
                $digits  = isset($_POST['digits']);
                $special = isset($_POST['special']);

                if (!$upper && !$lower && !$digits && !$special) {
                    echo '<p style="color:red; margin-top:15px;">Please select at least one character type.</p>';
                } else {
                    $password = generatePassword($length, $upper, $lower, $digits, $special);
                    echo '<div class="result">' . htmlspecialchars($password) . '</div>';
                }
            }
        ?>
    </div>

</body>
</html>