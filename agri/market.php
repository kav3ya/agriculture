<?php

session_start();

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

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>AgroCulture - Digital Market</title>


<style>

/* =====================================================
   RESET
===================================================== */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


/* =====================================================
   BODY
===================================================== */

body {

    font-family: Arial, Helvetica, sans-serif;

    background-image:
        linear-gradient(
            rgba(255,255,255,0.20),
            rgba(255,255,255,0.20)
        ),
        url("images/banner.jpg");

    background-size: cover;

    background-position: center;

    background-attachment: fixed;

    min-height: 100vh;

}


/* =====================================================
   MAIN DIGITAL MARKET AREA
===================================================== */

.market-container {

    width: 100%;

    min-height: calc(100vh - 100px);

    padding-top: 80px;

    padding-bottom: 70px;

}


/* =====================================================
   PAGE TITLE
===================================================== */

.market-title {

    text-align: center;

    margin-bottom: 55px;

}


.market-title h1 {

    font-size: 48px;

    font-weight: bold;

    color: #218838;

    text-shadow:
        1px 2px 3px
        rgba(0,0,0,0.35);

}


/* =====================================================
   THREE BLOCKS
===================================================== */

.market-cards {

    width: 92%;

    max-width: 1600px;

    margin: 0 auto;

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 45px;

}


/* =====================================================
   MARKET CARD
===================================================== */

.market-card {

    min-height: 440px;

    background:
        rgba(255,255,255,0.90);

    border-radius: 22px;

    padding:
        45px
        30px;

    display: flex;

    flex-direction: column;

    justify-content: center;

    align-items: center;

    text-align: center;

    text-decoration: none;

    box-shadow:
        0 5px 18px
        rgba(0,0,0,0.18);

    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease;

}


/* =====================================================
   CARD HOVER
===================================================== */

.market-card:hover {

    transform:
        translateY(-8px);

    box-shadow:
        0 12px 28px
        rgba(0,0,0,0.25);

}


/* =====================================================
   ICON
===================================================== */

.market-icon {

    width: 150px;

    height: 150px;

    object-fit: contain;

    margin-bottom: 25px;

}


/* =====================================================
   EMOJI ICON
===================================================== */

.market-emoji {

    font-size: 110px;

    line-height: 1;

    margin-bottom: 30px;

}


/* =====================================================
   CARD TITLE
===================================================== */

.market-card h2 {

    color: #176b2c;

    font-size: 31px;

    font-weight: bold;

    margin-bottom: 20px;

}


/* =====================================================
   CARD DESCRIPTION
===================================================== */

.market-card p {

    color: #333;

    font-size: 21px;

    line-height: 1.6;

    max-width: 390px;

}


/* =====================================================
   REMOVE LINK UNDERLINE
===================================================== */

.market-card-link {

    text-decoration: none;

    color: inherit;

}


/* =====================================================
   TABLET
===================================================== */

@media (max-width: 1100px) {

    .market-cards {

        grid-template-columns:
            repeat(2, 1fr);

        gap: 30px;

    }

    .market-card {

        min-height: 400px;

    }

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 700px) {

    .market-container {

        padding-top: 50px;

    }

    .market-title {

        margin-bottom: 35px;

        padding: 0 15px;

    }

    .market-title h1 {

        font-size: 34px;

    }

    .market-cards {

        width: 90%;

        grid-template-columns: 1fr;

        gap: 25px;

    }

    .market-card {

        min-height: 360px;

    }

    .market-card h2 {

        font-size: 27px;

    }

    .market-card p {

        font-size: 18px;

    }

}

</style>

</head>


<body>


<?php

/* =====================================================
   NAVIGATION BAR
===================================================== */

require 'menu.php';

?>


<!-- =====================================================
     DIGITAL MARKET
===================================================== -->

<div class="market-container">


    <!-- PAGE TITLE -->

    <div class="market-title">

        <h1>
            Welcome to Digital Market
        </h1>

    </div>



    <!-- =================================================
         THREE CARDS
    ================================================== -->

    <div class="market-cards">



        <!-- =============================================
             YOUR PROFILE
        ============================================== -->

        <a
            href="profileView.php"
            class="market-card-link"
        >

            <div class="market-card">


                <div class="market-emoji">
                    👤
                </div>


                <h2>
                    Your Profile
                </h2>


                <p>
                    View and manage your profile
                    information.
                </p>


            </div>

        </a>



        <!-- =============================================
             SEARCH PRODUCTS
             
             IMPORTANT:
             Opens category search.
        ============================================== -->

        <a
            href="productMenu.php?mode=search"
            class="market-card-link"
        >

            <div class="market-card">


                <div class="market-emoji">
                    🔍
                </div>


                <h2>
                    Search Products
                </h2>


                <p>
                    Search products according to
                    your needs.
                </p>


            </div>

        </a>



        <!-- =============================================
             OUR PRODUCTS
             
             IMPORTANT:
             Opens ALL products directly.
        ============================================== -->

        <a
            href="productMenu.php?mode=all"
            class="market-card-link"
        >

            <div class="market-card">


                <div class="market-emoji">
                    🌾
                </div>


                <h2>
                    Our Products
                </h2>


                <p>
                    Browse all available
                    agricultural products.
                </p>


            </div>

        </a>


    </div>


</div>


</body>

</html>