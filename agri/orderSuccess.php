<?php

session_start();
require 'db.php';

/* =====================================================
   CHECK ORDER ID
===================================================== */

if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    die("Order ID not received.");
}

$orderId = intval($_GET['order_id']);

if ($orderId <= 0) {
    die("Invalid Order ID.");
}


/* =====================================================
   GET ORDER DETAILS
===================================================== */

$sql = "
    SELECT
        order_id,
        total_amount,
        name,
        order_date,
        status
    FROM orders
    WHERE order_id = '$orderId'
    LIMIT 1
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die(
        "Unable to load order: " .
        mysqli_error($conn)
    );
}

if (mysqli_num_rows($result) == 0) {
    die("Order not found.");
}

$order = mysqli_fetch_assoc($result);

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
        AgroCulture - Order Successful
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            padding: 0;

            background: #eef7f0;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

        }


        .success-page {

            min-height: 100vh;

            display: flex;

            justify-content: center;

            align-items: center;

            padding: 30px;

        }


        .success-container {

            width: 100%;

            max-width: 700px;

            background: white;

            padding: 50px;

            border-radius: 20px;

            text-align: center;

            box-shadow:
                0 8px 30px
                rgba(0,0,0,0.12);

        }


        .success-icon {

            width: 90px;

            height: 90px;

            margin: 0 auto 25px;

            border-radius: 50%;

            background: #28a745;

            color: white;

            display: flex;

            justify-content: center;

            align-items: center;

            font-size: 50px;

            font-weight: bold;

        }


        h1 {

            color: #238b45;

            font-size: 40px;

            margin:
                0 0 15px 0;

        }


        .success-message {

            color: #555;

            font-size: 20px;

            margin-bottom: 30px;

        }


        .order-box {

            background: #f1f8f3;

            border:
                1px solid #d5eadb;

            border-radius: 12px;

            padding: 25px;

            margin-bottom: 30px;

            text-align: left;

        }


        .order-row {

            display: flex;

            justify-content: space-between;

            padding: 12px 0;

            border-bottom:
                1px solid #ddd;

            font-size: 18px;

        }


        .order-row:last-child {

            border-bottom: none;

        }


        .label {

            font-weight: bold;

            color: #444;

        }


        .value {

            color: #238b45;

            font-weight: bold;

        }


        .buttons {

            display: flex;

            justify-content: center;

            gap: 15px;

            flex-wrap: wrap;

        }


        .button {

            display: inline-block;

            text-decoration: none;

            padding:
                14px 28px;

            border-radius: 8px;

            font-size: 17px;

            font-weight: bold;

        }


        .orders-button {

            background: #238b45;

            color: white;

        }


        .home-button {

            background: #007bff;

            color: white;

        }


        .orders-button:hover {

            background: #1b7038;

        }


        .home-button:hover {

            background: #0069d9;

        }


        @media screen and (max-width: 600px) {

            .success-container {

                padding: 30px 20px;

            }


            h1 {

                font-size: 30px;

            }


            .success-message {

                font-size: 17px;

            }


            .order-row {

                font-size: 16px;

            }

        }

    </style>

</head>


<body>


<div class="success-page">


    <div class="success-container">


        <div class="success-icon">

            ✓

        </div>


        <h1>

            Order Placed Successfully!

        </h1>


        <p class="success-message">

            Thank you,

            <strong>

                <?php

                echo htmlspecialchars(
                    $order['name']
                );

                ?>

            </strong>

            ! Your order has been placed successfully.

        </p>


        <div class="order-box">


            <div class="order-row">

                <span class="label">

                    Order ID

                </span>

                <span class="value">

                    #

                    <?php

                    echo $order['order_id'];

                    ?>

                </span>

            </div>


            <div class="order-row">

                <span class="label">

                    Order Date

                </span>

                <span>

                    <?php

                    echo htmlspecialchars(
                        $order['order_date']
                    );

                    ?>

                </span>

            </div>


            <div class="order-row">

                <span class="label">

                    Total Amount

                </span>

                <span class="value">

                    ₹

                    <?php

                    echo number_format(
                        $order['total_amount'],
                        2
                    );

                    ?>

                </span>

            </div>


            <div class="order-row">

                <span class="label">

                    Status

                </span>

                <span class="value">

                    <?php

                    echo htmlspecialchars(
                        $order['status']
                    );

                    ?>

                </span>

            </div>


        </div>


        <div class="buttons">


            <a
                href="myOrders.php"
                class="button orders-button"
            >

                View My Orders

            </a>


            <a
                href="index.php"
                class="button home-button"
            >

                Continue Shopping

            </a>


        </div>


    </div>


</div>


</body>

</html>