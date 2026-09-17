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

// Delete the selected product from the cart
$sql = "DELETE FROM mycart WHERE bid='$bid' AND pid='$pid'";

mysqli_query($conn, $sql);

// Return to the cart
header("Location: myCart.php#cart");
exit();
?>