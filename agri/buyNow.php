<?php

session_start();
require 'db.php';


/* =====================================================
   LOGIN CHECK
   ===================================================== */

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {

    header("Location: Login/login.php");
    exit();

}


/* =====================================================
   BUYER ID
   ===================================================== */

if (isset($_SESSION['id'])) {

    $bid = intval($_SESSION['id']);

} elseif (isset($_SESSION['bid'])) {

    $bid = intval($_SESSION['bid']);

} else {

    die("Buyer ID not found.");

}


if ($bid <= 0) {

    die("Invalid Buyer ID.");

}


/* =====================================================
   CHECK SINGLE PRODUCT OR CART
   ===================================================== */

$singleProduct = false;

$pid = 0;


/*
   Digital Market:

   buyNow.php?pid=7
*/

if (isset($_GET['pid']) && $_GET['pid'] != '') {

    $pid = intval($_GET['pid']);

    if ($pid <= 0) {

        die("Invalid Product ID.");

    }

    $singleProduct = true;

}


/* =====================================================
   VARIABLES
   ===================================================== */

$productName = "";

$category = "";

$price = 0;

$quantity = 1;

$total = 0;

$image = "";

$cartItems = array();


/* =====================================================
   SINGLE PRODUCT BUY NOW
   ===================================================== */

if ($singleProduct) {


    $sql = "
        SELECT *
        FROM fproduct
        WHERE pid = '$pid'
    ";


    $result = mysqli_query($conn, $sql);


    if (!$result) {

        die(
            "Product Database Error: "
            . mysqli_error($conn)
        );

    }


    if (mysqli_num_rows($result) == 0) {

        die("Product not found.");

    }


    $product = mysqli_fetch_assoc($result);


    $productName =
        $product['product'];


    $category =
        $product['pcat'];


    $price =
        (float)$product['price'];


    $image =
        $product['pimage'];


    $quantity = 1;


    $total = $price;

}


/* =====================================================
   CART BUY NOW
   ===================================================== */

else {


    $cartSql = "

        SELECT

            mycart.pid,

            mycart.quantity,

            fproduct.product,

            fproduct.pcat,

            fproduct.price,

            fproduct.pimage

        FROM mycart

        INNER JOIN fproduct

            ON mycart.pid = fproduct.pid

        WHERE mycart.bid = '$bid'

    ";


    $cartResult =
        mysqli_query(
            $conn,
            $cartSql
        );


    if (!$cartResult) {

        die(
            "Cart Database Error: "
            . mysqli_error($conn)
        );

    }


    if (mysqli_num_rows($cartResult) == 0) {

        die("Your cart is empty.");

    }


    while (
        $row =
        mysqli_fetch_assoc($cartResult)
    ) {


        $cartItems[] = $row;


        $total +=

            (float)$row['price']

            *

            (int)$row['quantity'];

    }

}


/* =====================================================
   CONFIRM ORDER
   ===================================================== */

