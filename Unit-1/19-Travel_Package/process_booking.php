<?php

    // Function to parse selected package details
    function parsePackage($packageString) {
        $parts = explode("|", $packageString);
        return [
            "name" => $parts[0] ?? "",
            "price" => $parts[1] ?? "",
            "duration" => $parts[2] ?? ""
        ];
    }

    // Function to validate booking data
    function validateBooking($data) {
        $errors = [];

        if (empty($data['fullname'])) $errors[] = "Full name is required.";
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
        if (empty($data['phone']) || !preg_match('/^[0-9]{10}$/', $data['phone'])) $errors[] = "Valid 10-digit phone number is required.";
        if (empty($data['package'])) $errors[] = "Please select a package.";
        if (empty($data['travelers']) || $data['travelers'] < 1) $errors[] = "Number of travelers must be at least 1.";
        if (empty($data['travel_date'])) $errors[] = "Travel date is required.";

        return $errors;
    }

    // Collect input
    $data = [
        'fullname'    => trim($_POST['fullname'] ?? ''),
        'email'       => trim($_POST['email'] ?? ''),
        'phone'       => trim($_POST['phone'] ?? ''),
        'package'     => trim($_POST['package'] ?? ''),
        'travelers'   => intval($_POST['travelers'] ?? 0),
        'travel_date' => trim($_POST['travel_date'] ?? ''),
    ];

    $errors = validateBooking($data);

    if (!empty($errors)) {
        header("Location: travel_form.html?error=" . urlencode(implode(" ", $errors)));
        exit();
    }

    $package = parsePackage($data['package']);
    $priceValue = floatval(str_replace(["₹", ","], "", $package['price']));
    $totalCost = $priceValue * $data['travelers'];

    $booking_id = "TRV" . rand(10000, 99999);
    $booking_date = date("d-m-Y");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="style9.css">
</head>
<body>

    <div class="container">
        <h1>✅ Booking Confirmed!</h1>
        <p class="booking-id">Booking ID: <strong><?php echo $booking_id; ?></strong></p>

        <table>
            <tr><th>Traveler Name</th><td><?php echo htmlspecialchars($data['fullname']); ?></td></tr>
            <tr><th>Email</th><td><?php echo htmlspecialchars($data['email']); ?></td></tr>
            <tr><th>Phone</th><td><?php echo htmlspecialchars($data['phone']); ?></td></tr>
            <tr><th>Package</th><td><?php echo htmlspecialchars($package['name']); ?></td></tr>
            <tr><th>Duration</th><td><?php echo htmlspecialchars($package['duration']); ?></td></tr>
            <tr><th>Number of Travelers</th><td><?php echo $data['travelers']; ?></td></tr>
            <tr><th>Travel Date</th><td><?php echo htmlspecialchars($data['travel_date']); ?></td></tr>
            <tr><th>Price per Person</th><td><?php echo htmlspecialchars($package['price']); ?></td></tr>
            <tr><th>Total Cost</th><td class="total-cost">₹<?php echo number_format($totalCost, 2); ?></td></tr>
            <tr><th>Booking Date</th><td><?php echo $booking_date; ?></td></tr>
        </table>

        <p class="note">A confirmation email will be sent shortly. Please save your Booking ID.</p>
        <a href="travel_form.html" class="back-btn">Book Another Package</a>
    </div>

</body>
</html>