<?php
session_start();
require 'db.php';

if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] == 0)
{
    header("Location: Login/error.php");
    exit();
}

$bid = $_SESSION['id'];
$pid = $_GET['pid'];

// Check current quantity
$check = "SELECT quantity FROM mycart WHERE bid='$bid' AND pid='$pid'";
$result = mysqli_query($conn, $check);
$row = mysqli_fetch_assoc($result);

if($row['quantity'] > 1)
{
    $sql = "UPDATE mycart
            SET quantity = quantity - 1
            WHERE bid='$bid' AND pid='$pid'";
}
else
{
    $sql = "DELETE FROM mycart
            WHERE bid='$bid' AND pid='$pid'";
}

mysqli_query($conn, $sql);

header("Location: myCart.php#cart");
exit();
?>