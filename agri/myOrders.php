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

$bid = intval($_SESSION['id']);


/* ==========================================
   CANCEL ORDER
========================================== */

if (
    isset($_GET['cancel_order']) &&
    $_GET['cancel_order'] == 1 &&
    isset($_GET['order_id'])
) {

    $orderId = intval($_GET['order_id']);

    if ($orderId <= 0) {
        die("Invalid order ID.");
    }

    $cancelSql = "
        UPDATE orders
        SET status = 'Cancelled'
        WHERE order_id = '$orderId'
        AND bid = '$bid'
        AND status = 'Pending'
    ";

    $cancelResult = mysqli_query(
        $conn,
        $cancelSql
    );

    if (!$cancelResult) {
        die(
            "Unable to cancel order: " .
            mysqli_error($conn)
        );
    }

    if (mysqli_affected_rows($conn) > 0) {

        $_SESSION['order_success'] =
            "Your order has been cancelled successfully.";

    } else {

        $_SESSION['order_success'] =
            "This order cannot be cancelled.";

    }

    header("Location: myOrders.php");
    exit();
}


/* ==========================================
   GET BUYER ORDERS
========================================== */

$sql = "

    SELECT
        order_id,
        bid,
        total_amount,
        name,
        order_date,
        status

    FROM orders

    WHERE bid = '$bid'

    ORDER BY order_id DESC

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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>AgroCulture - My Orders</title>

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
        }

        /* ==========================================
           MAIN PAGE
        ========================================== */

        .orders-page {
            width: 100%;
            padding: 125px 20px 50px;
        }

        /* ==========================================
           TITLE
        ========================================== */

        .page-title {
            text-align: center;
            color: #218838;
            font-size: 38px;
            font-weight: bold;
            margin: 0 0 10px;
        }

        .page-description {
            text-align: center;
            color: #555;
            font-size: 17px;
            margin: 0 0 30px;
        }

        /* ==========================================
           SUCCESS MESSAGE
        ========================================== */

        .order-success-message {
            width: 90%;
            max-width: 1100px;
            margin: 0 auto 25px;
            padding: 14px 20px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 8px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        /* ==========================================
           ORDERS CONTAINER
        ========================================== */

        .orders-container {
            width: 100%;
            max-width: 1250px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.08);
            overflow-x: auto;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .orders-table th {
            background: #218838;
            color: white;
            padding: 13px 8px;
            text-align: center;
            vertical-align: middle;
            font-size: 16px;
            border: 1px solid #d5d5d5;
        }

        .orders-table td {
            padding: 13px 8px;
            text-align: center;
            vertical-align: middle;
            font-size: 15px;
            color: #333;
            border: 1px solid #dddddd;
        }

        .orders-table tbody tr:hover td {
            background: #f7fbf8;
        }

        .order-id-column {
            width: 11%;
        }

        .buyer-column {
            width: 17%;
        }

        .date-column {
            width: 21%;
        }

        .amount-column {
            width: 15%;
        }

        .status-column {
            width: 16%;
        }

        .action-column {
            width: 20%;
        }

        .order-id {
            font-size: 16px;
            font-weight: bold;
        }

        .buyer-name {
            font-size: 16px;
            font-weight: bold;
            color: #222;
        }

        .order-date {
            white-space: nowrap;
            font-size: 14px;
        }

        .total-amount {
            font-size: 16px;
            font-weight: bold;
            white-space: nowrap;
        }

        /* ==========================================
           STATUS BADGES
        ========================================== */

        .status-badge {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
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
           ACTION BUTTONS
        ========================================== */

        .details-button,
        .cancel-button {
            display: inline-block;
            width: 100%;
            padding: 10px 8px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }

        .details-button {
            background: #007bff;
            color: white;
            margin-bottom: 8px;
        }

        .details-button:hover {
            background: #0056b3;
            color: white;
            text-decoration: none;
        }

        .cancel-button {
            background: #dc3545;
            color: white;
            border: none;
        }

        .cancel-button:hover {
            background: #b52a37;
            color: white;
        }

        .completed-message {
            margin-top: 6px;
            color: #777;
            font-size: 13px;
        }

        .no-orders {
            text-align: center;
            padding: 50px;
            color: #777;
            font-size: 19px;
        }

        /* ==========================================
           PROFESSIONAL CANCEL MODAL
        ========================================== */

        .cancel-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .cancel-modal-box {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 16px;
            padding: 30px 25px;
            text-align: center;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
            animation: modalShow 0.25s ease;
        }

        .cancel-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fff3cd;
            color: #856404;
            font-size: 34px;
            font-weight: bold;
        }

        .cancel-modal-box h2 {
            margin: 0 0 12px;
            color: #333;
            font-size: 25px;
        }

        .cancel-modal-box p {
            margin: 8px 0;
            color: #555;
            font-size: 16px;
        }

        .cancel-warning {
            color: #dc3545 !important;
            font-size: 14px !important;
            font-weight: bold;
        }

        .cancel-modal-buttons {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 25px;
        }

        .keep-order-button,
        .confirm-cancel-button {
            flex: 1;
            padding: 11px 12px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        .keep-order-button {
            background: #6c757d;
            color: white;
            border: none;
        }

        .keep-order-button:hover {
            background: #545b62;
        }

        .confirm-cancel-button {
            background: #dc3545;
            color: white;
        }

        .confirm-cancel-button:hover {
            background: #b52a37;
            color: white;
            text-decoration: none;
        }

        @keyframes modalShow {

            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }

        }

        /* ==========================================
           MOBILE
        ========================================== */

        @media screen and (max-width: 900px) {

            .orders-page {
                padding: 105px 10px 40px;
            }

            .page-title {
                font-size: 30px;
            }

            .page-description {
                font-size: 15px;
            }

            .orders-container {
                padding: 10px;
            }

            .orders-table {
                min-width: 950px;
            }

        }

        @media screen and (max-width: 500px) {

            .cancel-modal-buttons {
                flex-direction: column;
            }

            .keep-order-button,
            .confirm-cancel-button {
                width: 100%;
            }

        }

    </style>

</head>

<body>

<?php require 'menu.php'; ?>

<div class="orders-page">

    <h1 class="page-title">
        My Orders
    </h1>

    <p class="page-description">
        View all your orders and their current status.
    </p>


    <?php if (isset($_SESSION['order_success'])) { ?>

        <div class="order-success-message">

            ✓
            <?php
            echo htmlspecialchars(
                $_SESSION['order_success']
            );
            ?>

        </div>

        <?php
        unset($_SESSION['order_success']);
        ?>

    <?php } ?>


    <div class="orders-container">

        <?php if (mysqli_num_rows($result) > 0) { ?>

            <table class="orders-table">

                <thead>

                    <tr>

                        <th class="order-id-column">
                            Order ID
                        </th>

                        <th class="buyer-column">
                            Buyer Name
                        </th>

                        <th class="date-column">
                            Order Date
                        </th>

                        <th class="amount-column">
                            Total Amount
                        </th>

                        <th class="status-column">
                            Order Status
                        </th>

                        <th class="action-column">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody>

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                        <?php

                        $orderId = intval(
                            $row['order_id']
                        );

                        $buyerName = $row['name'];

                        $orderDate = $row['order_date'];

                        $totalAmount = $row['total_amount'];

                        $status = !empty($row['status'])
                            ? $row['status']
                            : 'Pending';

                        $statusLower = strtolower(
                            trim($status)
                        );

                        ?>

                        <tr>

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

                            <td>

                                <div class="buyer-name">

                                    <?php
                                    echo htmlspecialchars(
                                        $buyerName
                                    );
                                    ?>

                                </div>

                            </td>

                            <td>

                                <div class="order-date">

                                    <?php
                                    echo htmlspecialchars(
                                        $orderDate
                                    );
                                    ?>

                                </div>

                            </td>

                            <td>

                                <div class="total-amount">

                                    ₹
                                    <?php
                                    echo number_format(
                                        $totalAmount,
                                        2
                                    );
                                    ?>

                                </div>

                            </td>

                            <td>

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
                                        <?php
                                        echo htmlspecialchars(
                                            $status
                                        );
                                        ?>
                                    </span>

                                <?php } ?>

                            </td>

                            <td>

                                <a
                                    href="orderDetails.php?order_id=<?php echo $orderId; ?>"
                                    class="details-button"
                                >
                                    View Details
                                </a>


                                <?php if ($statusLower == 'pending') { ?>

                                    <button
                                        type="button"
                                        class="cancel-button"
                                        onclick="openCancelModal(<?php echo $orderId; ?>)"
                                    >
                                        Cancel Order
                                    </button>

                                <?php } elseif ($statusLower == 'delivered') { ?>

                                    <div class="completed-message">
                                        Order completed.
                                    </div>

                                <?php } elseif ($statusLower == 'cancelled') { ?>

                                    <div class="completed-message">
                                        Order cancelled.
                                    </div>

                                <?php } ?>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        <?php } else { ?>

            <div class="no-orders">
                No Orders Found
            </div>

        <?php } ?>

    </div>

