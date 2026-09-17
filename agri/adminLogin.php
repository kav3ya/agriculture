<?php
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Admin login credentials
    $adminUsername = "admin";
    $adminPassword = "admin123";

    if ($username === $adminUsername && $password === $adminPassword) {

        $_SESSION["admin_logged_in"] = true;
        $_SESSION["admin_name"] = "Administrator";

        header("Location: adminDashboard.php");
        exit();

    } else {

        $message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Login | AgroCulture</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #087f5b, #20c997);
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 950px;
            min-height: 550px;
            display: flex;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.20);
        }

        .login-left {
            width: 50%;
            background: linear-gradient(135deg, #087f5b, #099268);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
        }

        .login-left .logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: white;
            color: #087f5b;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 45px;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .login-left h1 {
            font-size: 34px;
            margin-bottom: 15px;
        }

        .login-left p {
            font-size: 16px;
            line-height: 1.7;
            opacity: 0.95;
        }

        .login-right {
            width: 50%;
            padding: 55px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right h2 {
            color: #087f5b;
            font-size: 30px;
            margin-bottom: 10px;
        }

        .login-right .subtitle {
            color: #777;
            margin-bottom: 30px;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: bold;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        .form-group input:focus {
            border-color: #087f5b;
            box-shadow: 0 0 0 3px rgba(8, 127, 91, 0.12);
        }

        .login-button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #087f5b;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .login-button:hover {
            background: #065f46;
            transform: translateY(-2px);
        }

        .error-message {
            background: #ffe3e3;
            color: #c92a2a;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 14px;
        }

        .login-footer {
            margin-top: 25px;
            text-align: center;
            color: #777;
            font-size: 13px;
        }

        .login-footer a {
            color: #087f5b;
            text-decoration: none;
            font-weight: bold;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 450px;
            }

            .login-left,
            .login-right {
                width: 100%;
            }

            .login-left {
                min-height: 260px;
                padding: 30px;
            }

            .login-left .logo {
                width: 70px;
                height: 70px;
                font-size: 30px;
                margin-bottom: 15px;
            }

            .login-left h1 {
                font-size: 26px;
            }

            .login-right {
                padding: 35px 25px;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">

        <div class="login-left">

            <div class="logo">A</div>

            <h1>AgroCulture</h1>

            <p>
                Welcome to the AgroCulture<br>
                Administration Panel.
                <br><br>
                Manage farmers, buyers, products<br>
                and customer orders easily.
            </p>

        </div>

        <div class="login-right">

            <h2>Admin Login</h2>

            <p class="subtitle">
                Sign in to access your administrator account
            </p>

            <?php
            if ($message != "") {
                echo "<div class='error-message'>" . htmlspecialchars($message) . "</div>";
            }
            ?>

            <form method="POST" action="">

                <div class="form-group">
                    <label for="username">Username</label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Enter admin username"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter admin password"
                        required
                    >
                </div>

                <button type="submit" class="login-button">
                    Login to Dashboard
                </button>

            </form>

            <div class="login-footer">
                AgroCulture Admin Panel<br>
                <a href="index.php">Back to Home</a>
            </div>

        </div>

    </div>

</body>

</html>