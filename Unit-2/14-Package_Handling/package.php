<?php
// Task 14: Package Handling Workflow Using Stack and Queue

$packages = ["PKG-101", "PKG-102", "PKG-103", "PKG-104", "PKG-105"];

// STACK simulation (LIFO) - loading packages into a delivery van
// Last package loaded is the first one unloaded at the destination
$stack = [];
$stackLog = [];
foreach ($packages as $pkg) {
    array_push($stack, $pkg);
    $stackLog[] = "Loaded $pkg onto van (stack top: $pkg)";
}
$unloadOrder = [];
$stackCopy = $stack;
while (!empty($stackCopy)) {
    $unloadOrder[] = array_pop($stackCopy);
}

// QUEUE simulation (FIFO) - packages processed at the sorting center
// First package that arrives is the first one processed
$queue = [];
$queueLog = [];
foreach ($packages as $pkg) {
    array_push($queue, $pkg);
    $queueLog[] = "$pkg arrived at sorting center";
}
$processOrder = [];
$queueCopy = $queue;
while (!empty($queueCopy)) {
    $processOrder[] = array_shift($queueCopy);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Package Handling Workflow - Stack and Queue</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Package Handling Workflow</h1>

<div class="container">
    <h2>Stack Operation (LIFO) - Van Loading/Unloading</h2>
    <p class="note">Packages loaded onto the van, in order: <?php echo implode(", ", $packages); ?></p>
    <p><strong>Unloading order (last loaded, first unloaded):</strong></p>
    <ol>
        <?php foreach ($unloadOrder as $pkg) : ?>
            <li><?php echo $pkg; ?></li>
        <?php endforeach; ?>
    </ol>
</div>

<div class="container">
    <h2>Queue Operation (FIFO) - Sorting Center Processing</h2>
    <p class="note">Packages arriving at the sorting center, in order: <?php echo implode(", ", $packages); ?></p>
    <p><strong>Processing order (first arrived, first processed):</strong></p>
    <ol>
        <?php foreach ($processOrder as $pkg) : ?>
            <li><?php echo $pkg; ?></li>
        <?php endforeach; ?>
    </ol>
</div>

</body>
</html>