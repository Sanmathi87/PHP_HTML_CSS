<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>BMI Calculator</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            background: #f1f8e9;
            padding: 40px 15px;
        }

        .container {
            background: white;
            max-width: 450px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 0.95rem;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background: #2e7d32;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
        }

        button:hover {
            background: #1b5e20;
        }

        .result {
            margin-top: 25px;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .result.underweight { background: #e3f2fd; }
        .result.normal { background: #e8f5e9; }
        .result.overweight { background: #fff3e0; }
        .result.obese { background: #ffebee; }

        .bmi-value {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .status {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .recommendation {
            font-size: 0.9rem;
            color: #555;
            text-align: left;
            line-height: 1.5;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>BMI Calculator</h1>

        <form method="POST">
            <label>Height (in cm)</label>
            <input type="number" name="height" step="0.1" min="1" required>

            <label>Weight (in kg)</label>
            <input type="number" name="weight" step="0.1" min="1" required>

            <button type="submit">Calculate BMI</button>
        </form>

        <?php
            // Function to calculate BMI
            function calculateBMI($weight, $heightCm) {
                $heightM = $heightCm / 100;
                return $weight / ($heightM * $heightM);
            }

            // Function to determine health status based on BMI
            function getHealthStatus($bmi) {
                if ($bmi < 18.5) {
                    return "Underweight";
                } elseif ($bmi < 25) {
                    return "Normal";
                } elseif ($bmi < 30) {
                    return "Overweight";
                } else {
                    return "Obese";
                }
            }

            // Function to give recommendation based on status
            function getRecommendation($status) {
                switch ($status) {
                    case "Underweight":
                        return "Consider a nutrient-rich diet with more calories, proteins, and healthy fats. A doctor or dietitian can help build a suitable plan.";
                    case "Normal":
                        return "Great! Maintain your current lifestyle with balanced nutrition and regular physical activity.";
                    case "Overweight":
                        return "Consider incorporating regular exercise and a balanced diet with controlled portions. Consult a healthcare provider for personalized guidance.";
                    case "Obese":
                        return "It is advisable to consult a healthcare professional for a structured diet and exercise plan tailored to your needs.";
                    default:
                        return "";
                }
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $height = floatval($_POST['height'] ?? 0);
                $weight = floatval($_POST['weight'] ?? 0);

                if ($height > 0 && $weight > 0) {
                    $bmi = calculateBMI($weight, $height);
                    $status = getHealthStatus($bmi);
                    $recommendation = getRecommendation($status);
                    $cssClass = strtolower($status);

                    echo '<div class="result ' . $cssClass . '">';
                    echo '<div class="bmi-value">' . number_format($bmi, 1) . '</div>';
                    echo '<div class="status">' . $status . '</div>';
                    echo '<div class="recommendation">' . $recommendation . '</div>';
                    echo '</div>';
                } else {
                    echo '<p style="color:red; margin-top:15px;">Please enter valid height and weight.</p>';
                }
            }
        ?>
    </div>

</body>
</html>