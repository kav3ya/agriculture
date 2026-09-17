<?php

session_start();
require 'db.php';


/* =====================================================
   CHECK LOGIN
===================================================== */

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {

    $_SESSION['message'] = "You need to login first.";

    header("Location: Login.php");
    exit();
}


/* =====================================================
   CHECK FARMER
===================================================== */

if (
    !isset($_SESSION['Category']) ||
    intval($_SESSION['Category']) != 1
) {

    $_SESSION['message'] =
        "Only farmers can edit products.";

    header("Location: productMenu.php");
    exit();
}


/* =====================================================
   GET FARMER ID
===================================================== */

$fid = intval($_SESSION['id']);


/* =====================================================
   CHECK PRODUCT ID
===================================================== */

if (!isset($_GET['pid']) && !isset($_POST['pid'])) {

    $_SESSION['message'] =
        "Product ID is missing.";

    header("Location: productMenu.php");
    exit();
}


$pid = isset($_POST['pid'])
    ? intval($_POST['pid'])
    : intval($_GET['pid']);


if ($pid <= 0) {

    $_SESSION['message'] =
        "Invalid product ID.";

    header("Location: productMenu.php");
    exit();
}


/* =====================================================
   GET ONLY FARMER'S OWN PRODUCT
===================================================== */

$sql = "
    SELECT *
    FROM fproduct
    WHERE pid = '$pid'
    AND fid = '$fid'
";

$result = mysqli_query($conn, $sql);


if (!$result) {

    die(
        "Database error: " .
        mysqli_error($conn)
    );
}


if (mysqli_num_rows($result) == 0) {

    $_SESSION['message'] =
        "You are not allowed to edit this product.";

    header("Location: productMenu.php");
    exit();
}


$product = mysqli_fetch_assoc($result);


