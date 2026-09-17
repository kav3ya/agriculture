<?php
session_start();

require 'db.php';

/* CHECK LOGIN */
if (
    !isset($_SESSION['logged_in']) ||
    $_SESSION['logged_in'] != 1
) {
    $_SESSION['message'] = "You need to login first.";

    header("Location: Login/login.php");
    exit();
}

/* USER ID */
$userid = isset($_SESSION['id'])
    ? intval($_SESSION['id'])
    : 0;

/* CHECK FARMER */
$isFarmer = false;

if (
    isset($_SESSION['Category']) &&
    $_SESSION['Category'] == 1
) {
    $isFarmer = true;
}

/* PAGE MODE */
$mode = isset($_GET['mode'])
    ? $_GET['mode']
    : 'all';

if ($mode != 'search' && $mode != 'all') {
    $mode = 'all';
}

/* CATEGORY */
$n = isset($_GET['n'])
    ? intval($_GET['n'])
    : 0;


/* =====================================================
   ADD TO CART
===================================================== */

if (
    isset($_GET['flag']) &&
    $_GET['flag'] == 1 &&
    isset($_GET['pid'])
) {
    $pid = intval($_GET['pid']);

    if ($pid <= 0) {
        die("Invalid product ID.");
    }

    if ($isFarmer) {
        header("Location: productMenu.php?mode=all");
        exit();
    }

    /* CHECK PRODUCT */
    $checkProductSql = "
        SELECT pid
        FROM fproduct
        WHERE pid = '$pid'
    ";

    $checkProductResult = mysqli_query(
        $conn,
        $checkProductSql
    );

    if (!$checkProductResult) {
        die("Database Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($checkProductResult) == 0) {
        die("Product not found.");
    }

    /* CHECK CART */
    $checkCartSql = "
        SELECT *
        FROM mycart
        WHERE bid = '$userid'
        AND pid = '$pid'
    ";

    $checkCartResult = mysqli_query(
        $conn,
        $checkCartSql
    );

    if (!$checkCartResult) {
        die("Cart Error: " . mysqli_error($conn));
    }

    /* UPDATE QUANTITY */
    if (mysqli_num_rows($checkCartResult) > 0) {

        $updateCartSql = "
            UPDATE mycart
            SET quantity = quantity + 1
            WHERE bid = '$userid'
            AND pid = '$pid'
        ";

        if (!mysqli_query($conn, $updateCartSql)) {
            die("Unable to update cart: " . mysqli_error($conn));
        }

    } else {

        /* INSERT NEW PRODUCT */
        $insertCartSql = "
            INSERT INTO mycart
            (
                bid,
                pid,
                quantity
            )
            VALUES
            (
                '$userid',
                '$pid',
                1
            )
        ";

        if (!mysqli_query($conn, $insertCartSql)) {
            die("Unable to add product: " . mysqli_error($conn));
        }
    }

    /* SAVE NOTIFICATION */
    $_SESSION['cart_message'] =
        "Product added to cart successfully!";

    /* RETURN TO PRODUCT PAGE */
    header("Location: productMenu.php?mode=all");
    exit();
}


/* =====================================================
   DELETE PRODUCT
===================================================== */

if (
    isset($_GET['delete']) &&
    $_GET['delete'] == 1 &&
    isset($_GET['pid'])
) {
    if (!$isFarmer) {
        header("Location: productMenu.php?mode=all");
        exit();
    }

    $deletePid = intval($_GET['pid']);

    $getProductSql = "
        SELECT pimage
        FROM fproduct
        WHERE pid = '$deletePid'
        AND fid = '$userid'
    ";

    $getProductResult = mysqli_query(
        $conn,
        $getProductSql
    );

    if (
        $getProductResult &&
        mysqli_num_rows($getProductResult) > 0
    ) {
        $productData = mysqli_fetch_assoc(
            $getProductResult
        );

        $productImage = $productData['pimage'];

        /* DELETE FROM CART */
        $deleteCartSql = "
            DELETE FROM mycart
            WHERE pid = '$deletePid'
        ";

        mysqli_query($conn, $deleteCartSql);

        /* DELETE PRODUCT */
        $deleteSql = "
            DELETE FROM fproduct
            WHERE pid = '$deletePid'
            AND fid = '$userid'
        ";

        $deleteResult = mysqli_query(
            $conn,
            $deleteSql
        );

        /* DELETE IMAGE */
        if ($deleteResult) {

            if (
                !empty($productImage) &&
                $productImage != 'blank.png'
            ) {
                $imageFile =
                    "images/productImages/" .
                    $productImage;

                if (file_exists($imageFile)) {
                    @unlink($imageFile);
                }
            }
        }
    }

    header("Location: productMenu.php?mode=all");
    exit();
}


/* =====================================================
   BUILD WHERE CONDITION
===================================================== */

$where = "1=1";

if ($isFarmer) {
    $where .= "
        AND fproduct.fid = '$userid'
    ";
}

/* CATEGORY FILTER */
if ($mode == 'search') {

    if ($n == 1) {
        $where .= "
            AND fproduct.pcat = 'Fruit'
        ";
    }

    elseif ($n == 2) {
        $where .= "
            AND fproduct.pcat = 'Vegetable'
        ";
    }

    elseif ($n == 3) {
        $where .= "
            AND fproduct.pcat = 'Grains'
        ";
    }
}


/* =====================================================
   GET PRODUCTS
===================================================== */

$sql = "
    SELECT
        fproduct.pid,
        fproduct.fid,
        fproduct.product,
        fproduct.pcat,
        fproduct.pinfo,
        fproduct.price,
        fproduct.pimage,
        farmer.fname AS farmer_name

    FROM fproduct

    LEFT JOIN farmer
        ON fproduct.fid = farmer.fid

    WHERE $where

    ORDER BY fproduct.pid DESC
";

$result = mysqli_query(
    $conn,
    $sql
);

if (!$result) {
    die(
        "Product Database Error: " .
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

    <title>AgroCulture - Digital Market</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: auto;
        }

        body {
            background: #f4f8f4;
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
            overflow-x: hidden;
        }

        .market-page {
            width: 94%;
            max-width: 1350px;
            margin: 0 auto;
            padding-top: 105px;
            padding-bottom: 60px;
        }

        .page-title {
            text-align: center;
            color: #238b45;
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 45px;
        }

        .category-box {
            width: 82%;
            max-width: 1050px;
            margin: 0 auto 40px auto;
            background: white;
            padding: 30px 40px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            border: 1px solid #eeeeee;
        }

        .category-title {
            text-align: center;
            font-size: 30px;
            color: #333;
            margin-bottom: 25px;
        }

        .category-form {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .category-form select {
            flex: 1;
            height: 58px;
            padding: 0 18px;
            border: 1px solid #cccccc;
            border-radius: 10px;
            background: white;
            font-size: 18px;
            color: #333;
        }

        .go-button {
            height: 58px;
            min-width: 145px;
            padding: 0 28px;
            border: none;
            border-radius: 10px;
            background: #238b45;
            color: white;
            font-size: 19px;
            font-weight: bold;
            cursor: pointer;
        }

        .go-button:hover {
            background: #1c7138;
        }

        .product-grid {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 30px;
            align-items: start;
        }

        .product-card {
            width: 100%;
            min-width: 0;
            background: white;
            border-radius: 18px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            border: 1px solid #eeeeee;
            box-shadow: 0 7px 22px rgba(0, 0, 0, 0.08);
        }

        .product-image {
            width: 100%;
            height: 235px;
            object-fit: cover;
            display: block;
            border-radius: 13px;
            margin-bottom: 15px;
        }

        .product-name {
            text-align: center;
            font-size: 26px;
            font-weight: 700;
            color: #222;
            text-transform: capitalize;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .product-category {
            text-align: center;
            color: #555;
            font-size: 17px;
            margin-bottom: 8px;
        }

        .product-price {
            text-align: center;
            color: #238b45;
            font-size: 25px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .product-info {
            margin-top: 5px;
            margin-bottom: 12px;
            padding: 12px;
            background: #f8f8f8;
            border-radius: 8px;
            color: #555;
            font-size: 15px;
            line-height: 1.5;
            text-align: left;
            min-height: 70px;
        }

        .product-info strong {
            display: block;
            color: #238b45;
            margin-bottom: 5px;
        }

        .product-info p {
            margin: 0;
            word-wrap: break-word;
        }

        .farmer-box {
            width: 100%;
            min-height: 46px;
            background: #f3f8f4;
            border-left: 4px solid #238b45;
            border-radius: 8px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
        }

        .farmer-box strong {
            color: #238b45;
            margin: 0 5px;
        }

        .button-area {
            margin-top: 8px;
            padding-top: 8px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .cart-button,
        .buy-button,
        .delete-button {
            flex: 1;
            height: 46px;
            padding: 0 15px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transform: none !important;
            transition: none !important;
        }

        .cart-button {
            background: #238b45;
            color: white;
        }

        .cart-button:hover {
            background: #1c7138;
            color: white;
            transform: none !important;
        }

        .buy-button {
            background: #2878c8;
            color: white;
        }

        .buy-button:hover {
            background: #1f62a5;
            color: white;
            transform: none !important;
        }

        .delete-button {
            width: 100%;
            background: white;
            color: #d62828;
            border: 1.5px solid #ff6b6b;
        }

        .delete-button:hover {
            background: #d62828;
            color: white;
            transform: none !important;
        }

        .no-products {
            grid-column: 1 / -1;
            background: white;
            border-radius: 15px;
            padding: 60px 30px;
            text-align: center;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .no-products h3 {
            color: #555;
            font-size: 25px;
            margin-bottom: 12px;
        }

        .no-products p {
            color: #777;
            font-size: 17px;
        }

        /* CART NOTIFICATION */

        .cart-notification {
            position: fixed;
            top: 85px;
            right: 25px;
            z-index: 9999;
            background: #238b45;
            color: white;
            padding: 16px 25px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.18);
            animation: notificationShow 0.3s ease;
        }

        @keyframes notificationShow {

            from {
                opacity: 0;
                transform: translateY(-15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }

        @media (max-width: 1000px) {

            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .category-box {
                width: 95%;
            }

        }

        @media (max-width: 650px) {

            .market-page {
                width: 92%;
                padding-top: 75px;
            }

            .page-title {
                font-size: 32px;
            }

            .category-box {
                width: 100%;
                padding: 24px 20px;
            }

            .category-form {
                flex-direction: column;
                gap: 12px;
            }

            .category-form select,
            .go-button {
                width: 100%;
            }

            .product-grid {
                grid-template-columns: 1fr;
                gap: 22px;
            }

            .product-card {
                min-height: auto;
            }

            .button-area {
                flex-direction: column;
            }

            .cart-button,
            .buy-button,
            .delete-button {
                width: 100%;
                flex: none;
            }

            .cart-notification {
                top: 75px;
                left: 15px;
                right: 15px;
                text-align: center;
                font-size: 14px;
            }

        }

    </style>

</head>

<body>

<?php require 'menu.php'; ?>

<?php

/* SHOW CART NOTIFICATION */

if (isset($_SESSION['cart_message'])) {

    echo '
    <div class="cart-notification">
        🛒 ' .
        htmlspecialchars($_SESSION['cart_message']) .
        '
    </div>
    ';

    unset($_SESSION['cart_message']);
}

?>

<div class="market-page">

    <h1 class="page-title">
        Digital Market
    </h1>

    <?php if ($mode == 'search') { ?>

        <div class="category-box">

            <div class="category-title">
                Search Products by Category
            </div>

            <form
                method="GET"
                action="productMenu.php"
                class="category-form"
            >

                <input
                    type="hidden"
                    name="mode"
                    value="search"
                >

                <select name="n">

                    <option
                        value="0"
                        <?php
                        if ($n == 0) {
                            echo "selected";
                        }
                        ?>
                    >
                        List All
                    </option>

                    <option
                        value="1"
                        <?php
                        if ($n == 1) {
                            echo "selected";
                        }
                        ?>
                    >
                        Fruit
                    </option>

                    <option
                        value="2"
                        <?php
                        if ($n == 2) {
                            echo "selected";
                        }
                        ?>
                    >
                        Vegetable
                    </option>

                    <option
                        value="3"
                        <?php
                        if ($n == 3) {
                            echo "selected";
                        }
                        ?>
                    >
                        Grains
                    </option>

                </select>

                <button
                    type="submit"
                    class="go-button"
                >
                    Go!
                </button>

            </form>

        </div>

    <?php } ?>

    <div class="product-grid">

        <?php if (mysqli_num_rows($result) == 0) { ?>

            <div class="no-products">

                <h3>No products found.</h3>

                <p>Try another category.</p>

            </div>

        <?php } ?>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <?php

            $pid = intval($row['pid']);

            $productName = $row['product'];

            $productCategory = $row['pcat'];

            $productInfo = $row['pinfo'];

            $price = $row['price'];

            $image = $row['pimage'];

            $farmerName = $row['farmer_name'];

            $imagePath =
                "images/productImages/" . $image;

            ?>

            <div class="product-card">

                <img
                    src="<?php echo htmlspecialchars($imagePath); ?>"
                    class="product-image"
                    alt="<?php echo htmlspecialchars($productName); ?>"
                >

                <div class="product-name">
                    <?php echo htmlspecialchars($productName); ?>
                </div>

                <div class="product-category">

                    <strong>Category:</strong>

                    <?php echo htmlspecialchars($productCategory); ?>

                </div>

                <div class="product-price">

                    ₹<?php echo number_format($price, 2); ?>

                </div>

                <div class="product-info">

                    <strong>Product Information:</strong>

                    <p>

                        <?php

                        if (!empty($productInfo)) {

                            echo nl2br(
                                htmlspecialchars($productInfo)
                            );

                        } else {

                            echo "No product information available.";

                        }

                        ?>

                    </p>

                </div>

                <?php if (!$isFarmer) { ?>

                    <div class="farmer-box">

                        <span>👨‍🌾</span>

                        <strong>Farmer:</strong>

                        <?php

                        if (!empty($farmerName)) {

                            echo htmlspecialchars($farmerName);

                        } else {

                            echo "Unknown";

                        }

                        ?>

                    </div>

                    <div class="button-area">

                        <a
                            href="productMenu.php?flag=1&pid=<?php echo $pid; ?>&mode=<?php echo urlencode($mode); ?>&n=<?php echo $n; ?>"
                            class="cart-button"
                        >
                            🛒 Add to Cart
                        </a>

                        <a
                            href="buyNow.php?pid=<?php echo $pid; ?>"
                            class="buy-button"
                        >
                            💳 Buy Now
                        </a>

                    </div>

                <?php } else { ?>

                    <div class="button-area">

                        <a
                            href="productMenu.php?delete=1&pid=<?php echo $pid; ?>"
                            class="delete-button"
                        >
                            🗑️ Delete Product
                        </a>

                    </div>

                <?php } ?>

            </div>

        <?php } ?>

    </div>

</div>

</body>
</html>