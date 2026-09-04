<?php
// Task 13: Hotel Stay Duration Calculator
$message = "";
$days = null;

if (isset($_POST['calculate'])) {
    $checkin = $_POST['checkin_date'];
    $checkout = $_POST['checkout_date'];

    try {
        $checkinDate = new DateTime($checkin);
        $checkoutDate = new DateTime($checkout);

        if ($checkoutDate <= $checkinDate) {
            throw new Exception("Check-out date must be after check-in date.");
        }

        $interval = $checkinDate->diff($checkoutDate);
        $days = $interval->days;
        $message = "Total stay duration calculated successfully.";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hotel Stay Duration Calculator</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 30px;
            color: #2c3e50;
        }
        
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        
        h2 {
            color: #1a5276;
            border-bottom: 2px solid #1a5276;
            padding-bottom: 10px;
        }
        
        h3, h4 {
            color: #2874a6;
            margin-top: 25px;
        }
        
        form {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin: 15px 0;
            padding: 15px;
            background: #f9fbfc;
            border: 1px solid #dfe6e9;
            border-radius: 6px;
        }
        
        label {
            font-weight: 600;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="password"],
        input[type="date"],
        input[type="number"],
        input[type="file"],
        select,
        textarea {
            padding: 8px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-size: 14px;
        }
        
        button {
            align-self: flex-start;
            background-color: #1a5276;
            color: white;
            border: none;
            padding: 9px 18px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 5px;
        }
        
        button:hover {
            background-color: #154360;
        }
        
        .message {
            padding: 12px 15px;
            border-radius: 5px;
            background-color: #eaf2f8;
            border-left: 4px solid #2980b9;
            margin: 15px 0;
        }
        
        .message.success {
            background-color: #eafaf1;
            border-left-color: #27ae60;
        }
        
        .message.error {
            background-color: #fdedec;
            border-left-color: #c0392b;
        }
        
        .article-box {
            background: #f9fbfc;
            border: 1px solid #dfe6e9;
            padding: 15px;
            border-radius: 6px;
            line-height: 1.6;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table, th, td {
            border: 1px solid #dfe6e9;
        }
        
        th, td {
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }
        
        th {
            background-color: #2874a6;
            color: white;
        }
        
        .nav-links {
            display: flex;
            gap: 12px;
            margin: 10px 0;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #1a5276;
            font-weight: 600;
        }
        
        .nav-links a:hover {
            text-decoration: underline;
        }
        
        ul {
            line-height: 1.8;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hotel Stay Duration Calculator</h2>
        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, 'Error') === 0 ? 'error' : 'success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Check-in Date:</label>
            <input type="date" name="checkin_date" required>
            <label>Check-out Date:</label>
            <input type="date" name="checkout_date" required>
            <button type="submit" name="calculate">Calculate Duration</button>
        </form>

        <?php if ($days !== null): ?>
            <div class="article-box">
                Total Duration of Stay: <strong><?php echo $days; ?> day(s)</strong>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