/* =====================================================
   UPDATE PRODUCT
===================================================== */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    /* -------------------------------------------------
       GET FORM DATA
    ------------------------------------------------- */

    $productName =
        trim($_POST['pname']);

    $productType =
        trim($_POST['type']);

    $productInfo =
        trim($_POST['pinfo']);

    $productPrice =
        trim($_POST['price']);


    /* -------------------------------------------------
       VALIDATION
    ------------------------------------------------- */

    if (
        $productName == "" ||
        $productType == "" ||
        $productInfo == "" ||
        $productPrice == ""
    ) {

        $_SESSION['message'] =
            "Please fill all product details.";

        header(
            "Location: editProduct.php?pid=" .
            $pid
        );

        exit();
    }


    if (!is_numeric($productPrice) || $productPrice <= 0) {

        $_SESSION['message'] =
            "Please enter a valid price.";

        header(
            "Location: editProduct.php?pid=" .
            $pid
        );

        exit();
    }


    /* -------------------------------------------------
       ESCAPE DATA
    ------------------------------------------------- */

    $productName =
        mysqli_real_escape_string(
            $conn,
            $productName
        );

    $productType =
        mysqli_real_escape_string(
            $conn,
            $productType
        );

    $productInfo =
        mysqli_real_escape_string(
            $conn,
            $productInfo
        );

    $productPrice =
        mysqli_real_escape_string(
            $conn,
            $productPrice
        );


    /* =================================================
       UPDATE BASIC PRODUCT DETAILS
    ================================================= */

    $updateSql = "
        UPDATE fproduct
        SET
            product = '$productName',
            pcat = '$productType',
            pinfo = '$productInfo',
            price = '$productPrice'
        WHERE
            pid = '$pid'
        AND
            fid = '$fid'
    ";


    $updateResult =
        mysqli_query(
            $conn,
            $updateSql
        );


    if (!$updateResult) {

        $_SESSION['message'] =
            "Unable to update product: " .
            mysqli_error($conn);

        header(
            "Location: editProduct.php?pid=" .
            $pid
        );

        exit();
    }


    /* =================================================
       CHECK NEW IMAGE
    ================================================= */

    if (
        isset($_FILES['productPic']) &&
        $_FILES['productPic']['error'] != UPLOAD_ERR_NO_FILE
    ) {


        /* ---------------------------------------------
           CHECK UPLOAD ERROR
        --------------------------------------------- */

        if (
            $_FILES['productPic']['error'] !=
            UPLOAD_ERR_OK
        ) {

            $_SESSION['message'] =
                "There was an error uploading the image.";

            header(
                "Location: editProduct.php?pid=" .
                $pid
            );

            exit();
        }


        /* ---------------------------------------------
           IMAGE DETAILS
        --------------------------------------------- */

        $picName =
            $_FILES['productPic']['name'];

        $picTmpName =
            $_FILES['productPic']['tmp_name'];


        $extension =
            strtolower(
                pathinfo(
                    $picName,
                    PATHINFO_EXTENSION
                )
            );


        $allowedExtensions = array(
            "jpg",
            "jpeg",
            "png"
        );


        /* ---------------------------------------------
           CHECK EXTENSION
        --------------------------------------------- */

        if (
            !in_array(
                $extension,
                $allowedExtensions
            )
        ) {

            $_SESSION['message'] =
                "Only JPG, JPEG and PNG images are allowed.";

            header(
                "Location: editProduct.php?pid=" .
                $pid
            );

            exit();
        }


        /* ---------------------------------------------
           CREATE NEW IMAGE NAME
        --------------------------------------------- */

        $newImageName =
            $productName .
            $fid .
            "." .
            $extension;


        $destination =
            "images/productImages/" .
            $newImageName;


        /* ---------------------------------------------
           MOVE NEW IMAGE
        --------------------------------------------- */

        if (
            move_uploaded_file(
                $picTmpName,
                $destination
            )
        ) {


            /* -----------------------------------------
               UPDATE IMAGE NAME
            ----------------------------------------- */

            $imageSql = "
                UPDATE fproduct
                SET
                    pimage = '$newImageName',
                    picStatus = 1
                WHERE
                    pid = '$pid'
                AND
                    fid = '$fid'
            ";


            $imageResult =
                mysqli_query(
                    $conn,
                    $imageSql
                );


            if (!$imageResult) {

                $_SESSION['message'] =
                    "Product updated but image information could not be saved.";

                header(
                    "Location: editProduct.php?pid=" .
                    $pid
                );

                exit();
            }
        }
        else {

            $_SESSION['message'] =
                "Product updated but new image could not be uploaded.";

            header(
                "Location: editProduct.php?pid=" .
                $pid
            );

            exit();
        }
    }


    /* =================================================
       SUCCESS
    ================================================= */

    $_SESSION['message'] =
        "Product updated successfully!";


    header("Location: productMenu.php");

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

    <title>
        AgroCulture - Edit Product
    </title>


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

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background:
                #f4f8f4;

        }


        /* =================================================
           PAGE
        ================================================= */

        .page {

            width: 95%;

            max-width: 900px;

            margin:
                50px auto;

            padding-bottom: 50px;

        }


        /* =================================================
           TITLE
        ================================================= */

        .title {

            text-align: center;

            color:
                #238b45;

            font-size:
                36px;

            font-weight:
                bold;

            margin-bottom:
                30px;

        }


        /* =================================================
           FORM CARD
        ================================================= */

        .form-card {

            background:
                white;

            padding:
                35px;

            border-radius:
                12px;

            box-shadow:
                0 5px 20px
                rgba(0,0,0,0.10);

        }


        /* =================================================
           CURRENT IMAGE
        ================================================= */

        .current-image-area {

            text-align:
                center;

            margin-bottom:
                30px;

        }


        .current-image {

            width:
                250px;

            height:
                200px;

            object-fit:
                cover;

            border-radius:
                10px;

            border:
                1px solid #ddd;

        }


        .current-image-text {

            margin-top:
                10px;

            color:
                #777;

            font-size:
                14px;

        }


        /* =================================================
           LABEL
        ================================================= */

        label {

            display:
                block;

            margin-bottom:
                8px;

            color:
                #333;

            font-weight:
                bold;

            font-size:
                16px;

        }


        /* =================================================
           INPUT
        ================================================= */

        input,
        select,
        textarea {

            width:
                100%;

            padding:
                13px;

            border:
                1px solid #ccc;

            border-radius:
                7px;

            font-size:
                16px;

            margin-bottom:
                20px;

            outline:
                none;

        }


        input:focus,
        select:focus,
        textarea:focus {

            border-color:
                #238b45;

            box-shadow:
                0 0 5px
                rgba(35,139,69,0.20);

        }


        textarea {

            min-height:
                180px;

            resize:
                vertical;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

        }


        /* =================================================
           BUTTON AREA
        ================================================= */

        .button-area {

            display:
                flex;

            gap:
                15px;

            margin-top:
                10px;

        }


        /* =================================================
           UPDATE BUTTON
        ================================================= */

        .update-btn {

            flex:
                1;

            padding:
                14px;

            background:
                #238b45;

            color:
                white;

            border:
                none;

            border-radius:
                7px;

            font-size:
                17px;

            font-weight:
                bold;

            cursor:
                pointer;

        }


        .update-btn:hover {

            background:
                #176b34;

        }


        /* =================================================
           CANCEL BUTTON
        ================================================= */

        .cancel-btn {

            flex:
                1;

            padding:
                14px;

            background:
                #777;

            color:
                white;

            border-radius:
                7px;

            text-align:
                center;

            text-decoration:
                none;

            font-size:
                17px;

            font-weight:
                bold;

        }


        .cancel-btn:hover {

            background:
                #555;

            color:
                white;

            text-decoration:
                none;

        }


        /* =================================================
           MOBILE
        ================================================= */

        @media screen and (max-width: 600px) {

            .page {

                width:
                    92%;

                margin-top:
                    30px;

            }


            .form-card {

                padding:
                    22px;

            }


            .title {

                font-size:
                    29px;

            }


            .button-area {

                flex-direction:
                    column;

            }


            .current-image {

                width:
                    100%;

                height:
                    220px;

            }

        }

    </style>

