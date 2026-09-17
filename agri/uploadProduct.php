<?php
session_start();
require 'db.php';


/* =====================================================
   CHECK LOGIN
===================================================== */

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1)
{
    $_SESSION['message'] = "You must login before uploading a product!";
    header("Location: Login/error.php");
    exit();
}


/* =====================================================
   UPLOAD PRODUCT
===================================================== */

if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    $productType  = dataFilter($_POST['type']);
    $productName  = dataFilter($_POST['pname']);
    $productInfo  = $_POST['pinfo'];
    $productPrice = dataFilter($_POST['price']);

    $fid = $_SESSION['id'];


    /* -----------------------------------------------
       CHECK IMAGE
    ------------------------------------------------ */

    if (!isset($_FILES['productPic']) ||
        $_FILES['productPic']['error'] != 0)
    {
        $_SESSION['message'] =
            "Please select a product image.";

        header("Location: Login/error.php");
        exit();
    }


    /* -----------------------------------------------
       IMAGE DETAILS
    ------------------------------------------------ */

    $pic = $_FILES['productPic'];

    $picName    = $pic['name'];
    $picTmpName = $pic['tmp_name'];
    $picError   = $pic['error'];


    $picExt = explode(".", $picName);

    $picActualExt =
        strtolower(end($picExt));


    $allowed =
        array("jpg", "jpeg", "png");


    /* -----------------------------------------------
       CHECK IMAGE EXTENSION
    ------------------------------------------------ */

    if (!in_array($picActualExt, $allowed))
    {
        $_SESSION['message'] =
            "Only JPG, JPEG and PNG images are allowed.";

        header("Location: Login/error.php");
        exit();
    }


    /* -----------------------------------------------
       CHECK IMAGE ERROR
    ------------------------------------------------ */

    if ($picError !== 0)
    {
        $_SESSION['message'] =
            "There was an error uploading the image.";

        header("Location: Login/error.php");
        exit();
    }


    /* =================================================
       INSERT PRODUCT
    ================================================= */

    $sql = "INSERT INTO fproduct
            (fid, product, pcat, pinfo, price)
            VALUES
            ('$fid',
             '$productName',
             '$productType',
             '$productInfo',
             '$productPrice')";


    $result = mysqli_query($conn, $sql);


    if (!$result)
    {
        $_SESSION['message'] =
            "Unable to upload product!";

        header("Location: Login/error.php");
        exit();
    }


    /* =================================================
       PRODUCT IMAGE NAME
    ================================================= */

    $picNameNew =
        $productName .
        $fid .
        "." .
        $picActualExt;


    $picDestination =
        "images/productImages/" .
        $picNameNew;


    /* =================================================
       MOVE IMAGE
    ================================================= */

    if (!move_uploaded_file(
            $picTmpName,
            $picDestination
        ))
    {
        $_SESSION['message'] =
            "Product saved, but image upload failed.";

        header("Location: Login/error.php");
        exit();
    }


    /* =================================================
       UPDATE PRODUCT IMAGE
    ================================================= */

    $sql = "UPDATE fproduct
            SET
                picStatus = 1,
                pimage = '$picNameNew'
            WHERE
                product = '$productName'
            AND
                fid = '$fid'";


    $result = mysqli_query($conn, $sql);


    if ($result)
    {
        $_SESSION['message'] =
            "Product uploaded successfully!";

        header("Location: market.php");
        exit();
    }
    else
    {
        $_SESSION['message'] =
            "Product uploaded but image information could not be saved.";

        header("Location: Login/error.php");
        exit();
    }
}


/* =====================================================
   DATA FILTER
===================================================== */

