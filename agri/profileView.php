<?php
session_start();
require 'db.php';

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1) {
    $_SESSION['message'] = "Please login first.";
    header("Location: Login/login.php");
    exit();
}

$name = $_SESSION['Name'] ?? '';
$username = $_SESSION['Username'] ?? '';
$email = $_SESSION['Email'] ?? '';
$mobile = $_SESSION['Mobile'] ?? '';
$address = $_SESSION['Addr'] ?? '';
$category = $_SESSION['Category'] ?? 0;

$picName = $_SESSION['picName'] ?? 'profile0.png';
$profileImage = "images/profileImages/" . $picName;

if (!file_exists($profileImage)) {
    $profileImage = "images/profileImages/profile0.png";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgroCulture - My Profile</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            background: #9db5a1;
            color: white;
        }

        .navbar {
            width: 100%;
            min-height: 75px;
            background: #1f1f1f;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 55px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.20);
        }

        .logo {
            color: white;
            font-size: 32px;
            font-weight: bold;
            white-space: nowrap;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-links a {
            color: #e8f5e9;
            text-decoration: none;
            font-size: 18px;
            white-space: nowrap;
            transition: 0.2s;
        }

        .nav-links a:hover {
            color: #4caf50;
        }

        .profile-page {
            min-height: calc(100vh - 75px);
            padding: 65px 20px 70px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .profile-card {
            width: 100%;
            max-width: 1050px;
            text-align: center;
        }

        .profile-image {
            width: 190px;
            height: 190px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            display: block;
            margin: 0 auto 22px;
            background: #f1f1f1;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.15);
        }

        .profile-name {
            font-size: 40px;
            font-weight: bold;
            color: white;
            margin-bottom: 40px;
        }

        .profile-info {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }

        .info-row {
            width: 100%;
            display: grid;
            grid-template-columns: 180px 1fr;
            text-align: left;
            padding: 18px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.45);
        }

        .info-label {
            font-weight: bold;
            color: white;
            font-size: 23px;
        }

        .info-value {
            color: white;
            font-size: 22px;
            word-break: break-word;
        }

        .account-type {
            margin-top: 25px;
            display: inline-block;
            padding: 10px 25px;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.25);
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        .profile-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 35px;
        }

        .profile-button {
            min-width: 180px;
            height: 55px;
            padding: 0 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .profile-button:hover {
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.18);
        }

        .password-button {
            background: #e74c3c;
        }

        .password-button:hover {
            background: #d63c2f;
        }

        .edit-button {
            background: #3498db;
        }

        .edit-button:hover {
            background: #287fba;
        }

        .upload-button {
            background: #28a745;
        }

        .upload-button:hover {
            background: #218838;
        }

        .orders-button {
            background: #f39c12;
        }

        .orders-button:hover {
            background: #d98208;
        }

        .logout-button {
            background: #555555;
        }

        .logout-button:hover {
            background: #3f3f3f;
        }

        @media screen and (max-width: 900px) {
            .navbar {
                padding: 0 20px;
            }

            .logo {
                font-size: 25px;
            }

            .nav-links {
                gap: 15px;
            }

            .nav-links a {
                font-size: 14px;
            }

            .profile-card {
                width: 95%;
            }
        }

        @media screen and (max-width: 650px) {
            .navbar {
                min-height: 75px;
                flex-direction: column;
                padding: 18px 10px;
                gap: 15px;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 12px 18px;
            }

            .profile-page {
                padding: 35px 15px 50px;
            }

            .profile-image {
                width: 140px;
                height: 140px;
            }

            .profile-name {
                font-size: 32px;
                margin-bottom: 30px;
            }

            .info-row {
                grid-template-columns: 1fr;
                gap: 7px;
                padding: 15px 0;
            }

            .info-label {
                font-size: 19px;
            }

            .info-value {
                font-size: 18px;
            }

            .profile-buttons {
                flex-direction: column;
                gap: 12px;
            }

            .profile-button {
                width: 90%;
            }
        }
    </style>
</head>

<body>

<nav class="navbar">
    <div class="logo">AgroCulture</div>

    <div class="nav-links">
        <a href="index.php">🏠 Home</a>

        <?php if ($category == 0) { ?>
            <a href="myCart.php">🛒 MyCart</a>
        <?php } ?>

        <a href="myOrders.php">📋 My Orders</a>
        <a href="profileView.php">👤 My Profile</a>
        <a href="market.php">>🌱 Digital-Market</a>
        <a href="blog.php">💬 BLOG</a>
    </div>
</nav>

<main class="profile-page">
    <div class="profile-card">

        <img
            src="<?php echo htmlspecialchars($profileImage); ?>"
            class="profile-image"
            alt="Profile Picture"
        >

        <div class="profile-name">
            <?php echo htmlspecialchars($name); ?>
        </div>

        <div class="profile-info">
            <div class="info-row">
                <div class="info-label">Email:</div>
                <div class="info-value">
                    <?php echo htmlspecialchars($email); ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Username:</div>
                <div class="info-value">
                    <?php echo htmlspecialchars($username); ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Mobile:</div>
                <div class="info-value">
                    <?php echo htmlspecialchars($mobile); ?>
                </div>
            </div>

            <div class="info-row">
                <div class="info-label">Address:</div>
                <div class="info-value">
                    <?php echo nl2br(htmlspecialchars($address)); ?>
                </div>
            </div>
        </div>

        <div class="account-type">
            <?php
            if ($category == 1) {
                echo "🌾 Farmer Account";
            } else {
                echo "🛒 Buyer Account";
            }
            ?>
        </div>

        <div class="profile-buttons">

            <a href="Profile/changePassPage.php"
               class="profile-button password-button">
                Change Password
            </a>

            <a href="profileEdit.php"
               class="profile-button edit-button">
                Edit Profile
            </a>

            <a href="uploadProduct.php"
               class="profile-button upload-button">
                ⬆ Upload Product
            </a>

            <a href="farmerOrders.php"
               class="profile-button orders-button">
                ▤ Manage Orders
            </a>

            <a href="Login/logout.php"
               class="profile-button logout-button">
                LOG OUT
            </a>

        </div>
    </div>
</main>

</body>
</html>