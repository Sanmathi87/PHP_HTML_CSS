<?php
    $customer_name = trim($_POST['customer_name'] ?? '');
    $product_names = $_POST['product_name'];
    $quantities    = $_POST['quantity'];
    $prices        = $_POST['price'];
    $discount_pct  = floatval($_POST['discount'] ?? 0);
    $tax_pct       = floatval($_POST['tax'] ?? 0);

    $items = [];
    $subtotal = 0;

    for ($i = 0; $i < count($product_names); $i++) {
        $name = trim($product_names[$i]);
        $qty  = intval($quantities[$i] ?? 0);
        $price = floatval($prices[$i] ?? 0);

        if ($name !== '' && $qty > 0 && $price > 0) {
            $total = $qty * $price;
            $subtotal += $total;
            $items[] = [
                'name' => $name,
                'qty' => $qty,
                'price' => $price,
                'total' => $total
            ];
        }
    }

    $discount_amount = ($subtotal * $discount_pct) / 100;
    $after_discount = $subtotal - $discount_amount;
    $tax_amount = ($after_discount * $tax_pct) / 100;
    $grand_total = $after_discount + $tax_amount;

    $invoice_no = "INV" . rand(10000, 99999);
    $date = date("d-m-Y H:i");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Invoice</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="invoice-container">
        <h1>Supermarket Invoice</h1>

        <div class="invoice-header">
            <p><strong>Invoice No:</strong> <?php echo $invoice_no; ?></p>
            <p><strong>Date:</strong> <?php echo $date; ?></p>
            <p><strong>Customer:</strong> <?php echo htmlspecialchars($customer_name); ?></p>
        </div>

        <table>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price (₹)</th>
                <th>Total (₹)</th>
            </tr>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo $item['qty']; ?></td>
                <td><?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo number_format($item['total'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="summary">
            <p><span>Subtotal:</span> <span>₹<?php echo number_format($subtotal, 2); ?></span></p>
            <p><span>Discount (<?php echo $discount_pct; ?>%):</span> <span>-₹<?php echo number_format($discount_amount, 2); ?></span></p>
            <p><span>Amount after Discount:</span> <span>₹<?php echo number_format($after_discount, 2); ?></span></p>
            <p><span>Tax / GST (<?php echo $tax_pct; ?>%):</span> <span>+₹<?php echo number_format($tax_amount, 2); ?></span></p>
            <p class="grand-total"><span>Grand Total:</span> <span>₹<?php echo number_format($grand_total, 2); ?></span></p>
        </div>

        <p class="thank-you">Thank you for shopping with us!</p>
    </div>

</body>
</html>