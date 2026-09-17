<?php

session_start();
require 'db.php';

header('Content-Type: application/json');


/* =========================================
   LOGIN CHECK
   ========================================= */

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {

    echo json_encode([
        "success" => false,
        "message" => "Please login first."
    ]);

    exit();
}


/* =========================================
   BUYER ID
   ========================================= */

if (isset($_SESSION['id'])) {

    $bid = intval($_SESSION['id']);

} elseif (isset($_SESSION['bid'])) {

    $bid = intval($_SESSION['bid']);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Buyer ID not found."
    ]);

    exit();
}


if ($bid <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid Buyer ID."
    ]);

    exit();
}


/* =========================================
   PRODUCT ID
   ========================================= */

if (!isset($_GET['pid']) || empty($_GET['pid'])) {

    echo json_encode([
        "success" => false,
        "message" => "Product ID not received."
    ]);

    exit();
}


$pid = intval($_GET['pid']);


if ($pid <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid Product ID."
    ]);

    exit();
}


/* =========================================
   CHECK PRODUCT
   ========================================= */

$productSql = "
    SELECT pid, product
    FROM fproduct
    WHERE pid = '$pid'
";

$productResult = mysqli_query(
    $conn,
    $productSql
);


if (!$productResult) {

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);

    exit();
}


if (mysqli_num_rows($productResult) == 0) {

    echo json_encode([
        "success" => false,
        "message" => "Product not found."
    ]);

    exit();
}


$product = mysqli_fetch_assoc($productResult);

$productName = $product['product'];


/* =========================================
   CHECK CART
   ========================================= */

$checkSql = "
    SELECT quantity
    FROM mycart
    WHERE bid = '$bid'
    AND pid = '$pid'
";

$checkResult = mysqli_query(
    $conn,
    $checkSql
);


if (!$checkResult) {

    echo json_encode([
        "success" => false,
        "message" => "Cart check failed."
    ]);

    exit();
}


/* =========================================
   ALREADY IN CART
   ========================================= */

if (mysqli_num_rows($checkResult) > 0) {

    $updateSql = "
        UPDATE mycart
        SET quantity = quantity + 1
        WHERE bid = '$bid'
        AND pid = '$pid'
    ";

    if (!mysqli_query($conn, $updateSql)) {

        echo json_encode([
            "success" => false,
            "message" => "Unable to update cart."
        ]);

        exit();
    }

}


/* =========================================
   NEW PRODUCT
   ========================================= */

else {

    $insertSql = "
        INSERT INTO mycart
        (
            bid,
            pid,
            quantity
        )
        VALUES
        (
            '$bid',
            '$pid',
            1
        )
    ";

    if (!mysqli_query($conn, $insertSql)) {

        echo json_encode([
            "success" => false,
            "message" => "Unable to add product."
        ]);

        exit();
    }

}


/* =========================================
   SUCCESS
   ========================================= */

echo json_encode([
    "success" => true,
    "message" => $productName . " added to cart!"
]);

exit();

?>