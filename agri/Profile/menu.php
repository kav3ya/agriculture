<?php

/* =====================================================
   START SESSION
===================================================== */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/* =====================================================
   CHECK USER CATEGORY
===================================================== */

/*
   In your AgroCulture project:

   Category 1 = Farmer
   Other category = Buyer

   We also check the text "Farmer" in case the
   session stores the category as text.
*/

$isFarmer = false;

if (isset($_SESSION['Category'])) {

    $category = trim(
        (string) $_SESSION['Category']
    );

    if (
        $category === '1' ||
        strtolower($category) === 'farmer'
    ) {

        $isFarmer = true;

    }

}


/* =====================================================
   GET USER NAME
===================================================== */

$userName = '';

if (isset($_SESSION['Name'])) {

    $userName = $_SESSION['Name'];

}

elseif (isset($_SESSION['name'])) {

    $userName = $_SESSION['name'];

}

?>


<style>

/* =====================================================
   RESET
===================================================== */

* {
    box-sizing: border-box;
}


/* =====================================================
   NAVIGATION BAR
===================================================== */

.agro-navbar {

    width: 100%;

    height: 75px;

    background: #1f2223;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 55px;

    position: fixed;

    top: 0;

    left: 0;

    z-index: 99999;

    box-shadow:
        0 2px 8px rgba(0,0,0,0.15);

}


/* =====================================================
   LOGO
===================================================== */

.agro-logo {

    color: white;

    font-size: 30px;

    font-weight: 500;

    text-decoration: none;

    white-space: nowrap;

}


.agro-logo:hover {

    color: white;

    text-decoration: none;

}


/* =====================================================
   MENU
===================================================== */

.agro-menu {

    display: flex;

    align-items: center;

    justify-content: flex-end;

    gap: 30px;

    margin: 0;

    padding: 0;

}


/* =====================================================
   MENU LINKS
===================================================== */

.agro-menu a {

    color: #e8f5e9;

    text-decoration: none;

    font-size: 18px;

    white-space: nowrap;

    transition: color 0.2s ease;

}


.agro-menu a:hover {

    color: #4caf50;

    text-decoration: none;

}


/* =====================================================
   ICON
===================================================== */

.menu-icon {

    margin-right: 5px;

}


/* =====================================================
   MOBILE
===================================================== */

@media screen and (max-width: 1100px) {

    .agro-navbar {

        padding: 0 25px;

    }

    .agro-menu {

        gap: 18px;

    }

    .agro-menu a {

        font-size: 16px;

    }

    .agro-logo {

        font-size: 26px;

    }

}


@media screen and (max-width: 800px) {

    .agro-navbar {

        height: auto;

        min-height: 75px;

        padding: 15px 20px;

        flex-direction: column;

        align-items: flex-start;

    }


    .agro-menu {

        width: 100%;

        display: flex;

        flex-wrap: wrap;

        justify-content: flex-start;

        gap: 12px 20px;

        margin-top: 10px;

    }

}


</style>


<!-- =====================================================
     NAVIGATION BAR
===================================================== -->

<nav class="agro-navbar">


    <!-- =================================================
         LOGO
    ================================================= -->

    <a
        href="index.php"
        class="agro-logo"
    >

        AgroCulture

    </a>


    <!-- =================================================
         MENU ITEMS
    ================================================= -->

    <div class="agro-menu">


        <!-- =================================================
             HOME
        ================================================= -->

        <a href="index.php">

            <span class="menu-icon">
                🏠
            </span>

            Home

        </a>


        <!-- =================================================
             MY CART

             IMPORTANT:

             Farmer:
             MyCart is NOT displayed.

             Buyer:
             MyCart is displayed.
        ================================================= -->

        <?php

        if ($isFarmer === false) {

        ?>

            <a href="myCart.php">

                <span class="menu-icon">
                    🛒
                </span>

                MyCart

            </a>

        <?php

        }

        ?>


        <!-- =================================================
             MY ORDERS
        ================================================= -->

        <a href="myOrders.php">

            <span class="menu-icon">
                ▣
            </span>

            My Orders

        </a>


        <!-- =================================================
             MY PROFILE
        ================================================= -->

        <a href="profileView.php">

            <span class="menu-icon">
                👤
            </span>

            My Profile

            <?php

            if (!empty($userName)) {

                echo ": ";

                echo htmlspecialchars(
                    $userName,
                    ENT_QUOTES,
                    'UTF-8'
                );

            }

            ?>

        </a>


        <!-- =================================================
             DIGITAL MARKET
        ================================================= -->

        <a href="productMenu.php">

            <span class="menu-icon">
                🌾
            </span>

            Digital-Market

        </a>


        <!-- =================================================
             BLOG
        ================================================= -->

        <a href="blog.php">

            <span class="menu-icon">
                💬
            </span>

            BLOG

        </a>


    </div>


</nav>