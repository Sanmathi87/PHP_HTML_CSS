<?php

$name = $_POST["name"] ?? ($_COOKIE["customer_name"] ?? "");
if (isset($_POST["save"])) {
    $name = trim($_POST["name"]);
    if ($name === "") $error = "Name is required.";
    else {
        setcookie("customer_name", $name, time()+86400*30, "/");
        $visits = (int)($_COOKIE["visits"] ?? 0) + 1;
        setcookie("visits", $visits, time()+86400*30, "/");
        header("Location: ".$_SERVER["PHP_SELF"]); exit;
    }
}
$visits = (int)($_COOKIE["visits"] ?? 0);
$greeting = $name ? "Welcome back, ".htmlspecialchars($name)."!" : "Welcome, new visitor!";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>1. Customer Visit Tracking</title>
<style>
*{box-sizing:border-box}body{font-family:Arial;margin:0;background:#f4f7fb;color:#222}
.container{max-width:850px;margin:35px auto;padding:25px;background:#fff;border-radius:12px;box-shadow:0 4px 18px #0001}
h1{margin-top:0;color:#243b64}.card{padding:15px;margin:12px 0;background:#f7f9fc;border:1px solid #ddd;border-radius:8px}
input,select,textarea,button{width:100%;padding:10px;margin:6px 0 12px;border:1px solid #bbb;border-radius:6px}
button{background:#243b64;color:white;border:0;cursor:pointer}button:hover{background:#162947}
.success{color:#087a35}.error{color:#b00020}.small{font-size:13px;color:#666}
table{width:100%;border-collapse:collapse;margin-top:15px}th,td{padding:9px;border:1px solid #ccc;text-align:left}
</style>
</head>
<body><div class="container">
<h1>1. Customer Visit Tracking</h1>

<div class="card"><h2><?= $greeting ?></h2>
<p>You have visited this website <b><?= $visits ?></b> time(s).</p></div>
<form method="post"><label>Customer Name</label>
<input name="name" value="<?= htmlspecialchars($name) ?>" required>
<button name="save">Save Preference</button></form>
<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

</div></body></html>