if (isset($_POST['confirm_order'])) {


    /* CUSTOMER DETAILS */


    $name =
        mysqli_real_escape_string(
            $conn,
            $_POST['name']
        );


    $mobile =
        mysqli_real_escape_string(
            $conn,
            $_POST['mobile']
        );


    $email =
        mysqli_real_escape_string(
            $conn,
            $_POST['email']
        );


    $city =
        mysqli_real_escape_string(
            $conn,
            $_POST['city']
        );


    $pincode =
        mysqli_real_escape_string(
            $conn,
            $_POST['pincode']
        );


    $address =
        mysqli_real_escape_string(
            $conn,
            $_POST['address']
        );


    /* =================================================
       INSERT ORDER
       ================================================= */


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
            '$total',
            '$name',
            '$mobile',
            '$email',
            '$city',
            '$pincode',
            '$address'
        )

    ";


    if (!mysqli_query(
        $conn,
        $orderSql
    )) {

        die(
            "Order insertion failed: "
            . mysqli_error($conn)
        );

    }


    /* GET ORDER ID */

    $order_id =
        mysqli_insert_id($conn);


    /* =================================================
       SINGLE PRODUCT
       ================================================= */

    if ($singleProduct) {


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
                '1',
                '$price'
            )

        ";


        if (!mysqli_query(
            $conn,
            $itemSql
        )) {

            die(
                "Order item insertion failed: "
                . mysqli_error($conn)
            );

        }

    }


    /* =================================================
       CART PRODUCTS
       ================================================= */

    else {


        foreach (
            $cartItems
            as $item
        ) {


            $itemPid =
                intval(
                    $item['pid']
                );


            $itemQuantity =
                intval(
                    $item['quantity']
                );


            $itemPrice =
                (float)$item['price'];


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
                    '$itemPid',
                    '$itemQuantity',
                    '$itemPrice'
                )

            ";


            if (!mysqli_query(
                $conn,
                $itemSql
            )) {

                die(
                    "Order item insertion failed: "
                    . mysqli_error($conn)
                );

            }

        }


        /* CLEAR CART */

        $deleteCart = "

            DELETE FROM mycart

            WHERE bid = '$bid'

        ";


        mysqli_query(
            $conn,
            $deleteCart
        );

    }


    /* =================================================
       PROFESSIONAL SUCCESS PAGE
       ================================================= */


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
            Order Successful - AgroCulture
        </title>


        <style>

            * {

                margin: 0;

                padding: 0;

                box-sizing: border-box;

            }


            body {

                font-family:
                    Arial,
                    Helvetica,
                    sans-serif;

                background:
                    #f1f8f3;

                min-height: 100vh;

                display: flex;

                align-items: center;

                justify-content: center;

                padding: 20px;

            }


            /* =================================
               SUCCESS CARD
               ================================= */

            .success-card {

                width: 100%;

                max-width: 560px;

                background: white;

                border-radius: 18px;

                padding: 45px 40px;

                text-align: center;

                box-shadow:
                    0 8px 30px
                    rgba(0,0,0,0.12);

            }


            /* =================================
               SUCCESS ICON
               ================================= */

            .success-icon {

                width: 90px;

                height: 90px;

                margin:
                    0 auto 25px;

                border-radius: 50%;

                background:
                    #e8f5e9;

                color:
                    #239447;

                display: flex;

                align-items: center;

                justify-content: center;

                font-size: 52px;

                font-weight: bold;

                border:
                    3px solid #c8e6c9;

            }


            /* =================================
               TITLE
               ================================= */

            .success-card h1 {

                color:
                    #239447;

                font-size:
                    32px;

                margin-bottom:
                    12px;

                font-weight:
                    700;

            }


            .success-card .main-message {

                color:
                    #444;

                font-size:
                    18px;

                line-height:
                    1.6;

                margin-bottom:
                    18px;

            }


            /* =================================
               ORDER NUMBER
               ================================= */

            .order-number {

                background:
                    #f5faf6;

                border:
                    1px solid #d9eadb;

                border-radius:
                    10px;

                padding:
                    15px;

                margin:
                    20px 0;

                color:
                    #555;

                font-size:
                    16px;

            }


            .order-number strong {

                color:
                    #239447;

                font-size:
                    18px;

            }


            /* =================================
               INFORMATION
               ================================= */

            .success-info {

                color:
                    #777;

                font-size:
                    15px;

                line-height:
                    1.6;

                margin-bottom:
                    28px;

            }


            /* =================================
               BUTTONS
               ================================= */

            .buttons {

                display:
                    flex;

                gap:
                    12px;

            }


            .btn {

                flex: 1;

                padding:
                    14px 15px;

                border-radius:
                    7px;

                text-decoration:
                    none;

                font-size:
                    16px;

                font-weight:
                    bold;

                transition:
                    0.2s;

            }


            /* MY ORDERS */

            .orders-btn {

                background:
                    #239447;

                color:
                    white;

            }


            .orders-btn:hover {

                background:
                    #197536;

            }


            /* CONTINUE SHOPPING */

            .market-btn {

                background:
                    white;

                color:
                    #ef6c00;

                border:
                    2px solid #ef6c00;

            }


            .market-btn:hover {

                background:
                    #ef6c00;

                color:
                    white;

            }


            /* =================================
               FOOTER TEXT
               ================================= */

            .thank-you {

                margin-top:
                    25px;

                color:
                    #239447;

                font-size:
                    15px;

                font-weight:
                    600;

            }


            /* =================================
               MOBILE
               ================================= */

            @media (max-width: 550px) {


                .success-card {

                    padding:
                        35px 22px;

                }


                .success-card h1 {

                    font-size:
                        27px;

                }


                .success-card .main-message {

                    font-size:
                        16px;

                }


                .buttons {

                    flex-direction:
                        column;

                }


            }

        </style>

    </head>


    <body>


        <div class="success-card">


            <!-- SUCCESS ICON -->

            <div class="success-icon">

                ✓

            </div>


            <!-- TITLE -->

            <h1>

                Order Placed Successfully!

            </h1>


            <!-- MESSAGE -->

            <p class="main-message">

                Thank you for your order.

                Your order has been successfully

                confirmed.

            </p>


            <!-- ORDER NUMBER -->

            <div class="order-number">

                Order ID:

                <strong>

                    #<?php
                    echo $order_id;
                    ?>

                </strong>

            </div>


            <!-- INFORMATION -->

            <p class="success-info">

                Your order is now being processed.

                You can check the status and details

                anytime from your My Orders section.

            </p>


            <!-- BUTTONS -->

            <div class="buttons">


                <a

                    href="myOrders.php"

                    class="btn orders-btn"

                >

                    📋 View My Orders

                </a>


                <a

                    href="productMenu.php"

                    class="btn market-btn"

                >

                    🌱 Continue Shopping

                </a>


            </div>


            <div class="thank-you">

                Thank you for choosing AgroCulture ❤️

            </div>


        </div>


    </body>

    </html>

    <?php

    exit();

}


