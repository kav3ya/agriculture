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

    header("Location: Login/error.php");
    exit();

}


$bid = $_SESSION['id'];

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
        AgroCulture - Checkout
    </title>


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
           BODY BACKGROUND
        ================================================= */

        body {

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background:

                linear-gradient(
                    rgba(30, 80, 40, 0.70),
                    rgba(220, 240, 220, 0.90)
                ),

                url("images/banner.jpg");

            background-size: cover;

            background-position: center;

            background-attachment: fixed;

            min-height: 100vh;

        }


        /* =================================================
           MAIN CHECKOUT AREA
        ================================================= */

        .checkout-page {

            width: 100%;

            min-height: 100vh;

            padding: 110px 20px 50px 20px;

        }


        /* =================================================
           MAIN CONTAINER
        ================================================= */

        .checkout-container {

            width: 95%;

            max-width: 1100px;

            margin: 0 auto;

            background:
                rgba(255, 255, 255, 0.97);

            padding: 35px;

            border-radius: 16px;

            box-shadow:
                0 8px 25px
                rgba(0, 0, 0, 0.25);

        }


        /* =================================================
           TITLE
        ================================================= */

        .checkout-title {

            text-align: center;

            color: #2e7d32;

            font-size: 38px;

            font-weight: bold;

            margin:
                0 0 30px 0;

        }


        /* =================================================
           CART PRODUCTS SECTION
        ================================================= */

        .products-section {

            background: #f4faf4;

            padding: 25px;

            border-radius: 12px;

            border-left:
                5px solid #2e7d32;

            margin-bottom: 30px;

        }


        .section-title {

            color: #2e7d32;

            font-size: 25px;

            font-weight: bold;

            margin:
                0 0 20px 0;

        }


        /* =================================================
           TABLE
        ================================================= */

        .checkout-table {

            width: 100%;

            border-collapse: collapse;

            background: white;

            border-radius: 8px;

            overflow: hidden;

        }


        .checkout-table th {

            background: #2e7d32;

            color: white;

            padding: 15px;

            text-align: center;

            font-size: 17px;

        }


        .checkout-table td {

            padding: 14px;

            border-bottom:
                1px solid #ddd;

            text-align: center;

            font-size: 16px;

            color: #333;

        }


        .checkout-table tr:last-child td {

            border-bottom: none;

        }


        .checkout-table tr:hover {

            background: #f1f8f1;

        }


        /* =================================================
           GRAND TOTAL
        ================================================= */

        .grand-total {

            text-align: right;

            color: #2e7d32;

            font-size: 27px;

            font-weight: bold;

            margin-top: 20px;

        }


        /* =================================================
           DELIVERY SECTION
        ================================================= */

        .delivery-section {

            background: #f4faf4;

            padding: 25px;

            border-radius: 12px;

            border-left:
                5px solid #2e7d32;

        }


        .delivery-title {

            color: #2e7d32;

            font-size: 25px;

            font-weight: bold;

            margin:
                0 0 25px 0;

        }


        /* =================================================
           FORM ROW
        ================================================= */

        .form-row {

            display: flex;

            gap: 20px;

            margin-bottom: 20px;

        }


        .form-group {

            flex: 1;

        }


        /* =================================================
           LABEL
        ================================================= */

        .form-group label {

            display: block;

            color: #2e7d32;

            font-weight: bold;

            margin-bottom: 7px;

        }


        /* =================================================
           INPUT
        ================================================= */

        .form-group input,

        .form-group textarea {

            width: 100%;

            padding: 13px;

            border:
                1px solid #ccc;

            border-radius: 7px;

            font-size: 16px;

            background: white;

            outline: none;

        }


        .form-group input {

            height: 48px;

        }


        .form-group textarea {

            height: 110px;

            resize: vertical;

        }


        .form-group input:focus,

        .form-group textarea:focus {

            border-color: #2e7d32;

            box-shadow:
                0 0 5px
                rgba(46, 125, 50, 0.25);

        }


        /* =================================================
           BUTTON AREA
        ================================================= */

        .button-area {

            text-align: center;

            margin-top: 30px;

        }


        /* =================================================
           PLACE ORDER BUTTON
        ================================================= */

        .place-order-btn {

            display: inline-block;

            padding:
                14px 45px;

            background: #2e7d32;

            color: white;

            border: none;

            border-radius: 8px;

            font-size: 19px;

            font-weight: bold;

            cursor: pointer;

            text-decoration: none;

        }


        .place-order-btn:hover {

            background: #1b5e20;

            color: white;

            text-decoration: none;

        }


        /* =================================================
           BACK TO CART
        ================================================= */

        .back-cart {

            display: block;

            text-align: center;

            margin-top: 20px;

            color: #2e7d32;

            font-size: 16px;

            font-weight: bold;

            text-decoration: none;

        }


        .back-cart:hover {

            color: #1b5e20;

            text-decoration: underline;

        }


        /* =================================================
           MOBILE
        ================================================= */

        @media screen and (max-width: 768px) {

            .checkout-page {

                padding:
                    100px 10px 30px 10px;

            }


            .checkout-container {

                width: 100%;

                padding: 20px;

            }


            .checkout-title {

                font-size: 30px;

            }


            .form-row {

                flex-direction: column;

                gap: 15px;

            }


            .checkout-table {

                font-size: 14px;

            }


            .checkout-table th,

            .checkout-table td {

                padding: 10px 5px;

            }


            .grand-total {

                font-size: 23px;

            }

        }

    </style>

