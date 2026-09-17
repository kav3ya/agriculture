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

$sql = "UPDATE mycart
        SET quantity = quantity + 1
        WHERE bid='$bid' AND pid='$pid'";

mysqli_query($conn, $sql);

header("Location: myCart.php#cart");
exit();
?>