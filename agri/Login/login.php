<?php

session_start();

require '../db.php';


/* =========================================
   LOGIN PROCESS
========================================= */

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

    $user = dataFilter($_POST['uname']);
    $pass = $_POST['pass'];
    $category = dataFilter($_POST['category']);


    /* =====================================
       FARMER LOGIN
       Category = 1
    ===================================== */

    if ($category == 1)
    {

        $sql =
            "SELECT * FROM farmer WHERE fusername='$user'";

        $result = mysqli_query($conn, $sql);


        if (!$result)
        {
            die(
                "SQL Error: "
                . mysqli_error($conn)
            );
        }


        $num_rows = mysqli_num_rows($result);


        if ($num_rows == 0)
        {

            $_SESSION['message'] =
                "Invalid User Credentials!";

            header("location: error.php");

            exit();
        }


        $User = $result->fetch_assoc();


        if (
            password_verify(
                $pass,
                $User['fpassword']
            )
        )
        {

            $_SESSION['id'] =
                $User['fid'];

            $_SESSION['Hash'] =
                $User['fhash'];

            $_SESSION['Password'] =
                $User['fpassword'];

            $_SESSION['Email'] =
                $User['femail'];

            $_SESSION['Name'] =
                $User['fname'];

            $_SESSION['Username'] =
                $User['fusername'];

            $_SESSION['Mobile'] =
                $User['fmobile'];

            $_SESSION['Addr'] =
                $User['faddress'];

            $_SESSION['Active'] =
                $User['factive'];

            $_SESSION['picStatus'] =
                $User['picStatus'];

            $_SESSION['picExt'] =
                $User['picExt'];

            $_SESSION['logged_in'] =
                true;

            $_SESSION['Category'] =
                1;

            $_SESSION['Rating'] =
                0;


            /* Profile picture */

            if (
                $_SESSION['picStatus'] == 0
            )
            {

                $_SESSION['picId'] =
                    0;

                $_SESSION['picName'] =
                    "profile0.png";

            }
            else
            {

                $_SESSION['picId'] =
                    $_SESSION['id'];

                $_SESSION['picName'] =
                    "profile"
                    . $_SESSION['picId']
                    . "."
                    . $_SESSION['picExt'];

            }


            header(
                "location: profile.php"
            );

            exit();

        }
        else
        {

            $_SESSION['message'] =
                "Invalid User Credentials!";

            header(
                "location: error.php"
            );

            exit();
        }

    }


    /* =====================================
       BUYER LOGIN
       Category = 0
    ===================================== */

    else
    {

        $sql =
            "SELECT * FROM buyer WHERE busername='$user'";


        $result =
            mysqli_query(
                $conn,
                $sql
            );


        if (!$result)
        {
            die(
                "SQL Error: "
                . mysqli_error($conn)
            );
        }


        $num_rows =
            mysqli_num_rows($result);


        if ($num_rows == 0)
        {

            $_SESSION['message'] =
                "Invalid User Credentials!";

            header(
                "location: error.php"
            );

            exit();
        }


        $User =
            $result->fetch_assoc();


        if (
            password_verify(
                $pass,
                $User['bpassword']
            )
        )
        {

            $_SESSION['id'] =
                $User['bid'];

            $_SESSION['Hash'] =
                $User['bhash'];

            $_SESSION['Password'] =
                $User['bpassword'];

            $_SESSION['Email'] =
                $User['bemail'];

            $_SESSION['Name'] =
                $User['bname'];

            $_SESSION['Username'] =
                $User['busername'];

            $_SESSION['Mobile'] =
                $User['bmobile'];

            $_SESSION['Addr'] =
                $User['baddress'];

            $_SESSION['Active'] =
                $User['bactive'];

            $_SESSION['logged_in'] =
                true;

            $_SESSION['Category'] =
                0;

            $_SESSION['picStatus'] =
                $User['picStatus'];

            $_SESSION['picExt'] =
                $User['picExt'];


            /* Profile picture */

            if (
                $_SESSION['picStatus'] == 0
            )
            {

                $_SESSION['picId'] =
                    0;

                $_SESSION['picName'] =
                    "profile0.png";

            }
            else
            {

                $_SESSION['picId'] =
                    $_SESSION['id'];

                $_SESSION['picName'] =
                    "profile"
                    . $_SESSION['picId']
                    . "."
                    . $_SESSION['picExt'];

            }


            header(
                "location: profile.php"
            );

            exit();

        }
        else
        {

            $_SESSION['message'] =
                "Invalid User Credentials!";

            header(
                "location: error.php"
            );

            exit();

        }

    }

}


/* =========================================
   DATA FILTER
========================================= */

function dataFilter($data)
{

    $data = trim($data);

    $data = stripslashes($data);

    $data = htmlspecialchars($data);

    return $data;
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <title>
        AgroCulture - Login
    </title>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <link
        href="../bootstrap/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            padding: 0;

            font-family: Arial, sans-serif;

            min-height: 100vh;

            background-image:
                linear-gradient(
                    rgba(0,0,0,0.35),
                    rgba(0,0,0,0.35)
                ),
                url("../images/banner.jpg");

            background-size: cover;

            background-position: center;

            background-repeat: no-repeat;

            display: flex;

            justify-content: center;

            align-items: center;
        }


        .login-box {

            width: 400px;

            max-width: 90%;

            background:
                rgba(255,255,255,0.96);

            padding: 40px;

            border-radius: 8px;

            box-shadow:
                0 5px 20px
                rgba(0,0,0,0.25);
        }


        .login-title {

            text-align: center;

            color: #2E7D32;

            font-size: 32px;

            font-weight: bold;

            margin-bottom: 30px;
        }


        .form-group {

            margin-bottom: 20px;
        }


        .form-group label {

            display: block;

            margin-bottom: 7px;

            font-weight: bold;

            color: #333;
        }


        .form-group input,
        .form-group select {

            width: 100%;

            padding: 12px;

            border: 1px solid #ccc;

            border-radius: 4px;

            font-size: 16px;
        }


        .login-button {

            width: 100%;

            padding: 13px;

            background: #2E7D32;

            color: white;

            border: none;

            border-radius: 4px;

            font-size: 17px;

            cursor: pointer;
        }


        .login-button:hover {

            background: #256628;
        }


        .register-text {

            text-align: center;

            margin-top: 25px;

            color: #555;
        }


        .register-text a {

            color: #2E7D32;

            font-weight: bold;

            text-decoration: none;
        }


        .register-text a:hover {

            text-decoration: underline;
        }

    </style>

</head>


<body>


<div class="login-box">


    <h1 class="login-title">

        AgroCulture

    </h1>


    <form
        method="POST"
        action="login.php"
    >


        <!-- USERNAME -->

        <div class="form-group">

            <label>
                Username
            </label>

            <input
                type="text"
                name="uname"
                placeholder="Enter Username"
                required
            >

        </div>


        <!-- PASSWORD -->

        <div class="form-group">

            <label>
                Password
            </label>

            <input
                type="password"
                name="pass"
                placeholder="Enter Password"
                required
            >

        </div>


        <!-- CATEGORY -->

        <div class="form-group">

            <label>
                Login As
            </label>

            <select
                name="category"
                required
            >

                <option value="">
                    Select Category
                </option>

                <option value="0">
                    Buyer
                </option>

                <option value="1">
                    Farmer
                </option>

            </select>

        </div>


        <!-- LOGIN -->

        <input
            type="submit"
            value="LOGIN"
            class="login-button"
        >


    </form>


    <div class="register-text">

        Don't have an account?

        <a href="signUp.php">

            Register

        </a>

    </div>


</div>


</body>

</html>