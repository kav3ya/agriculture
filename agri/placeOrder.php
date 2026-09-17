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


/* =====================================================
   CHECK FORM SUBMISSION
===================================================== */

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    header("Location: checkout.php");
    exit();

}


/* =====================================================
   GET DELIVERY DETAILS
===================================================== */

$name = isset($_POST['name'])
    ? mysqli_real_escape_string($conn, $_POST['name'])
    : '';

$mobile = isset($_POST['mobile'])
    ? mysqli_real_escape_string($conn, $_POST['mobile'])
    : '';

$email = isset($_POST['email'])
    ? mysqli_real_escape_string($conn, $_POST['email'])
    : '';

$city = isset($_POST['city'])
    ? mysqli_real_escape_string($conn, $_POST['city'])
    : '';

$pincode = isset($_POST['pincode'])
    ? mysqli_real_escape_string($conn, $_POST['pincode'])
    : '';

$address = isset($_POST['address'])
    ? mysqli_real_escape_string($conn, $_POST['address'])
    : '';


/* =====================================================
   VALIDATE DETAILS
===================================================== */

if (
    empty($name) ||
    empty($mobile) ||
    empty($email) ||
    empty($city) ||
    empty($pincode) ||
    empty($address)
) {

    die("Please fill all delivery details.");

}


/* =====================================================
   GET CART PRODUCTS
===================================================== */

$cartSql = "
    SELECT
        mycart.pid,
        mycart.quantity,
        fproduct.product,
        fproduct.price

    FROM mycart

    JOIN fproduct
    ON mycart.pid = fproduct.pid

    WHERE mycart.bid = '$bid'
";


$cartResult = mysqli_query(
    $conn,
    $cartSql
);


if (!$cartResult) {

    die(
        "Cart Error: " .
        mysqli_error($conn)
    );

}


/* =====================================================
   CHECK CART EMPTY
===================================================== */

if (mysqli_num_rows($cartResult) == 0) {

    header("Location: myCart.php");
    exit();

}


/* =====================================================
   CALCULATE GRAND TOTAL
===================================================== */

$grandTotal = 0;

$cartItems = array();


while (
    $row = mysqli_fetch_assoc($cartResult)
) {

    $pid = $row['pid'];

    $quantity = $row['quantity'];

    $price = $row['price'];

    $subtotal = $price * $quantity;

    $grandTotal += $subtotal;


    $cartItems[] = array(

        'pid' => $pid,

        'quantity' => $quantity,

        'price' => $price,

        'product' => $row['product'],

        'subtotal' => $subtotal

    );

}


/* =====================================================
   INSERT ORDER
===================================================== */

$orderSql = "
    INSERT INTO orders
    (
        bid,
        total_amount,
        name,
        mobile,
        email,
        city,
        pincode,
        address
    )

    VALUES
    (
        '$bid',
        '$grandTotal',
        '$name',
        '$mobile',
        '$email',
        '$city',
        '$pincode',
        '$address'
    )
";


$orderResult = mysqli_query(
    $conn,
    $orderSql
);


if (!$orderResult) {

    die(
        "ORDER ERROR: " .
        mysqli_error($conn)
    );

}


/* =====================================================
   GET ORDER ID
===================================================== */

$order_id = mysqli_insert_id($conn);


/* =====================================================
   INSERT ORDER ITEMS
===================================================== */

foreach ($cartItems as $item) {

    $pid = $item['pid'];

    $quantity = $item['quantity'];

    $price = $item['price'];


    $itemSql = "
        INSERT INTO order_items
        (
            order_id,
            pid,
            quantity,
            price
        )

        VALUES
        (
            '$order_id',
            '$pid',
            '$quantity',
            '$price'
        )
    ";


    $itemResult = mysqli_query(
        $conn,
        $itemSql
    );


    if (!$itemResult) {

        die(
            "ORDER ITEM ERROR: " .
            mysqli_error($conn)
        );

    }

}


/* =====================================================
   EMPTY CART AFTER SUCCESSFUL ORDER
===================================================== */

$deleteCartSql = "
    DELETE FROM mycart
    WHERE bid = '$bid'
";


$deleteCartResult = mysqli_query(
    $conn,
    $deleteCartSql
);


if (!$deleteCartResult) {

    die(
        "Unable to clear cart: " .
        mysqli_error($conn)
    );

}


