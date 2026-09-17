<?php

session_start();
require '../db.php';


/* CHECK LOGIN */

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {

    echo "You are not logged in.";
    exit();

}


/* CHECK POST DATA */

if (!isset($_POST['order_id']) || !isset($_POST['status'])) {

    echo "Invalid request.";
    exit();

}


$order_id = mysqli_real_escape_string(
    $conn,
    $_POST['order_id']
);

$status = mysqli_real_escape_string(
    $conn,
    $_POST['status']
);


/* ONLY THESE STATUSES ARE ALLOWED */

$allowedStatuses = array(
    "Accepted",
    "Delivered"
);


if (!in_array($status, $allowedStatuses)) {

    echo "Invalid status.";
    exit();

}


/* GET CURRENT STATUS */

$checkSql = "
    SELECT status
    FROM orders
    WHERE order_id = '$order_id'
";

$checkResult = mysqli_query(
    $conn,
    $checkSql
);


if (!$checkResult || mysqli_num_rows($checkResult) == 0) {

    echo "Order not found.";
    exit();

}


$order = mysqli_fetch_assoc($checkResult);

$currentStatus = $order['status'];


/* ==========================================
   PENDING → ACCEPTED
========================================== */

if ($currentStatus == "Pending") {

    if ($status != "Accepted") {

        echo "Pending order can only be accepted.";
        exit();

    }

}


/* ==========================================
   ACCEPTED → DELIVERED
========================================== */

elseif ($currentStatus == "Accepted") {

    if ($status != "Delivered") {

        echo "Accepted order can only be marked as delivered.";
        exit();

    }

}


/* ==========================================
   DELIVERED CANNOT CHANGE
========================================== */

elseif ($currentStatus == "Delivered") {

    echo "Delivered order cannot be changed.";
    exit();

}


/* ==========================================
   UPDATE STATUS
========================================== */

$sql = "
    UPDATE orders
    SET status = '$status'
    WHERE order_id = '$order_id'
";


if (mysqli_query($conn, $sql)) {

    echo "Updated Successfully";
    exit();

}
else {

    echo "Database Error: " . mysqli_error($conn);
    exit();

}

?>