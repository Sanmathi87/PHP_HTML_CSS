<?php
// Task 21: Product Sorting Application

$products = [
    ["name" => "Wireless Mouse", "price" => 799],
    ["name" => "Mechanical Keyboard", "price" => 3499],
    ["name" => "USB-C Hub", "price" => 1299],
    ["name" => "Laptop Stand", "price" => 999],
    ["name" => "Webcam HD", "price" => 2199],
    ["name" => "Bluetooth Speaker", "price" => 1799]
];

$order = isset($_POST["order"]) ? $_POST["order"] : "asc";

usort($products, function ($a, $b) use ($order) {
    if ($order == "desc") {
        return $b["price"] <=> $a["price"];
    }
    return $a["price"] <=> $b["price"];
});

$prices = array_column($products, "price");
$highestPrice = max($prices);
$lowestPrice = min($prices);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Product Sorting Application</title>
<style>
    body { font-family: Arial, sans-serif; background-color: #fef9e7; display: flex; justify-content: center; padding: 40px 20px; margin: 0; }
    .container { background: #fff; max-width: 550px; width: 100%; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.12); }
    h1 { color: #9a7d0a; text-align: center; margin-bottom: 20px; font-size: 21px; }
    form { display: flex; gap: 10px; margin-bottom: 20px; justify-content: center; }
    select { padding: 9px; border: 1px solid #f7dc6f; border-radius: 6px; font-size: 14px; }
    button { background-color: #b7950b; color: white; border: none; padding: 9px 16px; border-radius: 6px; font-size: 14px; cursor: pointer; }
    button:hover { background-color: #9a7d0a; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { padding: 10px; text-align: center; border: 1px solid #f7dc6f; }
    th { background-color: #b7950b; color: white; }
    tr:nth-child(even) { background-color: #fef9e7; }
    .summary { margin-top: 15px; font-size: 14px; }
</style>
</head>
<body>

<div class="container">
    <h1>Product Sorting Application</h1>

    <form method="POST" action="">
        <select name="order">
            <option value="asc" <?php echo ($order == 'asc') ? 'selected' : ''; ?>>Price: Low to High</option>
            <option value="desc" <?php echo ($order == 'desc') ? 'selected' : ''; ?>>Price: High to Low</option>
        </select>
        <button type="submit">Sort</button>
    </form>

    <table>
        <tr><th>Product</th><th>Price (₹)</th></tr>
        <?php foreach ($products as $product) : ?>
        <tr>
            <td><?php echo $product["name"]; ?></td>
            <td><?php echo number_format($product["price"]); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="summary">
        <p>Highest Priced Item: ₹<?php echo number_format($highestPrice); ?></p>
        <p>Lowest Priced Item: ₹<?php echo number_format($lowestPrice); ?></p>
    </div>
</div>

</body>
</html>