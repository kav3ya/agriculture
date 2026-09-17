<?php

session_start();
require 'db.php';

/* ==========================================
   CHECK LOGIN
========================================== */

if (
    !isset($_SESSION['logged_in']) ||
    $_SESSION['logged_in'] != 1
) {
    header("Location: Login/error.php");
    exit();
}


/* ==========================================
   BUYER ID
========================================== */

$bid = intval($_SESSION['id']);


/* ==========================================
   ORDER ID
========================================== */

if (!isset($_GET['order_id'])) {
    die("Order ID is missing.");
}

$orderId = intval($_GET['order_id']);

if ($orderId <= 0) {
    die("Invalid Order ID.");
}


/* ==========================================
   GET ORDER DETAILS
========================================== */

$sql = "
    SELECT
        order_id,
        bid,
        total_amount,
        name,
        mobile,
        address,
        order_date,
        status
    FROM orders
    WHERE order_id = '$orderId'
    AND bid = '$bid'
    LIMIT 1
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die(
        "Database Error: " .
        mysqli_error($conn)
    );
}

if (mysqli_num_rows($result) == 0) {
    die("Order not found.");
}

$order = mysqli_fetch_assoc($result);


/* ==========================================
   ORDER VALUES
========================================== */

$orderId = $order['order_id'];
$name = $order['name'];
$mobile = $order['mobile'];
$address = $order['address'];
$orderDate = $order['order_date'];
$totalAmount = $order['total_amount'];

$status = !empty($order['status'])
    ? $order['status']
    : 'Pending';

$statusLower = strtolower(
    trim($status)
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>AgroCulture - Order Details</title>

    <link
        href="bootstrap/css/bootstrap.min.css"
        rel="stylesheet"
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
            background: #f1f8f3;
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
        }

        /* ==========================================
           MAIN PAGE
        ========================================== */

        .order-details-page {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 125px 20px 40px;
        }

        /* ==========================================
           TITLE
        ========================================== */

        .page-title {
            text-align: center;
            color: #218838;
            font-size: 34px;
            font-weight: bold;
            line-height: 1.2;
            margin: 0 0 10px;
        }

        .page-description {
            text-align: center;
            color: #555;
            font-size: 17px;
            margin: 0 0 30px;
        }

        .page-description strong {
            color: #218838;
        }

        /* ==========================================
           DETAILS BOX
        ========================================== */

        .details-container {
            width: 100%;
            max-width: 950px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .details-heading {
            background: #218838;
            color: white;
            padding: 17px 22px;
            font-size: 23px;
            font-weight: bold;
        }

        .details-content {
            padding: 22px 28px;
        }

        /* ==========================================
           DETAIL ROWS
        ========================================== */

        .detail-row {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 12px 0;
            border-bottom: 1px solid #e5e5e5;
            font-size: 16px;
            line-height: 1.5;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            width: 150px;
            min-width: 150px;
            font-weight: bold;
            color: #222;
        }

        .detail-value {
            flex: 1;
            color: #555;
            word-break: break-word;
        }

        /* ==========================================
           STATUS
        ========================================== */

        .status-badge {
            display: inline-block;
            padding: 7px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-accepted {
            background: #d4edda;
            color: #155724;
        }

        .status-delivered {
            background: #cce5ff;
            color: #004085;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .status-default {
            background: #e2e3e5;
            color: #383d41;
        }

        /* ==========================================
           BACK BUTTON
        ========================================== */

        .button-area {
            display: flex;
            justify-content: center;
            margin-top: 25px;
        }

        .back-button {
            display: inline-block;
            background: #218838;
            color: white;
            padding: 10px 22px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 15px;
            font-weight: bold;
        }

        .back-button:hover {
            background: #176b2c;
            color: white;
            text-decoration: none;
        }

        /* ==========================================
           MOBILE
        ========================================== */

        @media screen and (max-width: 700px) {

            .order-details-page {
                padding: 105px 12px 35px;
            }

            .page-title {
                font-size: 28px;
            }

            .page-description {
                font-size: 15px;
                margin-bottom: 25px;
            }

            .details-heading {
                padding: 15px 18px;
                font-size: 20px;
            }

            .details-content {
                padding: 18px;
            }

            .detail-row {
                display: block;
                font-size: 15px;
                padding: 10px 0;
            }

            .detail-label {
                width: 100%;
                min-width: 100%;
                display: block;
                margin-bottom: 3px;
            }

            .detail-value {
                display: block;
            }

            .back-button {
                width: 100%;
                text-align: center;
            }

        }

    </style>

</head>

<body>

<?php require 'menu.php'; ?>

<div class="order-details-page">

    <h1 class="page-title">
        Order Details
    </h1>

    <p class="page-description">

        Order ID:
        <strong>
            #
            <?php echo htmlspecialchars($orderId); ?>
        </strong>

    </p>


    <div class="details-container">

        <div class="details-heading">
            Delivery Details
        </div>

        <div class="details-content">

            <div class="detail-row">

                <div class="detail-label">
                    Name:
                </div>

                <div class="detail-value">
                    <?php echo htmlspecialchars($name); ?>
                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Mobile:
                </div>

                <div class="detail-value">
                    <?php echo htmlspecialchars($mobile); ?>
                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Address:
                </div>

                <div class="detail-value">

                    <?php
                    echo nl2br(
                        htmlspecialchars($address)
                    );
                    ?>

                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Order Date:
                </div>

                <div class="detail-value">
                    <?php echo htmlspecialchars($orderDate); ?>
                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Total Amount:
                </div>

                <div class="detail-value">

                    ₹
                    <?php
                    echo number_format(
                        $totalAmount,
                        2
                    );
                    ?>

                </div>

            </div>


            <div class="detail-row">

                <div class="detail-label">
                    Order Status:
                </div>

                <div class="detail-value">

                    <?php if ($statusLower == 'pending') { ?>

                        <span class="status-badge status-pending">
                            Pending
                        </span>

                    <?php } elseif ($statusLower == 'accepted') { ?>

                        <span class="status-badge status-accepted">
                            Accepted
                        </span>

                    <?php } elseif ($statusLower == 'delivered') { ?>

                        <span class="status-badge status-delivered">
                            Delivered
                        </span>

                    <?php } elseif ($statusLower == 'cancelled') { ?>

                        <span class="status-badge status-cancelled">
                            Cancelled
                        </span>

                    <?php } else { ?>

                        <span class="status-badge status-default">
                            <?php echo htmlspecialchars($status); ?>
                        </span>

                    <?php } ?>

                </div>

            </div>

        </div>

    </div>


    <div class="button-area">

        <a
            href="myOrders.php"
            class="back-button"
        >
            ← Back to My Orders
        </a>

    </div>

</div>

</body>

</html>