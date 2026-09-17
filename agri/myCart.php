<?php

session_start();
require 'db.php';


/* =====================================================
   CHECK LOGIN
===================================================== */

if (
    !isset($_SESSION['logged_in']) ||
    $_SESSION['logged_in'] != 1
) {

    $_SESSION['message'] = "You need to login first.";

    header("Location: Login/login.php");

    exit();

}


/* =====================================================
   FARMER CHECK
   Category 1 = Farmer

   Farmers cannot access the cart.
===================================================== */

if (
    isset($_SESSION['Category']) &&
    $_SESSION['Category'] == 1
) {

    $_SESSION['message'] =
        "Farmers cannot access the shopping cart.";

    header("Location: productMenu.php");

    exit();

}


/* =====================================================
   BUYER ID
===================================================== */

$bid = $_SESSION['id'];


/* =====================================================
   REMOVE PRODUCT FROM CART
   NO CONFIRMATION POPUP
===================================================== */

if (
    isset($_GET['remove']) &&
    is_numeric($_GET['remove'])
) {

    $pid = intval($_GET['remove']);

    if ($pid > 0) {

        $deleteSql = "

            DELETE FROM mycart

            WHERE bid = '$bid'

            AND pid = '$pid'

        ";

        mysqli_query(
            $conn,
            $deleteSql
        );

    }

    header("Location: myCart.php");

    exit();

}


/* =====================================================
   INCREASE QUANTITY
===================================================== */

if (
    isset($_GET['increase']) &&
    is_numeric($_GET['increase'])
) {

    $pid = intval($_GET['increase']);

    if ($pid > 0) {

        $increaseSql = "

            UPDATE mycart

            SET quantity = quantity + 1

            WHERE bid = '$bid'

            AND pid = '$pid'

        ";

        mysqli_query(
            $conn,
            $increaseSql
        );

    }

    header("Location: myCart.php");

    exit();

}


/* =====================================================
   DECREASE QUANTITY
===================================================== */

