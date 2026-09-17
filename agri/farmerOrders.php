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
   CHECK FARMER
===================================================== */

if (
    isset($_SESSION['Category']) &&
    $_SESSION['Category'] != 1
) {
    $_SESSION['message'] = "Only farmers can access this page.";

    header("Location: index.php");
    exit();
}

/* =====================================================
   GET LOGGED-IN FARMER ID
===================================================== */

$farmerId = intval($_SESSION['id']);

/* =====================================================
   UPDATE ORDER STATUS
===================================================== */

if (
    isset($_POST['update_status']) &&
    isset($_POST['order_id']) &&
    isset($_POST['status'])
) {
    $orderId = intval($_POST['order_id']);

    $status = mysqli_real_escape_string(
        $conn,
        $_POST['status']
    );

    $updateSql = "
        UPDATE orders
        SET status = '$status'
        WHERE order_id = '$orderId'
    ";

    if (!mysqli_query($conn, $updateSql)) {
        die(
            "Unable to update order status: " .
            mysqli_error($conn)
        );
    }

    header("Location: farmerOrders.php");
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

    <title>AgroCulture - Manage Orders</title>

    <link
        rel="stylesheet"
        href="bootstrap/css/bootstrap.min.css"
    >

    <style>

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

        body {
            background: #eef7f0;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* =================================================
           MAIN PAGE
        ================================================= */

        .orders-page {
            width: 100%;
            padding: 95px 20px 50px;
        }

        /* =================================================
           TITLE
        ================================================= */

        .page-title {
            text-align: center;
            color: #238b45;
            font-size: 38px;
            font-weight: bold;
            margin: 0 0 8px;
            line-height: 1.2;
        }

        .page-description {
            text-align: center;
            color: #555;
            font-size: 16px;
            margin: 0 0 30px;
        }

        /* =================================================
           ORDER CONTAINER
        ================================================= */

        .orders-container {
            width: 100%;
            max-width: 1550px;
            margin: 0 auto;
            background: white;
            padding: 18px;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.10);
            overflow-x: auto;
        }

        /* =================================================
           MAIN TABLE
        ================================================= */

        .orders-table {
            width: 100%;
            min-width: 1250px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .orders-table th {
            background: #238b45;
            color: white;
            padding: 12px 8px;
            text-align: center;
            vertical-align: middle;
            font-size: 15px;
            border: 1px solid #d5d5d5;
        }

        .orders-table td {
            padding: 10px 8px;
            text-align: center;
            vertical-align: top;
            font-size: 14px;
            color: #333;
            border: 1px solid #dddddd;
        }

        .orders-table tbody tr:hover td {
            background: #f7fbf8;
        }

        /* =================================================
           ORDER DETAILS
        ================================================= */

        .order-id {
            font-size: 16px;
            font-weight: bold;
        }

        .buyer-name {
            font-weight: bold;
            color: #222;
            font-size: 15px;
        }

        .buyer-address {
            font-size: 13px;
            color: #555;
            line-height: 1.5;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: anywhere;
        }

        .order-date {
            white-space: nowrap;
            font-size: 13px;
        }

        .total-amount {
            font-size: 16px;
            font-weight: bold;
            white-space: nowrap;
        }

        /* =================================================
           ITEMS TABLE
        ================================================= */

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th {
            background: #4caf50;
            color: white;
            font-size: 12px;
            padding: 7px 5px;
        }

        .items-table td {
            padding: 7px 5px;
            font-size: 13px;
            vertical-align: middle;
        }

        .product-image {
            width: 55px;
            height: 55px;
            object-fit: cover;
            border-radius: 7px;
            display: block;
            margin: auto;
        }

        .product-name {
            font-weight: bold;
            font-size: 13px;
        }

        /* =================================================
           STATUS
        ================================================= */

        .status-badge {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 22px;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 9px;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .accepted {
            background: #d4edda;
            color: #155724;
        }

        .delivered {
            background: #cce5ff;
            color: #004085;
        }

        .cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        /* =================================================
           BUTTONS
        ================================================= */

        .action-area {
            min-width: 145px;
        }

        .action-button {
            width: 100%;
            padding: 9px 10px;
            border: none;
            border-radius: 7px;
            color: white;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .accept-button {
            background: #28a745;
        }

        .accept-button:hover {
            background: #218838;
        }

        .delivered-button {
            background: #007bff;
        }

        .delivered-button:hover {
            background: #0056b3;
        }

        /* =================================================
           NO ORDERS
        ================================================= */

        .no-orders {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #666;
        }

        /* =================================================
           MOBILE
        ================================================= */

        @media screen and (max-width: 900px) {

            .orders-page {
                padding: 90px 10px 40px;
            }

            .page-title {
                font-size: 30px;
            }

            .page-description {
                font-size: 14px;
            }

            .orders-container {
                padding: 10px;
            }

            .orders-table {
                min-width: 1250px;
            }

        }

    </style>

</head>

<body>

<?php require 'menu.php'; ?>

<div class="orders-page">

    <h1 class="page-title">
        Manage Orders
    </h1>

    <p class="page-description">
        View customer orders, delivery address and update delivery status.
    </p>

    <div class="orders-container">

        <?php

        /* =====================================================
           GET ORDERS WITH BUYER NAME AND ADDRESS
        ===================================================== */

        $orderSql = "

            SELECT

                orders.order_id,
                orders.bid,
                orders.total_amount,
                orders.order_date,
                orders.status,
                orders.address AS buyer_address,

                buyer.bname AS buyer_name

            FROM orders

            LEFT JOIN buyer
                ON orders.bid = buyer.bid

            ORDER BY orders.order_id DESC

        ";

        $orderResult = mysqli_query(
            $conn,
            $orderSql
        );

        if (!$orderResult) {

            die(
                "Unable to fetch orders: " .
                mysqli_error($conn)
            );

        }

        if (mysqli_num_rows($orderResult) == 0) {

        ?>

            <div class="no-orders">
                No customer orders available.
            </div>

        <?php

        } else {

        ?>

            <table class="orders-table">

                <thead>

                    <tr>

                        <th style="width: 7%;">
                            Order ID
                        </th>

                        <th style="width: 11%;">
                            Buyer
                        </th>

                        <th style="width: 18%;">
                            Address
                        </th>

                        <th style="width: 12%;">
                            Date
                        </th>

                        <th style="width: 32%;">
                            Products
                        </th>

                        <th style="width: 10%;">
                            Total
                        </th>

                        <th style="width: 16%;">
                            Status / Action
                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php

                while (
                    $order = mysqli_fetch_assoc($orderResult)
                ) {

                    $orderId = intval(
                        $order['order_id']
                    );

                    $buyerName = !empty(
                        $order['buyer_name']
                    )
                        ? $order['buyer_name']
                        : 'Unknown Buyer';

                    $buyerAddress = !empty(
                        $order['buyer_address']
                    )
                        ? $order['buyer_address']
                        : 'Address not available';

                    $orderDate = $order['order_date'];

                    $totalAmount = $order['total_amount'];

                    $status = !empty(
                        $order['status']
                    )
                        ? $order['status']
                        : 'Pending';

                    $statusLower = strtolower(
                        trim($status)
                    );

                ?>

                    <tr>

                        <!-- ORDER ID -->

                        <td>

                            <div class="order-id">

                                #
                                <?php
                                echo htmlspecialchars(
                                    $orderId
                                );
                                ?>

                            </div>

                        </td>

                        <!-- BUYER NAME -->

                        <td>

                            <div class="buyer-name">

                                <?php
                                echo htmlspecialchars(
                                    $buyerName
                                );
                                ?>

                            </div>

                        </td>

                        <!-- BUYER ADDRESS -->

                        <td>

                            <div class="buyer-address">

                                <?php

                                echo nl2br(
                                    htmlspecialchars(
                                        $buyerAddress
                                    )
                                );

                                ?>

                            </div>

                        </td>

                        <!-- ORDER DATE -->

                        <td>

                            <div class="order-date">

                                <?php
                                echo htmlspecialchars(
                                    $orderDate
                                );
                                ?>

                            </div>

                        </td>

                        <!-- PRODUCTS -->

                        <td>

                        <?php

                        $itemsSql = "

                            SELECT

                                oi.pid,
                                oi.quantity,
                                oi.price,
                                fp.product,
                                fp.pimage

                            FROM order_items oi

                            INNER JOIN fproduct fp
                                ON oi.pid = fp.pid

                            WHERE oi.order_id = '$orderId'

                            AND fp.fid = '$farmerId'

                        ";

                        $itemsResult = mysqli_query(
                            $conn,
                            $itemsSql
                        );

                        if (!$itemsResult) {

                            echo "<span style='color:red;'>
                                Unable to load products
                            </span>";

                        } elseif (
                            mysqli_num_rows($itemsResult) == 0
                        ) {

                            echo "<span style='color:#777;'>
                                No products from this farmer
                            </span>";

                        } else {

                        ?>

                            <table class="items-table">

                                <thead>

                                    <tr>

                                        <th>
                                            Image
                                        </th>

                                        <th>
                                            Product
                                        </th>

                                        <th>
                                            Qty
                                        </th>

                                        <th>
                                            Price
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                <?php

                                while (
                                    $item =
                                    mysqli_fetch_assoc(
                                        $itemsResult
                                    )
                                ) {

                                    $itemImage =
                                        $item['pimage'];

                                    $itemProduct =
                                        $item['product'];

                                    $itemQuantity =
                                        $item['quantity'];

                                    $itemPrice =
                                        $item['price'];

                                    $imagePath =
                                        "images/productImages/" .
                                        $itemImage;

                                ?>

                                    <tr>

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
                                                    $itemProduct
                                                );
                                                ?>"
                                            >

                                        </td>

                                        <td>

                                            <span class="product-name">

                                                <?php
                                                echo htmlspecialchars(
                                                    $itemProduct
                                                );
                                                ?>

                                            </span>

                                        </td>

                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $itemQuantity
                                            );
                                            ?>

                                        </td>

                                        <td>

                                            ₹<?php
                                            echo number_format(
                                                $itemPrice,
                                                2
                                            );
                                            ?>

                                        </td>

                                    </tr>

                                <?php

                                }

                                ?>

                                </tbody>

                            </table>

                        <?php

                        }

                        ?>

                        </td>

                        <!-- TOTAL AMOUNT -->

                        <td>

                            <div class="total-amount">

                                ₹<?php
                                echo number_format(
                                    $totalAmount,
                                    2
                                );
                                ?>

                            </div>

                        </td>

                        <!-- STATUS AND ACTION -->

                        <td>

                            <div class="action-area">

                            <?php

                            if ($statusLower == 'pending') {

                            ?>

                                <div class="status-badge pending">
                                    Pending
                                </div>

                                <form
                                    method="POST"
                                    action="farmerOrders.php"
                                >

                                    <input
                                        type="hidden"
                                        name="order_id"
                                        value="<?php
                                        echo $orderId;
                                        ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="Accepted"
                                    >

                                    <button
                                        type="submit"
                                        name="update_status"
                                        class="action-button accept-button"
                                    >
                                        Accept
                                    </button>

                                </form>

                            <?php

                            } elseif (
                                $statusLower == 'accepted'
                            ) {

                            ?>

                                <div class="status-badge accepted">
                                    ✓ Accepted
                                </div>

                                <form
                                    method="POST"
                                    action="farmerOrders.php"
                                >

                                    <input
                                        type="hidden"
                                        name="order_id"
                                        value="<?php
                                        echo $orderId;
                                        ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="status"
                                        value="Delivered"
                                    >

                                    <button
                                        type="submit"
                                        name="update_status"
                                        class="action-button delivered-button"
                                    >
                                        Delivered
                                    </button>

                                </form>

                            <?php

                            } elseif (
                                $statusLower == 'delivered'
                            ) {

                            ?>

                                <div class="status-badge delivered">
                                    ✓ Delivered
                                </div>

                            <?php

                            } elseif (
                                $statusLower == 'cancelled'
                            ) {

                            ?>

                                <div class="status-badge cancelled">
                                    Cancelled
                                </div>

                            <?php

                            } else {

                            ?>

                                <div class="status-badge pending">

                                    <?php
                                    echo htmlspecialchars(
                                        $status
                                    );
                                    ?>

                                </div>

                            <?php

                            }

                            ?>

                            </div>

                        </td>

                    </tr>

                <?php

                }

                ?>

                </tbody>

            </table>

        <?php

        }

        ?>

    </div>

</div>

</body>

</html>