<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = "";

if (isset($_SESSION['Username'])) {
    $username = $_SESSION['Username'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Change Password - AgroCulture</title>

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

            min-height: 100vh;

            font-family: Arial, Helvetica, sans-serif;

            background-image:
                linear-gradient(
                    rgba(15, 55, 25, 0.45),
                    rgba(15, 55, 25, 0.45)
                ),
                url("../images/banner.jpg");

            background-size: cover;

            background-position: center;

            background-repeat: no-repeat;

            background-attachment: fixed;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 40px 9%;

        }


        /* =================================
           LEFT SIDE MESSAGE
           ================================= */

        .left-content {

            width: 48%;

            color: white;

            padding-left: 20px;

        }


        .left-line {

            width: 65px;

            height: 4px;

            background: white;

            border-radius: 5px;

            margin-bottom: 20px;

        }


        .left-content h1 {

            font-size: 48px;

            line-height: 1.15;

            margin: 0 0 18px;

            font-weight: bold;

            text-shadow:
                0 3px 8px rgba(0, 0, 0, 0.45);

        }


        .left-content p {

            font-size: 18px;

            line-height: 1.7;

            max-width: 470px;

            margin: 0 0 25px;

            color: white;

            text-shadow:
                0 2px 5px rgba(0, 0, 0, 0.45);

        }


        .left-highlight {

            font-size: 16px;

            font-weight: bold;

            border-left: 4px solid white;

            padding-left: 14px;

            line-height: 1.5;

        }


        /* =================================
           RIGHT SIDE FORM
           ================================= */

        .form-area {

            width: 350px;

            color: white;

        }


        .logo {

            font-size: 32px;

            margin-bottom: 5px;

        }


        .form-area h2 {

            margin: 0;

            font-size: 30px;

            font-weight: bold;

            color: white;

            text-shadow:
                0 2px 5px rgba(0, 0, 0, 0.35);

        }


        .subtitle {

            margin-top: 6px;

            margin-bottom: 25px;

            font-size: 14px;

            color: rgba(255, 255, 255, 0.9);

        }


        /* =================================
           FORM
           ================================= */

        .form-group {

            margin-bottom: 18px;

        }


        .form-group label {

            display: block;

            margin-bottom: 7px;

            font-size: 14px;

            font-weight: bold;

            color: white;

            text-shadow:
                0 1px 3px rgba(0, 0, 0, 0.4);

        }


        .form-group input {

            width: 100%;

            height: 44px;

            padding: 0 12px;

            border: none;

            border-bottom:
                2px solid rgba(255, 255, 255, 0.9);

            border-radius: 0;

            background:
                rgba(255, 255, 255, 0.12);

            color: white;

            font-size: 15px;

            outline: none;

        }


        .form-group input::placeholder {

            color:
                rgba(255, 255, 255, 0.75);

        }


        .form-group input:focus {

            border-bottom:
                2px solid white;

            background:
                rgba(255, 255, 255, 0.20);

        }


        .username-input {

            color: white !important;

            background:
                rgba(255, 255, 255, 0.10) !important;

        }


        /* =================================
           BUTTON
           ================================= */

        .change-button {

            width: 100%;

            height: 46px;

            margin-top: 5px;

            border: none;

            border-radius: 5px;

            background: white;

            color: #27652d;

            font-size: 15px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.2s;

        }


        .change-button:hover {

            background: #27652d;

            color: white;

        }


        /* =================================
           FOOTER
           ================================= */

        .footer {

            margin-top: 16px;

            font-size: 11px;

            color:
                rgba(255, 255, 255, 0.8);

        }


        .footer strong {

            color: white;

        }


        /* =================================
           MOBILE
           ================================= */

        @media (max-width: 800px) {

            body {

                flex-direction: column;

                justify-content: center;

                padding: 30px;

            }


            .left-content {

                width: 100%;

                padding: 0;

                margin-bottom: 35px;

                text-align: center;

            }


            .left-line {

                margin:
                    0 auto 18px;

            }


            .left-content h1 {

                font-size: 34px;

            }


            .left-content p {

                font-size: 15px;

                margin-left: auto;

                margin-right: auto;

            }


            .left-highlight {

                border-left: none;

                padding-left: 0;

            }


            .form-area {

                width: 350px;

                max-width: 100%;

            }

        }

    </style>

</head>


<body>


    <!-- =================================
         LEFT SIDE MESSAGE
         ================================= -->

    <div class="left-content">

        <div class="left-line"></div>

        <h1>
            Smart Farming.<br>
            Better Future.
        </h1>

        <p>
            Agriculture is the foundation of life.
            With modern technology and sustainable
            farming practices, we can help farmers
            grow healthier crops and build a better
            future.
        </p>

        <div class="left-highlight">
            Growing crops today for a greener
            tomorrow.
        </div>

    </div>


    <!-- =================================
         RIGHT SIDE CHANGE PASSWORD
         ================================= -->

    <div class="form-area">

        <div class="logo">
            🌱
        </div>

        <h2>
            Change Password
        </h2>

        <div class="subtitle">
            Keep your AgroCulture account secure
        </div>


        <form
            method="POST"
            action="changePass.php"
        >


            <!-- USERNAME -->

            <div class="form-group">

                <label for="uname">
                    Username
                </label>

                <input
                    type="text"
                    id="uname"
                    name="uname"
                    value="<?php echo htmlspecialchars($username); ?>"
                    class="username-input"
                    readonly
                >

            </div>


            <!-- CURRENT PASSWORD -->

            <div class="form-group">

                <label for="currPass">
                    Current Password
                </label>

                <input
                    type="password"
                    id="currPass"
                    name="currPass"
                    placeholder="Enter current password"
                    required
                >

            </div>


            <!-- NEW PASSWORD -->

            <div class="form-group">

                <label for="newPass">
                    New Password
                </label>

                <input
                    type="password"
                    id="newPass"
                    name="newPass"
                    placeholder="Enter new password"
                    required
                >

            </div>


            <!-- CONFIRM PASSWORD -->

            <div class="form-group">

                <label for="conNewPass">
                    Confirm New Password
                </label>

                <input
                    type="password"
                    id="conNewPass"
                    name="conNewPass"
                    placeholder="Confirm new password"
                    required
                >

            </div>


            <!-- BUTTON -->

            <button
                type="submit"
                name="submit"
                class="change-button"
            >
                Change Password
            </button>


        </form>


        <div class="footer">

            <strong>AgroCulture</strong>
            &nbsp; | &nbsp;
            Secure Account

        </div>

    </div>


</body>

</html>