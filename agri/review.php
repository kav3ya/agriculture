<?php
session_start();
require 'db.php';

if (!isset($_GET['pid'])) {
    die("Product ID not found.");
}

$pid = intval($_GET['pid']);

/* Get product details */
$sql = "SELECT * FROM fproduct WHERE pid = '$pid'";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Product not found.");
}

$row = mysqli_fetch_assoc($result);

/* Get farmer details */
$fid = $row['fid'];

$sqlFarmer = "SELECT * FROM farmer WHERE fid = '$fid'";
$resultFarmer = mysqli_query($conn, $sqlFarmer);

$frow = mysqli_fetch_assoc($resultFarmer);

$picDestination = "images/productImages/" . $row['pimage'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AgroCulture - Product</title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <script src="bootstrap/js/bootstrap.min.js"></script>

    <style>

        /* ==============================
           GENERAL PAGE
        ============================== */

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background: #f4f7f5;
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
        }

        /* ==============================
           MAIN CONTAINER
        ============================== */

        .product-page {
            width: 92%;
            max-width: 1200px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.10);
        }

        /* ==============================
           PRODUCT TOP SECTION
        ============================== */

        .product-main {
            display: flex;
            align-items: center;
            gap: 50px;
            padding-bottom: 40px;
            border-bottom: 1px solid #ddd;
        }

        .product-image-box {
            width: 45%;
            text-align: center;
        }

        .product-image {
            width: 100%;
            max-width: 500px;
            height: 400px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .product-details {
            width: 55%;
            padding: 20px;
        }

        .product-name {
            font-size: 48px;
            font-weight: bold;
            color: #2e7d32;
            margin-bottom: 25px;
        }

        .product-owner {
            font-size: 25px;
            margin-bottom: 20px;
        }

        .product-price {
            font-size: 30px;
            font-weight: bold;
            color: #e67e22;
            margin-bottom: 25px;
        }

        .product-category {
            font-size: 20px;
            color: #666;
            margin-bottom: 25px;
        }

        /* ==============================
           BUTTONS
        ============================== */

        .product-buttons {
            display: flex;
            gap: 20px;
            margin-top: 25px;
        }

        .product-buttons a {
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 6px;
            font-size: 18px;
            font-weight: bold;
            color: white;
            display: inline-block;
        }

        .cart-btn {
            background: #28a745;
        }

        .cart-btn:hover {
            background: #218838;
            color: white;
        }

        .buy-btn {
            background: #ff9800;
        }

        .buy-btn:hover {
            background: #e68900;
            color: white;
        }

        /* ==============================
           PRODUCT INFORMATION
        ============================== */

        .product-information {
            margin-top: 40px;
        }

        .section-title {
            font-size: 34px;
            font-weight: bold;
            color: #2e7d32;
            margin-bottom: 20px;
            border-left: 6px solid #4caf50;
            padding-left: 15px;
        }

        .product-info-box {
            background: #f8faf8;
            border-left: 5px solid #4caf50;
            padding: 25px;
            border-radius: 8px;
            font-size: 20px;
            line-height: 1.7;
            min-height: 100px;
        }

        /* ==============================
           REVIEWS
        ============================== */

        .reviews-section {
            margin-top: 50px;
            padding-top: 35px;
            border-top: 1px solid #ddd;
        }

        .review-box {
            background: #f8faf8;
            border-left: 5px solid #4caf50;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .review-comment {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .review-rating {
            font-size: 17px;
            font-weight: bold;
            color: #e67e22;
        }

        .review-user {
            margin-top: 10px;
            color: #666;
            font-style: italic;
        }

        /* ==============================
           REVIEW FORM
        ============================== */

        .review-form {
            margin-top: 40px;
            background: #f8faf8;
            padding: 25px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .review-form textarea {
            width: 100%;
            min-height: 120px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            resize: vertical;
            font-size: 17px;
        }

        .review-form input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 17px;
        }

        .review-submit {
            margin-top: 20px;
            background: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 17px;
        }

        .review-submit:hover {
            background: #218838;
        }

        /* ==============================
           RESPONSIVE DESIGN
        ============================== */

        @media screen and (max-width: 768px) {

            .product-page {
                width: 95%;
                padding: 20px;
            }

            .product-main {
                flex-direction: column;
                gap: 20px;
            }

            .product-image-box,
            .product-details {
                width: 100%;
            }

            .product-image {
                height: 300px;
            }

            .product-name {
                font-size: 36px;
            }

            .product-owner {
                font-size: 21px;
            }

            .product-price {
                font-size: 25px;
            }

            .product-buttons {
                flex-direction: column;
            }

            .product-buttons a {
                text-align: center;
                width: 100%;
            }
        }

    </style>

</head>

<body>

<?php require 'menu.php'; ?>


<div class="product-page">

    <!-- ==============================
         PRODUCT DETAILS
    ============================== -->

    <div class="product-main">

        <!-- PRODUCT IMAGE -->

        <div class="product-image-box">

            <img
                src="<?php echo htmlspecialchars($picDestination); ?>"
                class="product-image"
                alt="<?php echo htmlspecialchars($row['product']); ?>"
            >

        </div>


        <!-- PRODUCT INFORMATION -->

        <div class="product-details">

            <div class="product-name">
                <?php echo htmlspecialchars($row['product']); ?>
            </div>

            <div class="product-owner">
                <strong>Product Owner:</strong>
                <?php echo htmlspecialchars($frow['fname']); ?>
            </div>

            <div class="product-category">
                <strong>Category:</strong>
                <?php echo htmlspecialchars($row['pcat']); ?>
            </div>

            <div class="product-price">
                Price: ₹<?php echo htmlspecialchars($row['price']); ?>
            </div>


            <!-- BUTTONS -->

            <div class="product-buttons">

                <a
                    href="myCart.php?flag=1&pid=<?php echo $pid; ?>"
                    class="cart-btn"
                >
                    🛒 Add to Cart
                </a>

                <a
                    href="buyNow.php?pid=<?php echo $pid; ?>"
                    class="buy-btn"
                >
                    Buy Now
                </a>

            </div>

        </div>

    </div>


    <!-- ==============================
         PRODUCT INFORMATION
    ============================== -->

    <div class="product-information">

        <div class="section-title">
            Product Information
        </div>

        <div class="product-info-box">

            <?php
            if (!empty($row['pinfo'])) {
                echo $row['pinfo'];
            } else {
                echo "No product information available.";
            }
            ?>

        </div>

    </div>


    <!-- ==============================
         PRODUCT REVIEWS
    ============================== -->

    <div class="reviews-section">

        <div class="section-title">
            Product Reviews
        </div>


        <?php

        $sqlReview = "SELECT * FROM review WHERE pid='$pid'";

        $resultReview = mysqli_query($conn, $sqlReview);

        if ($resultReview && mysqli_num_rows($resultReview) > 0) {

            while ($row1 = mysqli_fetch_assoc($resultReview)) {

        ?>

                <div class="review-box">

                    <div class="review-comment">
                        <?php echo htmlspecialchars($row1['comment']); ?>
                    </div>

                    <div class="review-rating">
                        Rating:
                        <?php echo htmlspecialchars($row1['rating']); ?>
                        out of 10
                    </div>

                    <div class="review-user">
                        From:
                        <?php echo htmlspecialchars($row1['name']); ?>
                    </div>

                </div>

        <?php

            }

        } else {

        ?>

            <div class="review-box">
                No reviews available for this product yet.
            </div>

        <?php

        }

        ?>


        <!-- REVIEW FORM -->

        <div class="review-form">

            <h3>Rate this product</h3>

            <form
                method="POST"
                action="reviewInput.php?pid=<?php echo $pid; ?>"
            >

                <div style="margin-bottom:20px;">

                    <textarea
                        name="comment"
                        placeholder="Write a review"
                        required
                    ></textarea>

                </div>


                <div>

                    <label>
                        <strong>Rating:</strong>
                    </label>

                    <input
                        type="number"
                        min="0"
                        max="10"
                        name="rating"
                        value="0"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="review-submit"
                >
                    Submit Review
                </button>

            </form>

        </div>

    </div>

</div>


</body>

</html>