<?php
session_start();

if (
    !isset($_SESSION['admin_logged_in']) ||
    $_SESSION['admin_logged_in'] != true
) {
    header("Location: adminLogin.php");
    exit();
}

$adminName = $_SESSION['admin_name'] ?? "Administrator";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .header {
            background: #138a55;
            color: white;
            padding: 22px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            font-size: 30px;
        }

        .logout-btn {
            background: white;
            color: #138a55;
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 8px;
            font-weight: bold;
        }

        .logout-btn:hover {
            background: #e8f5ee;
        }

        .container {
            width: 92%;
            max-width: 1200px;
            margin: 35px auto;
        }

        .welcome-box {
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .welcome-box h2 {
            color: #138a55;
            margin-bottom: 15px;
            font-size: 30px;
        }

        .welcome-box p {
            font-size: 18px;
            color: #475569;
        }

        .card-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
        }

        .card {
            background: white;
            padding: 30px 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, 0.12);
        }

        .card .icon {
            font-size: 45px;
            margin-bottom: 15px;
        }

        .card h3 {
            color: #138a55;
            font-size: 22px;
            margin-bottom: 12px;
        }

        .card p {
            color: #64748b;
            min-height: 45px;
            line-height: 1.5;
            margin-bottom: 22px;
        }

        .open-btn {
            display: inline-block;
            background: #138a55;
            color: white;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 8px;
            font-weight: bold;
        }

        .open-btn:hover {
            background: #0d6f43;
        }

        .footer {
            text-align: center;
            color: #64748b;
            margin-top: 40px;
            padding: 20px;
        }

        @media (max-width: 1000px) {
            .card-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .header {
                padding: 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .container {
                width: 94%;
            }

            .welcome-box {
                padding: 25px;
            }

            .welcome-box h2 {
                font-size: 25px;
            }

            .card-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>AgroCulture Admin Panel</h1>

        <a href="adminLogout.php" class="logout-btn">
            Logout
        </a>
    </div>

    <div class="container">

        <div class="welcome-box">
            <h2>
                Welcome,
                <?php echo htmlspecialchars($adminName); ?>
            </h2>

            <p>
                You have successfully logged in to the AgroCulture
                administration panel.
            </p>
        </div>

        <div class="card-container">

            <div class="card">
                <div class="icon">👨‍🌾</div>

                <h3>Manage Farmers</h3>

                <p>
                    View and manage registered farmer accounts.
                </p>

                <a href="manageFarmers.php" class="open-btn">
                    Open
                </a>
            </div>

            <div class="card">
                <div class="icon">👥</div>

                <h3>Manage Buyers</h3>

                <p>
                    View and manage registered buyer accounts.
                </p>

                <a href="manageBuyers.php" class="open-btn">
                    Open
                </a>
            </div>

            <div class="card">
                <div class="icon">🌾</div>

                <h3>Manage Products</h3>

                <p>
                    View and manage products uploaded by farmers.
                </p>

                <a href="manageProducts.php" class="open-btn">
                    Open
                </a>
            </div>

            <div class="card">
                <div class="icon">📦</div>

                <h3>Manage Orders</h3>

                <p>
                    View and manage customer orders.
                </p>

                <a href="manageOrders.php" class="open-btn">
                    Open
                </a>
            </div>

        </div>

        <div class="footer">
            <p>© 2026 AgroCulture. Admin Panel</p>
        </div>

    </div>

</body>
</html>