</head>


<body>


<?php

require 'menu.php';

?>


<div class="page">


    <h1 class="title">

        ✏️ Edit Product

    </h1>


    <div class="form-card">


        <!-- =================================================
             CURRENT IMAGE
        ================================================== -->

        <div class="current-image-area">


            <?php

            if (
                !empty($product['pimage']) &&
                file_exists(
                    "images/productImages/" .
                    $product['pimage']
                )
            ) {

            ?>

                <img
                    src="images/productImages/<?php
                        echo htmlspecialchars(
                            $product['pimage']
                        );
                    ?>"
                    class="current-image"
                    alt="Current Product"
                >

            <?php

            }

            ?>


            <div class="current-image-text">

                Current Product Image

            </div>


        </div>


        <!-- =================================================
             FORM
        ================================================== -->

        <form
            method="POST"
            action="editProduct.php"
            enctype="multipart/form-data"
        >


            <input
                type="hidden"
                name="pid"
                value="<?php echo $pid; ?>"
            >


            <!-- PRODUCT NAME -->

            <label>
                Product Name
            </label>

            <input
                type="text"
                name="pname"
                value="<?php
                    echo htmlspecialchars(
                        $product['product']
                    );
                ?>"
                required
            >


            <!-- CATEGORY -->

            <label>
                Category
            </label>

            <select
                name="type"
                required
            >

                <option value="">
                    - Category -
                </option>


                <option
                    value="Fruit"
                    <?php
                    if ($product['pcat'] == "Fruit") {
                        echo "selected";
                    }
                    ?>
                >
                    Fruit
                </option>


                <option
                    value="Vegetable"
                    <?php
                    if ($product['pcat'] == "Vegetable") {
                        echo "selected";
                    }
                    ?>
                >
                    Vegetable
                </option>


                <option
                    value="Grains"
                    <?php
                    if ($product['pcat'] == "Grains") {
                        echo "selected";
                    }
                    ?>
                >
                    Grains
                </option>

            </select>


            <!-- PRODUCT INFORMATION -->

            <label>
                Product Information
            </label>

            <textarea
                name="pinfo"
                required
            ><?php
                echo htmlspecialchars(
                    $product['pinfo']
                );
            ?></textarea>


            <!-- PRICE -->

            <label>
                Price
            </label>

            <input
                type="number"
                name="price"
                min="1"
                step="0.01"
                value="<?php
                    echo htmlspecialchars(
                        $product['price']
                    );
                ?>"
                required
            >


            <!-- NEW IMAGE -->

            <label>
                Change Product Image
            </label>

            <input
                type="file"
                name="productPic"
                accept=".jpg,.jpeg,.png"
            >


            <small
                style="
                    display:block;
                    color:#777;
                    margin-top:-12px;
                    margin-bottom:20px;
                "
            >
                Leave empty if you want to keep the
                current image.
            </small>


            <!-- BUTTONS -->

            <div class="button-area">


                <button
                    type="submit"
                    class="update-btn"
                >

                    ✓ Update Product

                </button>


                <a
                    href="productMenu.php"
                    class="cancel-btn"
                >

                    Cancel

                </a>


            </div>


        </form>


    </div>

</div>


</body>

</html>