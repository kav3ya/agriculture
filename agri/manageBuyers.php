<?php
session_start();
require "db.php";

if (
    !isset($_SESSION['admin_logged_in']) ||
    $_SESSION['admin_logged_in'] != true
) {
    header("Location: adminLogin.php");
    exit();
}

$db = isset($conn) ? $conn : $con;

$query = "SELECT * FROM buyer ORDER BY bid DESC";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Buyers</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
        }

        .header {
            background: #138a55;
            color: white;
            padding: 22px 35px;
        }

        .container {
            width: 94%;
            margin: 30px auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            overflow-x: auto;
        }

        h1 {
            color: #138a55;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 14px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #138a55;
            color: white;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }

        .back-btn {
            display: inline-block;
            background: #138a55;
            color: white;
            padding: 10px 18px;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>AgroCulture Admin Panel</h2>
    </div>

    <div class="container">

        <a href="adminDashboard.php" class="back-btn">
            Back to Dashboard
        </a>

        <h1>Manage Buyers</h1>

        <table>
            <tr>
                <th>Buyer ID</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Address</th>
            </tr>

            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['bid']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['bname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['busername']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['bemail']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['bmobile']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['baddress']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='6'>No buyers found.</td>";
                echo "</tr>";
            }
            ?>
        </table>

    </div>

</body>
</html>