/* =====================================================
   NORMAL CHECKOUT PAGE
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
        AgroCulture - Checkout
    </title>


    <style>

        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

        }


        body {

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background:
                #f1f8f3;

            color: #222;

        }


        /* =================================
           HEADER
           ================================= */

        .header {

            background:
                #1f2324;

            min-height:
                80px;

            padding:
                0 4%;

            display:
                flex;

            align-items:
                center;

            justify-content:
                space-between;

            box-shadow:
                0 2px 10px
                rgba(0,0,0,0.15);

        }


        .logo {

            color:
                white;

            font-size:
                30px;

            font-weight:
                500;

        }


        .header-nav {

            display:
                flex;

            align-items:
                center;

            gap:
                28px;

        }


        .header-nav a {

            color:
                #e8f5e9;

            text-decoration:
                none;

            font-size:
                17px;

            font-weight:
                500;

        }


        .header-nav a:hover {

            color:
                #4caf50;

        }


        /* =================================
           PAGE TITLE
           ================================= */

        .page-header {

            text-align:
                center;

            padding:
                40px 20px 25px;

        }


        .page-header h1 {

            color:
                #239447;

            font-size:
                48px;

            font-weight:
                700;

            margin-bottom:
                10px;

        }


        .page-header p {

            color:
                #555;

            font-size:
                20px;

        }


        /* =================================
           MAIN
           ================================= */

        .checkout-container {

            width:
                92%;

            max-width:
                1150px;

            margin:
                15px auto 60px;

            display:
                grid;

            grid-template-columns:
                1fr 1fr;

            gap:
                25px;

        }


        /* =================================
           CARD
           ================================= */

        .card {

            background:
                white;

            border-radius:
                15px;

            padding:
                28px;

            box-shadow:
                0 5px 18px
                rgba(0,0,0,0.09);

        }


        .card-title {

            color:
                #239447;

            font-size:
                25px;

            font-weight:
                700;

            margin-bottom:
                22px;

            padding-bottom:
                12px;

            border-bottom:
                2px solid #e8f5e9;

        }


        /* =================================
           SINGLE PRODUCT
           ================================= */

        .single-product {

            display:
                flex;

            gap:
                22px;

            align-items:
                center;

        }


        .single-product img {

            width:
                190px;

            height:
                170px;

            object-fit:
                contain;

            border-radius:
                10px;

            background:
                #f8faf8;

        }


        .product-info h2 {

            font-size:
                27px;

            margin-bottom:
                10px;

        }


        .product-info p {

            color:
                #666;

            font-size:
                17px;

            margin:
                8px 0;

        }


        .product-price {

            color:
                #239447 !important;

            font-size:
                23px !important;

            font-weight:
                bold;

        }


        /* =================================
           CART ITEMS
           ================================= */

        .cart-item {

            display:
                flex;

            align-items:
                center;

            gap:
                15px;

            padding:
                15px 0;

            border-bottom:
                1px solid #eee;

        }


        .cart-item img {

            width:
                85px;

            height:
                75px;

            object-fit:
                contain;

            background:
                #f8faf8;

            border-radius:
                8px;

        }


        .cart-info {

            flex:
                1;

        }


        .cart-info h3 {

            font-size:
                19px;

            margin-bottom:
                5px;

        }


        .cart-info p {

            color:
                #666;

            font-size:
                15px;

            margin:
                3px 0;

        }


        /* =================================
           TOTAL
           ================================= */

        .total-section {

            margin-top:
                20px;

            background:
                #e8f5e9;

            border-radius:
                10px;

            padding:
                18px 20px;

            display:
                flex;

            align-items:
                center;

            justify-content:
                space-between;

        }


        .total-label {

            font-size:
                20px;

            font-weight:
                bold;

        }


        .total-value {

            color:
                #239447;

            font-size:
                28px;

            font-weight:
                bold;

        }


        /* =================================
           FORM
           ================================= */

        .form-row {

            display:
                grid;

            grid-template-columns:
                1fr 1fr;

            gap:
                15px;

        }


        .form-group {

            margin-bottom:
                16px;

        }


        .form-group.full {

            grid-column:
                1 / -1;

        }


        label {

            display:
                block;

            font-size:
                16px;

            font-weight:
                bold;

            margin-bottom:
                7px;

            color:
                #333;

        }


        input,
        textarea {

            width:
                100%;

            padding:
                12px 13px;

            border:
                1px solid #d2d2d2;

            border-radius:
                7px;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            font-size:
                16px;

        }


        input:focus,
        textarea:focus {

            outline:
                none;

            border-color:
                #239447;

        }


        textarea {

            height:
                100px;

            resize:
                vertical;

        }


        /* =================================
           PLACE ORDER
           ================================= */

        .confirm-button {

            width:
                100%;

            border:
                none;

            border-radius:
                7px;

            padding:
                15px;

            background:
                #239447;

            color:
                white;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            font-size:
                18px;

            font-weight:
                bold;

            cursor:
                pointer;

            margin-top:
                8px;

        }


        .confirm-button:hover {

            background:
                #197536;

        }


        /* =================================
           RESPONSIVE
           ================================= */

        @media (max-width: 850px) {

            .checkout-container {

                grid-template-columns:
                    1fr;

            }

        }


        @media (max-width: 600px) {

            .header {

                padding:
                    18px;

                flex-direction:
                    column;

                gap:
                    15px;

            }


            .header-nav {

                flex-wrap:
                    wrap;

                justify-content:
                    center;

                gap:
                    15px;

            }


            .page-header h1 {

                font-size:
                    38px;

            }


            .single-product {

                flex-direction:
                    column;

                text-align:
                    center;

            }


            .form-row {

                grid-template-columns:
                    1fr;

            }


            .form-group.full {

                grid-column:
                    auto;

            }

        }

    </style>