</head>


<body>


<?php

require 'menu.php';

?>


<!-- =====================================================
     CHECKOUT PAGE
===================================================== -->

<div class="checkout-page">


    <div class="checkout-container">


        <!-- =================================================
             TITLE
        ================================================== -->

        <h1 class="checkout-title">

            Checkout

        </h1>


        <!-- =================================================
             PRODUCTS
        ================================================== -->

        <div class="products-section">


            <h2 class="section-title">

                🛒 Order Summary

            </h2>


            <table class="checkout-table">


                <tr>

                    <th>
                        Product
                    </th>

                    <th>
                        Quantity
                    </th>

                    <th>
                        Price
                    </th>

                    <th>
                        Subtotal
                    </th>

                </tr>


<?php


/* =====================================================
   GET CART PRODUCTS
===================================================== */

$sql = "
    SELECT
        mycart.quantity,
        fproduct.product,
        fproduct.price

    FROM mycart

    JOIN fproduct
    ON mycart.pid = fproduct.pid

    WHERE mycart.bid = '$bid'
";


$result = mysqli_query(
    $conn,
    $sql
);


if (!$result) {

    die(
        "Database Error: " .
        mysqli_error($conn)
    );

}


$grandTotal = 0;


/* =====================================================
   DISPLAY PRODUCTS
===================================================== */

while (
    $row =
    mysqli_fetch_assoc($result)
) {


    $quantity =
        $row['quantity'];


    $price =
        $row['price'];


    $subtotal =
        $price * $quantity;


    $grandTotal +=
        $subtotal;

?>


                <tr>


                    <td>

                        <?php

                        echo htmlspecialchars(
                            $row['product']
                        );

                        ?>

                    </td>


                    <td>

                        <?php

                        echo $quantity;

                        ?>

                    </td>


                    <td>

                        ₹<?php

                        echo number_format(
                            $price,
                            2
                        );

                        ?>

                    </td>


                    <td>

                        ₹<?php

                        echo number_format(
                            $subtotal,
                            2
                        );

                        ?>

                    </td>


                </tr>


<?php

}

?>


            </table>


            <!-- GRAND TOTAL -->

            <div class="grand-total">

                Grand Total :

                ₹<?php

                echo number_format(
                    $grandTotal,
                    2
                );

                ?>

            </div>


        </div>


        <!-- =================================================
             DELIVERY DETAILS
        ================================================== -->

        <div class="delivery-section">


            <h2 class="delivery-title">

                📦 Delivery Details

            </h2>


            <form
                action="placeOrder.php"
                method="post"
            >


                <!-- NAME + MOBILE -->

                <div class="form-row">


                    <div class="form-group">


                        <label>
                            Full Name
                        </label>


                        <input
                            type="text"
                            name="name"
                            placeholder="Enter your full name"
                            required
                        >


                    </div>


                    <div class="form-group">


                        <label>
                            Mobile Number
                        </label>


                        <input
                            type="text"
                            name="mobile"
                            placeholder="Enter mobile number"
                            maxlength="10"
                            required
                        >


                    </div>


                </div>


                <!-- EMAIL + CITY -->

                <div class="form-row">


                    <div class="form-group">


                        <label>
                            Email Address
                        </label>


                        <input
                            type="email"
                            name="email"
                            placeholder="Enter email address"
                            required
                        >


                    </div>


                    <div class="form-group">


                        <label>
                            City
                        </label>


                        <input
                            type="text"
                            name="city"
                            placeholder="Enter city"
                            required
                        >


                    </div>


                </div>


                <!-- PINCODE + ADDRESS -->

                <div class="form-row">


                    <div class="form-group">


                        <label>
                            Pincode
                        </label>


                        <input
                            type="text"
                            name="pincode"
                            placeholder="Enter pincode"
                            maxlength="6"
                            required
                        >


                    </div>


                    <div class="form-group">


                        <label>
                            Delivery Address
                        </label>


                        <textarea
                            name="address"
                            placeholder="Enter complete delivery address"
                            required
                        ></textarea>


                    </div>


                </div>


                <!-- PLACE ORDER -->

                <div class="button-area">


                    <button
                        type="submit"
                        class="place-order-btn"
                    >

                        ✓ Place Order

                    </button>


                </div>


            </form>


            <!-- BACK TO CART -->

            <a
                href="myCart.php"
                class="back-cart"
            >

                ← Back to Cart

            </a>


        </div>


    </div>


</div>


</body>

</html>