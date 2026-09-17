<?php
session_start();

require '../db.php';

/* =====================================================
   REGISTRATION PROCESS
===================================================== */

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = dataFilter($_POST['name']);
    $mobile = dataFilter($_POST['mobile']);
    $user = dataFilter($_POST['uname']);
    $email = dataFilter($_POST['email']);
    $pass = $_POST['pass'];
    $category = dataFilter($_POST['category']);
    $addr = dataFilter($_POST['addr']);

    /* Check mobile number */
    if (!preg_match('/^[0-9]{10}$/', $mobile))
    {
        $_SESSION['message'] = "Please enter a valid 10-digit mobile number!";
        header("location: error.php");
        exit();
    }

    /* Password hash */
    $passHash = password_hash($pass, PASSWORD_BCRYPT);

    /* Verification hash */
    $hash = md5(rand(0, 1000));

    /* Save session information */
    $_SESSION['Email'] = $email;
    $_SESSION['Name'] = $name;
    $_SESSION['Password'] = $passHash;
    $_SESSION['Username'] = $user;
    $_SESSION['Mobile'] = $mobile;
    $_SESSION['Category'] = $category;
    $_SESSION['Hash'] = $hash;
    $_SESSION['Addr'] = $addr;
    $_SESSION['Rating'] = 0;


    /* =================================================
       FARMER REGISTRATION
    ================================================= */

    if ($category == 1)
    {
        $sql = "SELECT * FROM farmer WHERE femail='$email'";
        $result = mysqli_query($conn, $sql);

        if (!$result)
        {
            die("SQL Error: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) > 0)
        {
            $_SESSION['message'] = "User with this email already exists!";
            header("location: error.php");
            exit();
        }

        $sql = "INSERT INTO farmer
                (fname, fusername, fpassword, fhash, fmobile, femail, faddress)
                VALUES
                ('$name', '$user', '$passHash', '$hash', '$mobile', '$email', '$addr')";

        if (mysqli_query($conn, $sql))
        {
            $_SESSION['Active'] = 0;
            $_SESSION['logged_in'] = true;

            $_SESSION['picStatus'] = 0;
            $_SESSION['picExt'] = "png";
            $_SESSION['picId'] = 0;
            $_SESSION['picName'] = "profile0.png";

            $sql = "SELECT * FROM farmer WHERE fusername='$user'";
            $result = mysqli_query($conn, $sql);
            $User = mysqli_fetch_assoc($result);

            $_SESSION['id'] = $User['fid'];

            $_SESSION['message'] =
                "Confirmation link has been sent to $email, please verify your account by clicking on the verification link sent to your email address.";

            header("location: profile.php");
            exit();
        }
        else
        {
            $_SESSION['message'] =
                "Registration failed! " . mysqli_error($conn);

            header("location: error.php");
            exit();
        }
    }


    /* =================================================
       BUYER REGISTRATION
    ================================================= */

    else
    {
        /* Check existing email */
        $sql = "SELECT * FROM buyer WHERE bemail='$email'";
        $result = mysqli_query($conn, $sql);

        if (!$result)
        {
            die("SQL Error: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) > 0)
        {
            $_SESSION['message'] =
                "User with this email already exists!";

            header("location: error.php");
            exit();
        }


        /* Check existing username */
        $sql = "SELECT * FROM buyer WHERE busername='$user'";
        $result = mysqli_query($conn, $sql);

        if (!$result)
        {
            die("SQL Error: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) > 0)
        {
            $_SESSION['message'] =
                "This username already exists!";

            header("location: error.php");
            exit();
        }


        /* Insert buyer */
        $sql = "INSERT INTO buyer
                (bname, busername, bpassword, bhash, bmobile, bemail, baddress)
                VALUES
                ('$name', '$user', '$passHash', '$hash', '$mobile', '$email', '$addr')";

        if (mysqli_query($conn, $sql))
        {
            $_SESSION['Active'] = 0;
            $_SESSION['logged_in'] = true;

            $_SESSION['picStatus'] = 0;
            $_SESSION['picExt'] = "png";
            $_SESSION['picId'] = 0;
            $_SESSION['picName'] = "profile0.png";

            $sql = "SELECT * FROM buyer WHERE busername='$user'";
            $result = mysqli_query($conn, $sql);

            if (!$result)
            {
                die("SQL Error: " . mysqli_error($conn));
            }

            $User = mysqli_fetch_assoc($result);

            $_SESSION['id'] = $User['bid'];

            $_SESSION['message'] =
                "Confirmation link has been sent to $email, please verify your account by clicking on the verification link sent to your email address.";

            header("location: profile.php");
            exit();
        }
        else
        {
            $_SESSION['message'] =
                "Registration not successful! " . mysqli_error($conn);

            header("location: error.php");
            exit();
        }
    }
}


/* =====================================================
   DATA FILTER
===================================================== */

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

    <title>AgroCulture - Register</title>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <link
        href="../bootstrap/css/bootstrap.min.css"
        rel="stylesheet">

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

            padding: 40px 0;
        }


        .register-box {

            width: 500px;

            max-width: 92%;

            background: rgba(255,255,255,0.96);

            padding: 35px 40px;

            border-radius: 8px;

            box-shadow:
                0 5px 20px
                rgba(0,0,0,0.25);
        }


        .register-title {

            text-align: center;

            color: #2E7D32;

            font-size: 32px;

            font-weight: bold;

            margin-bottom: 30px;
        }


        .form-group {

            margin-bottom: 18px;
        }


        .form-group label {

            display: block;

            margin-bottom: 6px;

            font-weight: bold;

            color: #333;
        }


        .form-group input,
        .form-group select {

            width: 100%;

            padding: 11px;

            border: 1px solid #ccc;

            border-radius: 4px;

            font-size: 16px;
        }


        .register-button {

            width: 100%;

            padding: 13px;

            background: #2E7D32;

            color: white;

            border: none;

            border-radius: 4px;

            font-size: 17px;

            cursor: pointer;
        }


        .register-button:hover {

            background: #256628;
        }


        .login-text {

            text-align: center;

            margin-top: 20px;

            color: #555;
        }


        .login-text a {

            color: #2E7D32;

            font-weight: bold;

            text-decoration: none;
        }


        .login-text a:hover {

            text-decoration: underline;
        }

    </style>

</head>


<body>


<div class="register-box">


    <h1 class="register-title">
        AgroCulture Registration
    </h1>


    <form method="POST"
          action="signUp.php">


        <div class="form-group">

            <label>Full Name</label>

            <input
                type="text"
                name="name"
                placeholder="Enter Full Name"
                required>

        </div>


        <div class="form-group">

            <label>Mobile Number</label>

            <input
                type="text"
                name="mobile"
                placeholder="Enter 10-digit Mobile Number"
                maxlength="10"
                required>

        </div>


        <div class="form-group">

            <label>Username</label>

            <input
                type="text"
                name="uname"
                placeholder="Enter Username"
                required>

        </div>


        <div class="form-group">

            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="Enter Email"
                required>

        </div>


        <div class="form-group">

            <label>Password</label>

            <input
                type="password"
                name="pass"
                placeholder="Enter Password"
                required>

        </div>


        <div class="form-group">

            <label>Address</label>

            <input
                type="text"
                name="addr"
                placeholder="Enter Address"
                required>

        </div>


        <div class="form-group">

            <label>Register As</label>

            <select name="category" required>

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


        <input
            type="submit"
            value="REGISTER"
            class="register-button">


    </form>


    <div class="login-text">

        Already have an account?

        <a href="login.php">
            Login
        </a>

    </div>


</div>


</body>

</html>