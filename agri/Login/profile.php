<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1)
{
    $_SESSION['message'] = "You must log in before viewing your profile page!";
    header("location: error.php");
    exit();
}

$email  = $_SESSION['Email'];
$name   = $_SESSION['Name'];
$user   = $_SESSION['Username'];
$mobile = $_SESSION['Mobile'];
$address = $_SESSION['Addr'];
$active = $_SESSION['Active'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>AgroCulture - My Profile</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <script src="../bootstrap/js/bootstrap.min.js"></script>

    <style>

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            min-height: 100%;
        }

        body {
            font-family: Arial, sans-serif;
            background: #eef7e8;
        }

        .profile-background {
            min-height: calc(100vh - 70px);
            padding: 60px 20px;

            background: linear-gradient(
                135deg,
                #f1f8e9,
                #dcedc8
            );
        }

        .profile-content {
            width: 100%;
            max-width: 850px;

            margin: 0 auto;

            padding: 40px;

            background: rgba(255, 255, 255, 0.96);

            border-radius: 8px;

            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.12);
        }

        .profile-title {
            margin: 0 0 30px 0;

            font-size: 34px;

            color: #2E7D32;

            font-weight: bold;
        }

        .verification-message {
            background: #fff3cd;

            border: 1px solid #ffe69c;

            color: #856404;

            padding: 15px 20px;

            margin-bottom: 30px;

            border-radius: 5px;

            font-size: 16px;

            line-height: 1.6;

            text-align: center;
        }

        .user-name {
            color: #2E7D32;

            font-size: 28px;

            font-weight: bold;

            margin-top: 25px;

            margin-bottom: 25px;
        }

        .profile-details {
            font-size: 18px;

            line-height: 2;

            color: #555;
        }

        .profile-details strong {
            color: #333;
        }

        .profile-buttons {
            margin-top: 35px;
        }

        .profile-button {
            display: inline-block;

            padding: 11px 25px;

            margin-right: 12px;

            margin-bottom: 10px;

            color: white !important;

            text-decoration: none !important;

            border-radius: 4px;

            font-size: 16px;
        }

        .green-button {
            background: #4CAF50;
        }

        .green-button:hover {
            background: #388E3C;
        }

        .gray-button {
            background: #555;
        }

        .gray-button:hover {
            background: #333;
        }

    </style>

</head>


<body>

<?php
require 'menu.php';
?>


<div class="profile-background">

    <div class="profile-content">

        <!-- Page Heading -->

        <h1 class="profile-title">
            My Profile
        </h1>


        <!-- Verification Message -->

        <?php if (!$active): ?>

            <div class="verification-message">

                Please confirm your email by clicking on the
                verification link sent to your email address.

            </div>

        <?php endif; ?>


        <!-- User Name -->

        <h2 class="user-name">
            <?php echo $name; ?>
        </h2>


        <!-- User Details -->

        <div class="profile-details">

            <p>
                <strong>Email:</strong>
                <?php echo $email; ?>
            </p>

            <p>
                <strong>Username:</strong>
                <?php echo $user; ?>
            </p>

            <p>
                <strong>Mobile:</strong>
                <?php echo $mobile; ?>
            </p>

            <p>
                <strong>Address:</strong>
                <?php echo $address; ?>
            </p>

        </div>


        <!-- Buttons -->

        <div class="profile-buttons">

            <?php if ($_SESSION['Category'] == 1): ?>

                <!-- Farmer -->

                <a href="../profileView.php"
                   class="profile-button green-button">
                    My Profile
                </a>

                <a href="logout.php"
                   class="profile-button gray-button">
                    LOG OUT
                </a>

            <?php else: ?>

                <!-- Buyer -->

                <a href="../market.php"
                   class="profile-button green-button">
                    Digital Market
                </a>

                <a href="logout.php"
                   class="profile-button gray-button">
                    LOG OUT
                </a>

            <?php endif; ?>

        </div>

    </div>

</div>


</body>

</html>