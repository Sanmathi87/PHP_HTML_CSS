<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Calculator</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            background: #e8f0fe;
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
            color: #1565c0;
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
            background: #1565c0;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
        }

        button:hover {
            background: #0d47a1;
        }

        .result {
            margin-top: 25px;
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
        }

        .result p {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .total {
            font-size: 1.2rem;
            font-weight: bold;
            color: #1565c0;
            border-top: 1px solid #bbdefb;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Sales Calculator</h1>

        <form method="POST">
            <label>Product Name</label>
            <input type="text" name="product" required>

            <label>Quantity</label>
            <input type="number" name="quantity" min="1" required>

            <label>Price per Unit (₹)</label>
            <input type="number" name="price" min="0" step="0.01" required>

            <button type="submit">Calculate Total</button>
        </form>

        <?php
            // User-defined function to calculate total sales
            function calculateTotalSales($quantity, $price) {
                return $quantity * $price;
            }

            // User-defined function to format currency
            function formatCurrency($amount) {
                return "₹" . number_format($amount, 2);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $product  = trim($_POST['product'] ?? '');
                $quantity = intval($_POST['quantity'] ?? 0);
                $price    = floatval($_POST['price'] ?? 0);

                if (!empty($product) && $quantity > 0 && $price > 0) {
                    $total = calculateTotalSales($quantity, $price);
                    echo '<div class="result">';
                    echo '<p><span>Product:</span> <span>' . htmlspecialchars($product) . '</span></p>';
                    echo '<p><span>Quantity:</span> <span>' . $quantity . '</span></p>';
                    echo '<p><span>Price per Unit:</span> <span>' . formatCurrency($price) . '</span></p>';
                    echo '<p class="total"><span>Total Sales Value:</span> <span>' . formatCurrency($total) . '</span></p>';
                    echo '</div>';
                } else {
                    echo '<p style="color:red; margin-top:15px;">Please enter valid quantity and price.</p>';
                }
            }
        ?>
    </div>

</body>
</html>