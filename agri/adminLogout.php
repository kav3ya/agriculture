<?php
session_start();

/* Remove admin session */
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Logout Successful</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .message-box {
            background-color: white;
            padding: 40px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.15);
            width: 90%;
            max-width: 500px;
        }

        .message-box h1 {
            color: #198754;
        }

        .message-box p {
            font-size: 18px;
            color: #555;
        }

        .login-button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 25px;
            background-color: #198754;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .login-button:hover {
            background-color: #146c43;
        }
    </style>
</head>

<body>

    <div class="message-box">

        <h1>Logout Successful!</h1>

        <p>Thank you for using the AgroCulture Admin Panel.</p>

        <p>You have been logged out safely.</p>

        <a class="login-button" href="adminLogin.php">
            Login Again
        </a>

    </div>

</body>

</html>