</head>


<body>


<!-- =================================
     HEADER
     ================================= -->

<header class="header">


    <div class="logo">

        AgroCulture

    </div>


    <nav class="header-nav">


        <a href="index.php">
            🏠 Home
        </a>


        <a href="productMenu.php">
            🌱 Digital Market
        </a>


        <a href="myCart.php">
            🛒 My Cart
        </a>


        <a href="myOrders.php">
            📋 My Orders
        </a>


    </nav>


</header>



<!-- =================================
     TITLE
     ================================= -->

<section class="page-header">


    <h1>

        Checkout

    </h1>


    <p>

        Review your order and enter your delivery details.

    </p>


</section>



<!-- =================================
     CHECKOUT CONTAINER
     ================================= -->

<div class="checkout-container">


    <!-- =================================
         ORDER SUMMARY
         ================================= -->

    <div class="card">


        <div class="card-title">

            Order Summary

        </div>


        <?php if ($singleProduct) { ?>


            <div class="single-product">


                <?php

                $imagePath =
                    "images/productImages/"
                    . $image;

                ?>


                <img

                    src="<?php
                    echo htmlspecialchars(
                        $imagePath
                    );
                    ?>"

                    alt="<?php
                    echo htmlspecialchars(
                        $productName
                    );
                    ?>"

                >


                <div class="product-info">


                    <h2>

                        <?php
                        echo htmlspecialchars(
                            $productName
                        );
                        ?>

                    </h2>


                    <p>

                        Category:
                        <?php
                        echo htmlspecialchars(
                            $category
                        );
                        ?>

                    </p>


                    <p>

                        Quantity: 1

                    </p>


                    <p class="product-price">

                        ₹<?php
                        echo number_format(
                            $price,
                            2
                        );
                        ?>

                    </p>


                </div>


            </div>


        <?php } else { ?>


            <?php foreach (
                $cartItems
                as $item
            ) { ?>


                <div class="cart-item">


                    <?php

                    $itemImage =
                        "images/productImages/"
                        . $item['pimage'];

                    ?>


                    <img

                        src="<?php
                        echo htmlspecialchars(
                            $itemImage
                        );
                        ?>"

                        alt="<?php
                        echo htmlspecialchars(
                            $item['product']
                        );
                        ?>"

                    >


                    <div class="cart-info">


                        <h3>

                            <?php
                            echo htmlspecialchars(
                                $item['product']
                            );
                            ?>

                        </h3>


                        <p>

                            Quantity:
                            <?php
                            echo (int)
                                $item['quantity'];
                            ?>

                        </p>


                        <p>

                            ₹<?php
                            echo number_format(
                                (float)$item['price'],
                                2
                            );
                            ?>

                        </p>


                    </div>


                </div>


            <?php } ?>


        <?php } ?>


        <!-- TOTAL -->

        <div class="total-section">


            <span class="total-label">

                Grand Total

            </span>


            <span class="total-value">

                ₹<?php
                echo number_format(
                    $total,
                    2
                );
                ?>

            </span>


        </div>


    </div>



    <!-- =================================
         DELIVERY INFORMATION
         ================================= -->

    <div class="card">


        <div class="card-title">

            Delivery Information

        </div>


        <form method="POST">


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

                        required

                    >


                </div>


                <div class="form-group full">


                    <label>
                        Email Address
                    </label>


                    <input

                        type="email"

                        name="email"

                        placeholder="Enter your email"

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


                <div class="form-group">


                    <label>
                        Pincode
                    </label>


                    <input

                        type="text"

                        name="pincode"

                        placeholder="Enter pincode"

                        required

                    >


                </div>


                <div class="form-group full">


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


            <button

                type="submit"

                name="confirm_order"

                class="confirm-button"

            >

                🛍️ Place Order

            </button>


        </form>


    </div>


</div>


</body>

</html>