function dataFilter($data)
{
    $data = trim($data);

    $data = stripslashes($data);

    $data = htmlspecialchars($data);

    return $data;
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
        AgroCulture - Upload Product
    </title>


    <style>

        /* =================================================
           RESET
        ================================================= */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        html,
        body {

            width: 100%;

            min-height: 100%;

        }


        body {

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background:

                linear-gradient(
                    rgba(45, 90, 55, 0.40),
                    rgba(45, 90, 55, 0.40)
                ),

                url("images/banner2.jpg");

            background-size: cover;

            background-position: center;

            background-attachment: fixed;

            background-repeat: no-repeat;

            overflow-x: hidden;

        }


        /* =================================================
           NAVIGATION BAR
        ================================================= */

        .navbar {

            width: 100%;

            height: 80px;

            background:
                rgba(25, 30, 30, 0.97);

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding:
                0 4%;

            position: relative;

            z-index: 1000;

        }


        /* LOGO */

        .logo {

            color: white;

            font-size: 32px;

            font-weight: 400;

            text-decoration: none;

            white-space: nowrap;

        }


        .logo:hover {

            color: white;

            text-decoration: none;

        }


        /* NAVIGATION */

        .nav-links {

            display: flex;

            align-items: center;

            gap: 30px;

            list-style: none;

        }


        .nav-links a {

            color: white;

            text-decoration: none;

            font-size: 16px;

            white-space: nowrap;

            transition: 0.2s;

        }


        .nav-links a:hover {

            color: #a9d8ad;

            text-decoration: none;

        }


        /* =================================================
           MAIN PAGE
        ================================================= */

        .page {

            width: 100%;

            min-height:
                calc(100vh - 80px);

            padding:
                40px 5% 60px 5%;

        }


        .content {

            width: 100%;

            max-width: 1450px;

            margin: auto;

        }


        /* =================================================
           HEADING
        ================================================= */

        .page-title {

            text-align: center;

            color: white;

            font-size: 42px;

            font-weight: bold;

            margin-bottom: 35px;

            text-shadow:
                1px 2px 5px
                rgba(0,0,0,0.5);

        }


        /* =================================================
           FORM
        ================================================= */

        .product-form {

            width: 100%;

        }


        /* =================================================
           FILE UPLOAD
        ================================================= */

        .image-upload {

            width: 100%;

            display: flex;

            justify-content: center;

            align-items: center;

            margin-bottom: 30px;

        }


        .image-upload input[type="file"] {

            color: white;

            font-size: 17px;

        }


        /* =================================================
           CATEGORY + PRODUCT NAME
        ================================================= */

        .row-two {

            width: 100%;

            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 25px;

            margin-bottom: 25px;

        }


        /* =================================================
           INPUTS
        ================================================= */

        .input-box {

            width: 100%;

            height: 60px;

            background: white;

            border: none;

            border-radius: 7px;

            padding:
                0 20px;

            font-size: 19px;

            color: #333;

            outline: none;

        }


        .input-box::placeholder {

            color: #777;

        }


        .input-box:focus {

            outline:
                2px solid #4caf50;

        }


        /* =================================================
           PRODUCT INFORMATION
        ================================================= */

        .product-info {

            width: 100%;

            min-height: 280px;

            resize: vertical;

            display: block;

            background: white;

            border: none;

            border-radius: 7px;

            padding: 20px;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            font-size: 18px;

            color: #333;

            outline: none;

            margin-bottom: 25px;

        }


        .product-info::placeholder {

            color: #777;

        }


        .product-info:focus {

            outline:
                2px solid #4caf50;

        }


        /* =================================================
           PRICE + BUTTON
        ================================================= */

        .row-bottom {

            width: 100%;

            display: grid;

            grid-template-columns:
                1fr 220px;

            gap: 25px;

        }


        /* =================================================
           SUBMIT BUTTON
        ================================================= */

        .submit-btn {

            width: 100%;

            height: 60px;

            border: none;

            border-radius: 7px;

            background:
                #4caf50;

            color: white;

            font-size: 19px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.2s;

        }


        .submit-btn:hover {

            background:
                #388e3c;

        }


        /* =================================================
           TABLET
        ================================================= */

        @media screen
        and (max-width: 1000px)
        {

            .navbar {

                padding:
                    0 25px;

            }


            .nav-links {

                gap: 18px;

            }


            .nav-links a {

                font-size: 14px;

            }


            .page-title {

                font-size: 36px;

            }

        }


        /* =================================================
           MOBILE
        ================================================= */

        @media screen
        and (max-width: 750px)
        {

            .navbar {

                height: auto;

                min-height: 80px;

                flex-direction: column;

                justify-content: center;

                gap: 15px;

                padding:
                    15px;

            }


            .nav-links {

                flex-wrap: wrap;

                justify-content: center;

            }


            .page {

                padding:
                    30px 20px 50px 20px;

            }


            .page-title {

                font-size: 30px;

            }


            .row-two {

                grid-template-columns:
                    1fr;

            }


            .row-bottom {

                grid-template-columns:
                    1fr;

            }


            .product-info {

                min-height: 220px;

            }

        }


        /* =================================================
           SMALL MOBILE
        ================================================= */

        @media screen
        and (max-width: 450px)
        {

            .logo {

                font-size: 27px;

            }


            .nav-links {

                gap: 12px;

            }


            .nav-links a {

                font-size: 12px;

            }


            .page-title {

                font-size: 25px;

            }


            .input-box {

                font-size: 16px;

            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     NAVIGATION
===================================================== -->

<nav class="navbar">


    <a
        href="index.php"
        class="logo"
    >
        AgroCulture
    </a>


    <ul class="nav-links">

        <li>
            <a href="index.php">
                Home
            </a>
        </li>


        <li>
            <a href="myCart.php">
                MyCart
            </a>
        </li>


        <li>
            <a href="myOrders.php">
                My Orders
            </a>
        </li>


        <li>
            <a href="profileView.php">
                My Profile
            </a>
        </li>


        <li>
            <a href="market.php">
                Digital-Market
            </a>
        </li>


        <li>
            <a href="blogView.php">
                BLOG
            </a>
        </li>

    </ul>

</nav>


<!-- =====================================================
     MAIN CONTENT
===================================================== -->

<main class="page">


    <div class="content">


        <h1 class="page-title">

            Enter the Product Information

        </h1>


        <form
            class="product-form"
            method="POST"
            action="uploadProduct.php"
            enctype="multipart/form-data"
        >


            <!-- IMAGE -->

            <div class="image-upload">

                <input
                    type="file"
                    name="productPic"
                    accept=".jpg,.jpeg,.png"
                    required
                >

            </div>


            <!-- CATEGORY + PRODUCT NAME -->

            <div class="row-two">


                <select
                    name="type"
                    class="input-box"
                    required
                >

                    <option value="">
                        - Category -
                    </option>

                    <option value="Fruit">
                        Fruit
                    </option>

                    <option value="Vegetable">
                        Vegetable
                    </option>

                    <option value="Grains">
                        Grains
                    </option>

                </select>


                <input
                    type="text"
                    name="pname"
                    class="input-box"
                    placeholder="Product Name"
                    required
                >

            </div>


            <!-- PRODUCT INFORMATION -->

            <textarea
                name="pinfo"
                class="product-info"
                placeholder="Enter product information..."
                required
            ></textarea>


            <!-- PRICE + SUBMIT -->

            <div class="row-bottom">


                <input
                    type="number"
                    name="price"
                    class="input-box"
                    placeholder="Price"
                    min="1"
                    required
                >


                <button
                    type="submit"
                    class="submit-btn"
                >

                    Submit

                </button>


            </div>


        </form>


    </div>


</main>


</body>

</html>