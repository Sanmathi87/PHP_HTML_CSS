<?php
// Task 19: Banking Transaction Exception Handling

$submitted = false;
$resultMessage = "";
$resultClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted = true;

    $balance = $_POST["balance"];
    $withdrawAmount = $_POST["withdraw"];
    $installments = $_POST["installments"];

    try {
        if (!is_numeric($balance) || !is_numeric($withdrawAmount) || !is_numeric($installments)) {
            throw new InvalidArgumentException("All fields must contain valid numeric values.");
        }

        $balance = floatval($balance);
        $withdrawAmount = floatval($withdrawAmount);
        $installments = intval($installments);

        if ($withdrawAmount > $balance) {
            throw new Exception("Insufficient balance for this withdrawal.");
        }

        if ($installments == 0) {
            throw new DivisionByZeroError("Number of installments cannot be zero.");
        }

        $newBalance = $balance - $withdrawAmount;
        $perInstallment = round($withdrawAmount / $installments, 2);

        $resultMessage = "Transaction successful. Withdrawn ₹" . number_format($withdrawAmount, 2)
            . " in " . $installments . " installment(s) of ₹" . number_format($perInstallment, 2)
            . " each. Remaining balance: ₹" . number_format($newBalance, 2);
        $resultClass = "success";

    } catch (InvalidArgumentException $e) {
        $resultMessage = "Invalid Input: " . $e->getMessage();
        $resultClass = "error";
    } catch (DivisionByZeroError $e) {
        $resultMessage = "Calculation Error: " . $e->getMessage();
        $resultClass = "error";
    } catch (Exception $e) {
        $resultMessage = "Transaction Failed: " . $e->getMessage();
        $resultClass = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Banking Transaction Exception Handling</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #eaf2f8; display: flex; justify-content: center; padding: 40px 20px; margin: 0; }
    .container { background: #fff; max-width: 480px; width: 100%; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.12); }
    h1 { color: #1a5276; text-align: center; margin-bottom: 20px; font-size: 21px; }
    form { display: flex; flex-direction: column; }
    label { margin-bottom: 6px; font-weight: bold; color: #34495e; }
    input[type="text"] { padding: 10px; border: 1px solid #aed6f1; border-radius: 6px; font-size: 14px; margin-bottom: 15px; }
    button { background-color: #1a5276; color: white; border: none; padding: 10px; border-radius: 6px; font-size: 15px; cursor: pointer; }
    button:hover { background-color: #154360; }
    .message { margin-top: 18px; padding: 12px; border-radius: 6px; font-size: 14px; }
    .success { background-color: #d4efdf; color: #196f3d; border: 1px solid #a9dfbf; }
    .error { background-color: #fadbd8; color: #922b21; border: 1px solid #f5b7b1; }
</style>
</head>
<body>

<div class="container">
    <h1>Banking Transaction Exception Handling</h1>

    <form method="POST" action="">
        <label for="balance">Current Balance (₹):</label>
        <input type="text" id="balance" name="balance" placeholder="e.g. 10000">

        <label for="withdraw">Withdrawal Amount (₹):</label>
        <input type="text" id="withdraw" name="withdraw" placeholder="e.g. 3000">

        <label for="installments">Split into Installments:</label>
        <input type="text" id="installments" name="installments" placeholder="e.g. 3">

        <button type="submit">Process Transaction</button>
    </form>

    <?php if ($submitted) : ?>
        <div class="message <?php echo $resultClass; ?>">
            <?php echo $resultMessage; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>