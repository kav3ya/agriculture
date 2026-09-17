<?php

session_start();

require 'db.php';


/* =====================================================
   CHECK LOGIN
===================================================== */

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1)
{
    $_SESSION['message'] =
        "You need to login first.";

    header("Location: Login/error.php");
    exit();
}


/* =====================================================
   CHECK FARMER
===================================================== */

if (!isset($_SESSION['Category']) || $_SESSION['Category'] != 1)
{
    $_SESSION['message'] =
        "Only farmers can write blogs.";

    header("Location: Login/error.php");
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
        AgroCulture - Write a Blog
    </title>


    <!-- Bootstrap -->

    <link
        rel="stylesheet"
        href="bootstrap/css/bootstrap.min.css"
    >


    <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js">
    </script>


    <script
        src="bootstrap/js/bootstrap.min.js">
    </script>


    <!-- CKEditor -->

    <script
        src="https://cdn.ckeditor.com/4.8.0/full/ckeditor.js">
    </script>


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


        /* =================================================
           BODY
        ================================================= */

        body {

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background:
                #eef6f0;

            color:
                #333;

        }


        /* =================================================
           NAVIGATION
        ================================================= */

        .navbar {

            width: 100%;

            min-height: 75px;

            background:
                #1f2424;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding:
                0 4%;

        }


        .logo {

            color: white;

            font-size: 30px;

            text-decoration: none;

            font-weight: 400;

        }


        .logo:hover {

            color: white;

            text-decoration: none;

        }


        .nav-links {

            list-style: none;

            display: flex;

            align-items: center;

            gap: 25px;

            margin: 0;

        }


        .nav-links li {

            display: inline-block;

        }


        .nav-links a {

            color: white;

            text-decoration: none;

            font-size: 16px;

        }


        .nav-links a:hover {

            color: #a9d8ad;

            text-decoration: none;

        }


        /* =================================================
           PAGE
        ================================================= */

        .page {

            width: 100%;

            min-height:
                calc(100vh - 75px);

            padding:
                45px 20px 60px 20px;

        }


        .container-box {

            width: 100%;

            max-width: 1100px;

            margin:
                auto;

        }


        /* =================================================
           HEADER
        ================================================= */

        .page-header {

            background:
                white;

            border-radius:
                12px;

            padding:
                25px 30px;

            margin-bottom:
                25px;

            box-shadow:
                0 4px 15px
                rgba(0,0,0,0.10);

            display:
                flex;

            justify-content:
                space-between;

            align-items:
                center;

        }


        .page-header h1 {

            margin: 0;

            color:
                #238b45;

            font-size:
                34px;

            font-weight:
                bold;

        }


        .page-header p {

            margin:
                7px 0 0 0;

            color:
                #777;

            font-size:
                16px;

        }


        .view-blog-btn {

            background:
                #238b45;

            color:
                white;

            padding:
                12px 22px;

            border-radius:
                7px;

            text-decoration:
                none;

            font-weight:
                bold;

            white-space:
                nowrap;

        }


        .view-blog-btn:hover {

            background:
                #176b34;

            color:
                white;

            text-decoration:
                none;

        }


        /* =================================================
           FORM CARD
        ================================================= */

        .form-card {

            background:
                white;

            border-radius:
                12px;

            padding:
                35px;

            box-shadow:
                0 4px 18px
                rgba(0,0,0,0.10);

        }


        /* =================================================
           LABEL
        ================================================= */

        .form-label {

            display:
                block;

            color:
                #333;

            font-size:
                17px;

            font-weight:
                bold;

            margin-bottom:
                8px;

        }


        /* =================================================
           BLOG TITLE
        ================================================= */

        .blog-title-input {

            width: 100%;

            height: 55px;

            border:
                1px solid #d4ddd7;

            border-radius:
                7px;

            padding:
                0 15px;

            font-size:
                17px;

            outline:
                none;

            margin-bottom:
                25px;

        }


        .blog-title-input:focus {

            border-color:
                #238b45;

            box-shadow:
                0 0 5px
                rgba(35,139,69,0.20);

        }


        /* =================================================
           BLOG CONTENT
        ================================================= */

        .content-label {

            margin-top:
                5px;

        }


        #blogContent {

            width: 100%;

            min-height: 350px;

        }


        /* =================================================
           SUBMIT AREA
        ================================================= */

        .submit-area {

            text-align:
                center;

            margin-top:
                30px;

        }


        .submit-btn {

            background:
                #238b45;

            color:
                white;

            border:
                none;

            border-radius:
                7px;

            padding:
                13px 35px;

            font-size:
                17px;

            font-weight:
                bold;

            cursor:
                pointer;

        }


        .submit-btn:hover {

            background:
                #176b34;

        }


        /* =================================================
           MOBILE
        ================================================= */

        @media screen and (max-width: 768px)
        {

            .navbar {

                flex-direction:
                    column;

                padding:
                    15px;

                gap:
                    15px;

            }


            .nav-links {

                flex-wrap:
                    wrap;

                justify-content:
                    center;

                gap:
                    12px;

            }


            .page {

                padding:
                    25px 12px 40px 12px;

            }


            .page-header {

                flex-direction:
                    column;

                align-items:
                    stretch;

                gap:
                    20px;

                padding:
                    20px;

            }


            .page-header h1 {

                font-size:
                    28px;

            }


            .view-blog-btn {

                text-align:
                    center;

            }


            .form-card {

                padding:
                    20px;

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
                🏠 Home
            </a>
        </li>


        <li>
            <a href="myCart.php">
                🛒 MyCart
            </a>
        </li>


        <li>
            <a href="myOrders.php">
                📋 My Orders
            </a>
        </li>


        <li>
            <a href="profileView.php">
                👤 My Profile
            </a>
        </li>


        <li>
            <a href="market.php">
                🌱 Digital-Market
            </a>
        </li>


        <li>
            <a href="blogView.php">
                💬 BLOG
            </a>
        </li>


    </ul>


</nav>


<!-- =====================================================
     MAIN PAGE
===================================================== -->

<main class="page">


    <div class="container-box">


        <!-- =================================================
             HEADER
        ================================================== -->

        <div class="page-header">


            <div>

                <h1>
                    Write a Blog
                </h1>


                <p>
                    Share your agricultural knowledge and experience.
                </p>

            </div>


            <a
                href="blogView.php"
                class="view-blog-btn"
            >

                View Blogs

            </a>


        </div>


        <!-- =================================================
             FORM
        ================================================== -->

        <div class="form-card">


            <form
                method="POST"
                action="Blog/blogSubmit.php"
            >


                <!-- BLOG TITLE -->

                <label
                    for="blogTitle"
                    class="form-label"
                >

                    Blog Title

                </label>


                <input
                    type="text"
                    name="blogTitle"
                    id="blogTitle"
                    class="blog-title-input"
                    placeholder="Enter your blog title"
                    required
                >


                <!-- BLOG CONTENT -->

                <label
                    for="blogContent"
                    class="form-label content-label"
                >

                    Blog Content

                </label>


                <textarea
                    name="blogContent"
                    id="blogContent"
                    placeholder="Write your agricultural blog here..."
                    required
                ></textarea>


                <!-- SUBMIT -->

                <div class="submit-area">


                    <button
                        type="submit"
                        name="submit"
                        class="submit-btn"
                    >

                        ✎ Publish Blog

                    </button>


                </div>


            </form>


        </div>


    </div>


</main>


<!-- =====================================================
     CKEDITOR
===================================================== -->

<script>

CKEDITOR.replace('blogContent');

</script>


</body>

</html>