/* =====================================================
   ORDER SUCCESS
===================================================== */

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
        AgroCulture - Order Success
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
           MAIN PAGE
        ================================================= */

        .success-page {

            min-height: 100vh;

            padding:
                120px 20px 60px 20px;

        }


        /* =================================================
           SUCCESS CARD
        ================================================= */

        .success-card {

            width: 95%;

            max-width: 900px;

            margin: 0 auto;

            background:
                rgba(255, 255, 255, 0.98);

            border-radius: 20px;

            padding:
                45px;

            box-shadow:
                0 10px 35px
                rgba(0, 0, 0, 0.25);

            text-align: center;

        }


        /* =================================================
           SUCCESS ICON
        ================================================= */

        .success-icon {

            width: 90px;

            height: 90px;

            margin: 0 auto 25px auto;

            border-radius: 50%;

            background: #e8f5e9;

            border:
                4px solid #2e7d32;

            color: #2e7d32;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 50px;

            font-weight: bold;

        }


        /* =================================================
           TITLE
        ================================================= */

        .success-title {

            color: #2e7d32;

            font-size: 38px;

            font-weight: bold;

            margin:
                0 0 15px 0;

        }


        /* =================================================
           MESSAGE
        ================================================= */

        .success-message {

            color: #555;

            font-size: 18px;

            line-height: 1.7;

            margin-bottom: 30px;

        }


        /* =================================================
           ORDER SUMMARY
        ================================================= */

        .order-summary {

            background: #f4faf4;

            border:
                1px solid #d6ead7;

            border-left:
                5px solid #2e7d32;

            border-radius: 12px;

            padding: 25px;

            text-align: left;

            margin-bottom: 25px;

        }


        .summary-title {

            color: #2e7d32;

            font-size: 23px;

            font-weight: bold;

            margin:
                0 0 20px 0;

        }


        /* =================================================
           SUMMARY ROW
        ================================================= */

        .summary-row {

            display: flex;

            justify-content:
                space-between;

            align-items: center;

            padding:
                13px 5px;

            border-bottom:
                1px dashed #cccccc;

        }


        .summary-row:last-child {

            border-bottom: none;

        }


        .summary-label {

            color: #555;

            font-weight: bold;

            font-size: 16px;

        }


        .summary-value {

            color: #222;

            font-weight: bold;

            font-size: 16px;

        }


        .order-number {

            color: #2e7d32;

            font-size: 18px;

        }


        .total-value {

            color: #2e7d32;

            font-size: 22px;

        }


        /* =================================================
           DELIVERY MESSAGE
        ================================================= */

        .delivery-message {

            background: #fff8e1;

            border:
                1px solid #ffe082;

            border-radius: 10px;

            padding: 18px;

            margin-bottom: 30px;

            color: #6d5700;

            font-size: 16px;

            line-height: 1.6;

        }


        /* =================================================
           BUTTON AREA
        ================================================= */

        .button-area {

            display: flex;

            justify-content: center;

            gap: 15px;

            flex-wrap: wrap;

        }


        /* =================================================
           BUTTONS
        ================================================= */

        .action-btn {

            display: inline-block;

            min-width: 190px;

            padding:
                14px 25px;

            border-radius: 8px;

            font-size: 17px;

            font-weight: bold;

            text-decoration: none;

            transition: 0.2s;

        }


        .shopping-btn {

            background: white;

            color: #2e7d32;

            border:
                2px solid #2e7d32;

        }


        .shopping-btn:hover {

            background: #e8f5e9;

            color: #1b5e20;

            text-decoration: none;

        }


        .orders-btn {

            background: #2e7d32;

            color: white;

            border:
                2px solid #2e7d32;

        }


        .orders-btn:hover {

            background: #1b5e20;

            color: white;

            text-decoration: none;

        }


        /* =================================================
           MOBILE
        ================================================= */

        @media screen and (max-width: 768px) {

            .success-page {

                padding:
                    100px 10px 40px 10px;

            }


            .success-card {

                width: 100%;

                padding: 30px 20px;

            }


            .success-title {

                font-size: 30px;

            }


            .success-message {

                font-size: 16px;

            }


            .summary-row {

                flex-direction: column;

                align-items: flex-start;

                gap: 5px;

            }


            .action-btn {

                width: 100%;

            }

        }


    </style>

</head>


<body>


<?php

require 'menu.php';

?>


<!-- =====================================================
     SUCCESS PAGE
===================================================== -->

<div class="success-page">


    <div class="success-card">


        <!-- =================================================
             SUCCESS ICON
        ================================================== -->

        <div class="success-icon">

            ✓

        </div>


        <!-- =================================================
             TITLE
        ================================================== -->

        <h1 class="success-title">

            Order Placed Successfully!

        </h1>


        <!-- =================================================
             MESSAGE
        ================================================== -->

        <div class="success-message">

            Thank you for shopping with

            <strong>
                AgroCulture
            </strong>.

            <br>

            Your order has been successfully placed
            and will be processed soon.

        </div>


        <!-- =================================================
             ORDER SUMMARY
        ================================================== -->

        <div class="order-summary">


            <h2 class="summary-title">

                📋 Order Summary

            </h2>


            <!-- ORDER ID -->

            <div class="summary-row">


                <span class="summary-label">

                    Order ID

                </span>


                <span class="summary-value order-number">

                    #AGRI-<?php

                    echo $order_id;

                    ?>

                </span>


            </div>


            <!-- ORDER ITEMS -->

            <div class="summary-row">


                <span class="summary-label">

                    Products

                </span>


                <span class="summary-value">

                    <?php

                    echo count($cartItems);

                    ?>

                    item(s)

                </span>


            </div>


            <!-- PAYMENT -->

            <div class="summary-row">


                <span class="summary-label">

                    Payment

                </span>


                <span class="summary-value">

                    Cash on Delivery

                </span>


            </div>


            <!-- ORDER STATUS -->

            <div class="summary-row">


                <span class="summary-label">

                    Order Status

                </span>


                <span class="summary-value">

                    <span
                        style="
                            background:#e8f5e9;
                            color:#2e7d32;
                            padding:6px 12px;
                            border-radius:20px;
                        "
                    >

                        Placed

                    </span>

                </span>


            </div>


            <!-- TOTAL -->

            <div class="summary-row">


                <span class="summary-label">

                    Total Amount

                </span>


                <span class="summary-value total-value">

                    ₹<?php

                    echo number_format(
                        $grandTotal,
                        2
                    );

                    ?>

                </span>


            </div>


        </div>


        <!-- =================================================
             DELIVERY MESSAGE
        ================================================== -->

        <div class="delivery-message">

            🔔

            <strong>
                Thank you for your order!
            </strong>

            <br>

            You can check your order details
            and track the order status from
            <strong>My Orders</strong>.

        </div>


        <!-- =================================================
             BUTTONS
        ================================================== -->

        <div class="button-area">


            <a
                href="productMenu.php?n=0"
                class="action-btn shopping-btn"
            >

                🛒 Continue Shopping

            </a>


            <a
                href="myOrders.php"
                class="action-btn orders-btn"
            >

                📋 View My Orders

            </a>


        </div>


    </div>


</div>


</body>

</html>