</div>


<!-- ==========================================
     CANCEL CONFIRMATION MODAL
========================================== -->

<div
    id="cancelModal"
    class="cancel-modal"
>

    <div class="cancel-modal-box">

        <div class="cancel-icon">
            !
        </div>

        <h2>
            Cancel Order?
        </h2>

        <p>
            Are you sure you want to cancel this order?
        </p>

        <p class="cancel-warning">
            This action cannot be undone.
        </p>

        <div class="cancel-modal-buttons">

            <button
                type="button"
                class="keep-order-button"
                onclick="closeCancelModal()"
            >
                Keep Order
            </button>

            <a
                id="confirmCancelButton"
                href="#"
                class="confirm-cancel-button"
            >
                Yes, Cancel
            </a>

        </div>

    </div>

</div>


<script>

function openCancelModal(orderId) {

    document.getElementById("cancelModal").style.display = "flex";

    document.getElementById("confirmCancelButton").href =
        "myOrders.php?cancel_order=1&order_id=" + orderId;

}

function closeCancelModal() {

    document.getElementById("cancelModal").style.display = "none";

}

window.onclick = function(event) {

    var modal = document.getElementById("cancelModal");

    if (event.target === modal) {
        closeCancelModal();
    }

};

document.addEventListener("keydown", function(event) {

    if (event.key === "Escape") {
        closeCancelModal();
    }

});

</script>

</body>

</html>