<?php
// Task 13: Railway Reservation Waiting List System
session_start();

$totalSeats = 5;

if (!isset($_SESSION["confirmed"])) {
    $_SESSION["confirmed"] = [];   // seat number => passenger name
    $_SESSION["waitlist"] = [];    // queue of passenger names
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["book"]) && trim($_POST["passenger"]) != "") {
        $name = trim($_POST["passenger"]);

        if (count($_SESSION["confirmed"]) < $totalSeats) {
            // Assign the next free seat number
            for ($seat = 1; $seat <= $totalSeats; $seat++) {
                if (!isset($_SESSION["confirmed"][$seat])) {
                    $_SESSION["confirmed"][$seat] = $name;
                    $message = "$name booked with Confirmed Seat No. $seat.";
                    break;
                }
            }
        } else {
            array_push($_SESSION["waitlist"], $name);
            $position = count($_SESSION["waitlist"]);
            $message = "$name added to Waiting List (Position: WL$position).";
        }

    } elseif (isset($_POST["cancel"]) && isset($_POST["seat"])) {
        $seat = intval($_POST["seat"]);

        if (isset($_SESSION["confirmed"][$seat])) {
            $cancelledName = $_SESSION["confirmed"][$seat];
            unset($_SESSION["confirmed"][$seat]);

            if (count($_SESSION["waitlist"]) > 0) {
                // Promote first waiting passenger (FIFO) into the freed seat
                $promoted = array_shift($_SESSION["waitlist"]);
                $_SESSION["confirmed"][$seat] = $promoted;
                $message = "Cancelled $cancelledName (Seat $seat). $promoted promoted from waiting list to Seat $seat.";
            } else {
                $message = "Cancelled $cancelledName (Seat $seat). Seat now vacant.";
            }
        } else {
            $message = "Seat $seat has no confirmed passenger.";
        }

    } elseif (isset($_POST["reset"])) {
        $_SESSION["confirmed"] = [];
        $_SESSION["waitlist"] = [];
        $message = "Reservation system reset.";
    }
}

ksort($_SESSION["confirmed"]);
$confirmed = $_SESSION["confirmed"];
$waitlist = $_SESSION["waitlist"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Railway Reservation Waiting List System</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Railway Reservation Waiting List System</h1>
    <p class="subtitle">Total Seats: <?php echo $totalSeats; ?></p>

    <form method="POST" action="" class="inline-form">
        <input type="text" name="passenger" placeholder="Passenger name">
        <button type="submit" name="book">Book Ticket</button>
    </form>

    <form method="POST" action="" class="inline-form">
        <select name="seat">
            <?php for ($s = 1; $s <= $totalSeats; $s++) : ?>
                <option value="<?php echo $s; ?>">Seat <?php echo $s; ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit" name="cancel">Cancel Seat</button>
    </form>

    <form method="POST" action="">
        <button type="submit" name="reset" class="reset-btn">Reset System</button>
    </form>

    <?php if ($message != "") : ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <h2>Confirmed Seats</h2>
    <table>
        <tr><th>Seat No.</th><th>Passenger</th></tr>
        <?php for ($s = 1; $s <= $totalSeats; $s++) : ?>
        <tr>
            <td><?php echo $s; ?></td>
            <td><?php echo isset($confirmed[$s]) ? htmlspecialchars($confirmed[$s]) : "-- vacant --"; ?></td>
        </tr>
        <?php endfor; ?>
    </table>

    <h2>Waiting List</h2>
    <?php if (count($waitlist) > 0) : ?>
        <ol>
            <?php foreach ($waitlist as $i => $name) : ?>
                <li>WL<?php echo $i + 1; ?> - <?php echo htmlspecialchars($name); ?></li>
            <?php endforeach; ?>
        </ol>
    <?php else : ?>
        <p class="empty">Waiting list is empty.</p>
    <?php endif; ?>
</div>

</body>
</html>