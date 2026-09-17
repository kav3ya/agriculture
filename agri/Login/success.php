<?php
session_start();

if (empty($_SESSION['message'])) {
    header('Location: ../index.php');
    exit;
}

$message = $_SESSION['message'];
$_SESSION['message'] = '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password changed | AgroCulture</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
            color: #17361e;
            background:
                linear-gradient(rgba(10, 42, 24, 0.64), rgba(10, 42, 24, 0.64)),
                url('../images/banner.jpg') center / cover fixed no-repeat;
        }

        .success-page {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 40px 20px;
        }

        .success-card {
            width: min(100%, 520px);
            padding: 48px 42px;
            text-align: center;
            background: rgba(255, 255, 255, 0.96);
            border-radius: 18px;
            box-shadow: 0 22px 60px rgba(0, 0, 0, 0.30);
        }

        .success-icon {
            width: 66px;
            height: 66px;
            margin: 0 auto 20px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            color: white;
            background: #2f7d32;
            font-size: 34px;
            font-weight: bold;
        }

        h1 {
            margin: 0;
            color: #216d2b;
            font-size: clamp(28px, 4vw, 38px);
            line-height: 1.2;
        }

        p {
            margin: 16px 0 30px;
            color: #425245;
            font-size: 18px;
            line-height: 1.55;
        }

        .home-button {
            display: inline-block;
            padding: 13px 32px;
            border-radius: 8px;
            color: white;
            background: #2f7d32;
            text-decoration: none;
            font-weight: 700;
            transition: 0.2s;
        }

        .home-button:hover {
            background: #1f6228;
            transform: translateY(-2px);
        }

        @media (max-width: 520px) {
            .success-card {
                padding: 38px 25px;
            }
        }
    </style>
</head>

<body>
    <main class="success-page">
        <section class="success-card">
            <div class="success-icon">&#10003;</div>

            <h1>Password Changed Successfully</h1>

            <p>
                <?php
                echo htmlspecialchars(strip_tags($message), ENT_QUOTES, 'UTF-8');
                ?>
            </p>

            <a class="home-button" href="../index.php">Go to Home</a>
        </section>
    </main>
</body>
</html>