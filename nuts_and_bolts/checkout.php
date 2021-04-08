<?php require_once "config/connect.php" ?>
<?php
session_start();

$userid = $_SESSION['userId'];
$total = $_SESSION['total'];
$receiptid = uniqid ($prefix = "$userid-");
$_SESSION['receiptID'] = $receiptid;

echo("$userid, $total, $receiptid");
mysqli_query($conn,"INSERT INTO check_out_history(user_id, total_purchase, receipt_id) VALUES('$userid', '$total', '$receiptid')");

header("location: receipt.php");
?>