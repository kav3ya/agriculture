<?php

session_start();

require_once "db.php";


/* =====================================================
   DATABASE CONNECTION
===================================================== */

if (isset($conn)) {
    $database = $conn;
} elseif (isset($con)) {
    $database = $con;
} else {
    die("Database connection not found. Please check db.php");
}


/* =====================================================
   CHECK PRODUCT ID
===================================================== */

if (!isset($_GET['pid'])) {
    $_SESSION['message'] = "Product ID is missing.";

    header("Location: manageProducts.php");
    exit();
}

$pid = intval($_GET['pid']);

if ($pid <= 0) {
    $_SESSION['message'] = "Invalid product ID.";

    header("Location: manageProducts.php");
    exit();
}


/* =====================================================
   GET PRODUCT IMAGE NAME
===================================================== */

$selectSql = "SELECT pimage FROM fproduct WHERE pid = $pid";

$selectResult = mysqli_query($database, $selectSql);

$imageName = "";

if ($selectResult && mysqli_num_rows($selectResult) > 0) {

    $product = mysqli_fetch_assoc($selectResult);

    $imageName = trim($product['pimage']);

} else {

    $_SESSION['message'] = "Product not found.";

    header("Location: manageProducts.php");
    exit();
}


/* =====================================================
   DELETE PRODUCT FROM DATABASE
===================================================== */

$deleteSql = "DELETE FROM fproduct WHERE pid = $pid";

$deleteResult = mysqli_query($database, $deleteSql);


if ($deleteResult) {

    /* =================================================
       DELETE PRODUCT IMAGE FROM FOLDER
    ================================================= */

    if (!empty($imageName)) {

        $imagePath = "images/productImages/" . $imageName;

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $_SESSION['message'] = "Product deleted successfully.";

} else {

    $_SESSION['message'] =
        "Product could not be deleted: " . mysqli_error($database);
}


/* =====================================================
   RETURN TO MANAGE PRODUCTS PAGE
===================================================== */

header("Location: manageProducts.php");
exit();

?>