<?php

session_start();

require_once "db.php";


/* =====================================================
   DATABASE CONNECTION
===================================================== */

if (isset($conn)) {
    $database = $conn;
} elseif (isset($con)) {
    $database = $con;
} else {
    die("Database connection not found. Please check db.php");
}


/* =====================================================
   FETCH PRODUCTS AND FARMER NAMES
===================================================== */

$sql = "
    SELECT
        fproduct.pid,
        fproduct.fid,
        fproduct.product,
        fproduct.pcat,
        fproduct.pinfo,
        fproduct.price,
        fproduct.pimage,
        farmer.fname
    FROM fproduct
    LEFT JOIN farmer
        ON fproduct.fid = farmer.fid
    ORDER BY fproduct.pid DESC
";

$result = mysqli_query($database, $sql);

if (!$result) {
    die("Database query failed: " . mysqli_error($database));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Products | AgroCulture</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f1f5f9;
            font-family: Arial, sans-serif;
            color: #111827;
        }

        .container-box {
            width: 96%;
            margin: 30px auto;
            padding: 25px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        }

        .top-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }

        h1 {
            margin: 0;
            color: #168653;
            font-size: 30px;
            font-weight: bold;
        }

        .dashboard-btn {
            background: #168653;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 7px;
            font-size: 15px;
        }

        .dashboard-btn:hover {
            background: #106b42;
            color: white;
        }

        .message {
            padding: 14px;
            margin-bottom: 20px;
            background: #dcfce7;
            color: #166534;
            border-radius: 6px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 1150px;
            border-collapse: collapse;
        }

        th {
            padding: 16px 12px;
            background: #168653;
            color: white;
            text-align: center;
            font-size: 16px;
            vertical-align: middle;
        }

        td {
            padding: 16px 12px;
            text-align: center;
            vertical-align: middle;
            border-bottom: 1px solid #dddddd;
            font-size: 15px;
        }

        tr:hover {
            background: #f0fdf4;
        }

        .product-image {
            width: 110px;
            height: 90px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #dddddd;
        }

        .no-image {
            width: 110px;
            height: 90px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
            background: #e5e7eb;
            color: #555555;
            border-radius: 8px;
        }

        .description {
            max-width: 350px;
            text-align: left;
            line-height: 1.5;
        }

        .price {
            color: #168653;
            font-weight: bold;
            white-space: nowrap;
        }

        .delete-form {
            margin: 0;
            padding: 0;
        }

        .delete-btn {
            display: inline-block;
            padding: 10px 16px;
            background: #dc2626;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-size: 15px;
            line-height: 1.2;
        }

        .delete-btn:hover {
            background: #b91c1c;
        }

        .empty-message {
            padding: 25px;
            text-align: center;
            color: #666666;
        }

    </style>

</head>

<body>

    <div class="container-box">

        <div class="top-section">

            <h1>Manage Products</h1>

            <a
                href="adminDashboard.php"
                class="dashboard-btn"
            >
                Back to Dashboard
            </a>

        </div>


        <?php if (isset($_SESSION['message'])) { ?>

            <div class="message">

                <?php

                echo htmlspecialchars($_SESSION['message']);

                unset($_SESSION['message']);

                ?>

            </div>

        <?php } ?>


        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>Product ID</th>

                        <th>Farmer ID</th>

                        <th>Farmer Name</th>

                        <th>Product Image</th>

                        <th>Product Name</th>

                        <th>Category</th>

                        <th>Information</th>

                        <th>Price</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    if (mysqli_num_rows($result) > 0) {

                        while ($row = mysqli_fetch_assoc($result)) {

                            $productId = intval($row['pid']);

                            $farmerId = intval($row['fid']);

                            $farmerName = $row['fname'] ?? "";

                            $productName = $row['product'] ?? "";

                            $category = $row['pcat'] ?? "";

                            $information = $row['pinfo'] ?? "";

                            $price = $row['price'] ?? "";

                            $imageName = trim($row['pimage'] ?? "");

                            $imagePath = "images/productImages/" . $imageName;

                            ?>

                            <tr>

                                <td>
                                    <?php echo $productId; ?>
                                </td>

                                <td>
                                    <?php echo $farmerId; ?>
                                </td>

                                <td>
                                    <?php

                                    if (!empty($farmerName)) {
                                        echo htmlspecialchars($farmerName);
                                    } else {
                                        echo "Farmer name not found";
                                    }

                                    ?>
                                </td>

                                <td>

                                    <?php if (!empty($imageName)) { ?>

                                        <img
                                            src="<?php echo htmlspecialchars($imagePath); ?>"
                                            alt="<?php echo htmlspecialchars($productName); ?>"
                                            class="product-image"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                        >

                                        <div
                                            class="no-image"
                                            style="display:none;"
                                        >
                                            No Image
                                        </div>

                                    <?php } else { ?>

                                        <div class="no-image">
                                            No Image
                                        </div>

                                    <?php } ?>

                                </td>

                                <td>
                                    <?php echo htmlspecialchars($productName); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($category); ?>
                                </td>

                                <td class="description">
                                    <?php echo htmlspecialchars($information); ?>
                                </td>

                                <td class="price">
                                    ₹<?php echo htmlspecialchars($price); ?>
                                </td>

                                <td>

                                    <form
                                        action="deleteproduct.php"
                                        method="GET"
                                        class="delete-form"
                                        onsubmit="return confirm('Are you sure you want to delete this product?');"
                                    >

                                        <input
                                            type="hidden"
                                            name="pid"
                                            value="<?php echo $productId; ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="delete-btn"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                            <?php

                        }

                    } else {

                        ?>

                        <tr>

                            <td
                                colspan="9"
                                class="empty-message"
                            >
                                No products found.
                            </td>

                        </tr>

                        <?php

                    }

                    ?>

                </tbody>

            </table>

        </div>

    </div>

</body>

</html>