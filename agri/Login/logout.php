<?php
session_start();

session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>AgroCulture - Logout</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            height: 100%;
        }

        body {

            font-family: Arial, Helvetica, sans-serif;

            background-image:
                linear-gradient(
                    rgba(0, 0, 0, 0.40),
                    rgba(0, 0, 0, 0.40)
                ),
                url("../images/banner.jpg");

            background-size: cover;

            background-position: center;

            background-repeat: no-repeat;

            min-height: 100vh;

            display: flex;

            flex-direction: column;
        }


        /* =========================
           HEADER
        ========================= */

        .header {

            height: 85px;

            background: rgba(25, 30, 32, 0.95);

            display: flex;

            align-items: center;

            padding-left: 6%;

            color: white;
        }


        .logo {

            font-size: 38px;

            font-weight: 300;

            color: white;
        }


        /* =========================
           MAIN CONTENT
        ========================= */

        .logout-container {

            flex: 1;

            display: flex;

            justify-content: center;

            align-items: center;

            text-align: center;

            padding: 30px;
        }


        .logout-content {

            color: white;

            text-shadow: 1px 2px 5px rgba(0,0,0,0.7);
        }


        .logout-content h1 {

            font-size: 52px;

            font-weight: 400;

            margin-bottom: 20px;
        }


        .logout-content p {

            font-size: 22px;

            margin-bottom: 35px;
        }


        /* =========================
           HOME BUTTON
        ========================= */

        .home-button {

            display: inline-block;

            background: #2e7d32;

            color: white;

            text-decoration: none;

            padding: 13px 42px;

            border-radius: 4px;

            font-size: 18px;

            transition: 0.3s;
        }


        .home-button:hover {

            background: #1b5e20;

            color: white;

            text-decoration: none;
        }


        /* =========================
           FOOTER
        ========================= */

        .footer {

            background: rgba(25, 30, 32, 0.95);

            color: #aaa;

            text-align: center;

            padding: 18px;

            font-size: 14px;
        }


        /* =========================
           MOBILE
        ========================= */

        @media screen and (max-width: 600px) {

            .header {

                height: 75px;

                justify-content: center;

                padding: 0;
            }


            .logo {

                font-size: 30px;
            }


            .logout-content h1 {

                font-size: 36px;
            }


            .logout-content p {

                font-size: 18px;
            }

        }

    </style>

</head>


<body>


    <!-- HEADER -->

    <header class="header">

        <div class="logo">

            AgroCulture

        </div>

    </header>


    <!-- MAIN CONTENT -->

    <main class="logout-container">

        <div class="logout-content">

            <h1>
                Thanks for visiting!
            </h1>

            <p>
                You have been successfully logged out.
            </p>

            <a href="../index.php"
               class="home-button">

                HOME

            </a>

        </div>

    </main>


    <!-- FOOTER -->

    <footer class="footer">

        © 2026 AgroCulture. All Rights Reserved.

    </footer>


</body>

</html>