if (
    isset($_GET['decrease']) &&
    is_numeric($_GET['decrease'])
) {

    $pid = intval($_GET['decrease']);

    if ($pid > 0) {

        $decreaseSql = "

            UPDATE mycart

            SET quantity = quantity - 1

            WHERE bid = '$bid'

            AND pid = '$pid'

            AND quantity > 1

        ";

        mysqli_query(
            $conn,
            $decreaseSql
        );

    }

    header("Location: myCart.php");

    exit();

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">


    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >


    <title>
        AgroCulture - My Cart
    </title>


    <link
        rel="stylesheet"
        href="bootstrap/css/bootstrap.min.css"
    >


    <style>


        /* =================================================
           RESET
        ================================================= */

        * {

            box-sizing: border-box;

        }


        html,
        body {

            margin: 0;

            padding: 0;

            width: 100%;

            min-height: 100%;

        }


        /* =================================================
           BODY
        ================================================= */

        body {

            background: #f1f8f3;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

        }


        /* =================================================
           MAIN PAGE
        ================================================= */

        .cart-page {

            width: 100%;

            padding:
                90px 25px 60px 25px;

        }


        /* =================================================
           TITLE
        ================================================= */

        .page-title {

            text-align: center;

            color: #238b45;

            font-size: 42px;

            font-weight: bold;

            margin:
                0 0 10px 0;

        }


        .page-description {

            text-align: center;

            color: #555;

            font-size: 18px;

            margin:
                0 0 35px 0;

        }


        /* =================================================
           CART CONTAINER
        ================================================= */

        .cart-container {

            width: 100%;

            max-width: 1300px;

            margin: 0 auto;

            background: white;

            padding: 25px;

            border-radius: 18px;

            box-shadow:
                0 5px 20px
                rgba(0,0,0,0.10);

        }


        /* =================================================
           CART TABLE
        ================================================= */

        .cart-table {

            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;

        }


        .cart-table th {

            background: #238b45;

            color: white;

            text-align: center;

            vertical-align: middle;

            padding: 15px 10px;

            font-size: 17px;

            border:
                1px solid #ddd;

        }


        .cart-table td {

            text-align: center;

            vertical-align: middle;

            padding: 15px 10px;

            font-size: 16px;

            border:
                1px solid #ddd;

        }


        .cart-table tbody tr:hover {

            background: #f7fbf8;

        }


        /* =================================================
           PRODUCT IMAGE
        ================================================= */

        .product-image {

            width: 90px;

            height: 90px;

            object-fit: cover;

            border-radius: 8px;

            display: block;

            margin: auto;

        }


        /* =================================================
           PRODUCT NAME
        ================================================= */

        .product-name {

            font-weight: bold;

            font-size: 18px;

            color: #222;

        }


        .product-category {

            color: #777;

            font-size: 14px;

            margin-top: 5px;

        }


        /* =================================================
           PRICE
        ================================================= */

        .price {

            font-weight: bold;

            color: #238b45;

            font-size: 17px;

        }


        /* =================================================
           QUANTITY
        ================================================= */

        .quantity-area {

            display: flex;

            justify-content: center;

            align-items: center;

            gap: 8px;

        }


        .quantity-button {

            width: 35px;

            height: 35px;

            border: none;

            border-radius: 5px;

            color: white;

            font-size: 20px;

            font-weight: bold;

            text-decoration: none;

            display: inline-flex;

            justify-content: center;

            align-items: center;

        }


        .minus-button {

            background: #dc3545;

        }


        .minus-button:hover {

            background: #c82333;

            color: white;

            text-decoration: none;

        }


        .plus-button {

            background: #28a745;

        }


        .plus-button:hover {

            background: #218838;

            color: white;

            text-decoration: none;

        }


        .quantity-number {

            min-width: 35px;

            font-weight: bold;

            font-size: 17px;

        }


        /* =================================================
           SUBTOTAL
        ================================================= */

        .subtotal {

            font-weight: bold;

            font-size: 17px;

            color: #222;

        }


        /* =================================================
           REMOVE BUTTON
        ================================================= */

        .remove-button {

            background: #dc3545;

            color: white;

            padding:
                9px 15px;

            border-radius: 6px;

            text-decoration: none;

            display: inline-block;

            font-weight: bold;

            border: none;

        }


        .remove-button:hover {

            background: #c82333;

            color: white;

            text-decoration: none;

        }


        /* =================================================
           TOTAL SECTION
        ================================================= */

        .cart-total {

            margin-top: 25px;

            display: flex;

            justify-content: flex-end;

            align-items: center;

            gap: 25px;

            flex-wrap: wrap;

        }


        .total-label {

            font-size: 22px;

            font-weight: bold;

            color: #333;

        }


        .total-price {

            font-size: 25px;

            font-weight: bold;

            color: #238b45;

        }


        /* =================================================
           BUY NOW BUTTON
        ================================================= */

        .buy-button {

            background: #007bff;

            color: white;

            padding:
                12px 25px;

            border-radius: 7px;

            text-decoration: none;

            font-size: 17px;

            font-weight: bold;

            display: inline-block;

        }


        .buy-button:hover {

            background: #0056b3;

            color: white;

            text-decoration: none;

        }


        /* =================================================
           EMPTY CART
        ================================================= */

        .empty-cart {

            text-align: center;

            padding: 60px 20px;

        }


        .empty-cart h3 {

            color: #555;

            font-size: 25px;

            margin-bottom: 15px;

        }


        .empty-cart p {

            color: #777;

            font-size: 17px;

            margin-bottom: 25px;

        }


        .market-button {

            background: #28a745;

            color: white;

            padding:
                12px 25px;

            border-radius: 7px;

            text-decoration: none;

            font-weight: bold;

            display: inline-block;

        }


        .market-button:hover {

            background: #218838;

            color: white;

            text-decoration: none;

        }


        /* =================================================
           MOBILE
        ================================================= */

        @media screen and (max-width: 900px) {

            .cart-page {

                padding:
                    80px 10px 50px 10px;

            }


            .page-title {

                font-size: 32px;

            }


            .cart-container {

                padding: 12px;

                overflow-x: auto;

            }


            .cart-table {

                min-width: 900px;

            }

        }

    </style>

</head>


<body>


<?php

require 'menu.php';

?>


<!-- =====================================================
     MAIN PAGE
===================================================== -->

<div class="cart-page">


    <!-- =================================================
         TITLE
    ================================================= -->

    <h1 class="page-title">

        My Cart

    </h1>


    <p class="page-description">

        Review your selected products before placing your order.

    </p>


    <!-- =================================================
         CART CONTAINER
    ================================================= -->

    <div class="cart-container">


<?php


/* =====================================================
   GET CART PRODUCTS
===================================================== */

$sql = "

    SELECT

        mc.pid,

        mc.quantity,

        fp.product,

        fp.pcat,

        fp.price,

        fp.pimage

    FROM mycart mc

    INNER JOIN fproduct fp

        ON mc.pid = fp.pid

    WHERE mc.bid = '$bid'

    ORDER BY mc.pid DESC

";


$result = mysqli_query(
    $conn,
    $sql
);


if (!$result) {

    die(
        "Cart database error: "
        . mysqli_error($conn)
    );

}


/* =====================================================
   EMPTY CART
===================================================== */

if (mysqli_num_rows($result) == 0) {

?>


        <div class="empty-cart">


            <h3>

                🛒 Your cart is empty

            </h3>


            <p>

                You have not added any products
                to your cart yet.

            </p>


            <a
                href="productMenu.php"
                class="market-button"
            >

                🌾 Go to Digital Market

            </a>


        </div>


<?php

}

else {

?>


        <!-- =================================================
             CART TABLE
        ================================================= -->

        <table class="cart-table">


            <thead>

                <tr>

                    <th style="width:15%;">

                        Image

                    </th>


                    <th style="width:20%;">

                        Product

                    </th>


                    <th style="width:15%;">

                        Price

                    </th>


                    <th style="width:20%;">

                        Quantity

                    </th>


                    <th style="width:15%;">

                        Subtotal

                    </th>


                    <th style="width:15%;">

                        Action

                    </th>

                </tr>

            </thead>


            <tbody>


<?php


    $grandTotal = 0;


    while (
        $row =
        mysqli_fetch_assoc($result)
    ) {


        $pid =
            $row['pid'];


        $productName =
            $row['product'];


        $category =
            $row['pcat'];


        $price =
            $row['price'];


        $quantity =
            $row['quantity'];


        $subtotal =
            $price * $quantity;


        $grandTotal += $subtotal;


        $imagePath =
            "images/productImages/"
            . $row['pimage'];

?>


                <tr>


                    <!-- =================================================
                         IMAGE
                    ================================================= -->

                    <td>

                        <img

                            src="<?php

                                echo htmlspecialchars(
                                    $imagePath
                                );

                            ?>"

                            class="product-image"

                            alt="<?php

                                echo htmlspecialchars(
                                    $productName
                                );

                            ?>"

                        >

                    </td>


                    <!-- =================================================
                         PRODUCT
                    ================================================= -->

                    <td>

                        <div class="product-name">

                            <?php

                            echo htmlspecialchars(
                                $productName
                            );

                            ?>

                        </div>


                        <div class="product-category">

                            Category:

                            <?php

                            echo htmlspecialchars(
                                $category
                            );

                            ?>

                        </div>

                    </td>


                    <!-- =================================================
                         PRICE
                    ================================================= -->

                    <td>

                        <div class="price">

                            ₹<?php

                            echo number_format(
                                $price,
                                2
                            );

                            ?>

                        </div>

                    </td>


                    <!-- =================================================
                         QUANTITY
                    ================================================= -->

                    <td>


                        <div class="quantity-area">


                            <!-- DECREASE -->

                            <a

                                href="myCart.php?decrease=<?php
                                    echo $pid;
                                ?>"

                                class="
                                    quantity-button
                                    minus-button
                                "

                            >

                                −

                            </a>


                            <!-- QUANTITY -->

                            <span
                                class="quantity-number"
                            >

                                <?php

                                echo $quantity;

                                ?>

                            </span>


                            <!-- INCREASE -->

                            <a

                                href="myCart.php?increase=<?php
                                    echo $pid;
                                ?>"

                                class="
                                    quantity-button
                                    plus-button
                                "

                            >

                                +

                            </a>


                        </div>


                    </td>


                    <!-- =================================================
                         SUBTOTAL
                    ================================================= -->

                    <td>

                        <div class="subtotal">

                            ₹<?php

                            echo number_format(
                                $subtotal,
                                2
                            );

                            ?>

                        </div>

                    </td>


                    <!-- =================================================
                         REMOVE
                    ================================================= -->

                    <td>


                        <a

                            href="myCart.php?remove=<?php
                                echo $pid;
                            ?>"

                            class="remove-button"

                        >

                            Remove

                        </a>


                    </td>


                </tr>


<?php

    }

?>


            </tbody>


        </table>


        <!-- =================================================
             TOTAL
        ================================================= -->

        <div class="cart-total">


            <span class="total-label">

                Grand Total:

            </span>


            <span class="total-price">

                ₹<?php

                echo number_format(
                    $grandTotal,
                    2
                );

                ?>

            </span>


            <a

                href="buyNow.php"

                class="buy-button"

            >

                💳 Buy Now

            </a>


        </div>


<?php

}

?>


    </div>


</div>


</body>

</html>