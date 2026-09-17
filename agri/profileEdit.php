<?php
session_start();
?>

<!DOCTYPE HTML>
<html lang="en">

<head>

    <title>Profile: <?php echo $_SESSION['Username']; ?></title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <script src="bootstrap/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/style.css">


    <style>

        body {
            background: #f4f7f4;
            margin: 0;
            padding: 0;
        }


        .profile-page {

            width: 100%;

            max-width: 750px;

            margin: 40px auto;

            text-align: center;

        }


        /* Profile Picture */

        .profile-picture {

            width: 130px !important;

            height: 130px !important;

            border-radius: 50%;

            object-fit: cover;

            display: block;

            margin: 10px auto 15px auto;

            border: 2px solid #ddd;

        }


        .profile-name {

            color: #2E7D32;

            font-size: 28px;

            font-weight: bold;

            margin-bottom: 25px;

        }


        /* Upload Section */

        .upload-section {

            margin-bottom: 35px;

        }


        .upload-section input[type="file"] {

            display: inline-block;

            margin-bottom: 15px;

        }


        .upload-button {

            background: #2E7D32;

            color: white;

            border: none;

            padding: 10px 25px;

            margin: 5px;

            cursor: pointer;

            border-radius: 4px;

        }


        .upload-button:hover {

            background: #256628;

        }


        .remove-button {

            background: #777;

            color: white;

            border: none;

            padding: 10px 25px;

            margin: 5px;

            cursor: pointer;

            border-radius: 4px;

        }


        .remove-button:hover {

            background: #555;

        }


        /* Form */

        .form-fields {

            width: 100%;

            max-width: 550px;

            margin: 0 auto;

            text-align: left;

        }


        .form-fields label {

            display: block;

            font-weight: bold;

            margin-bottom: 6px;

            color: #333;

        }


        .form-fields input {

            width: 100%;

            padding: 12px;

            margin-bottom: 18px;

            border: 1px solid #ccc;

            border-radius: 4px;

            font-size: 16px;

            box-sizing: border-box;

        }


        .update-button {

            background: #2E7D32;

            color: white;

            border: none;

            padding: 12px 35px;

            font-size: 16px;

            cursor: pointer;

            border-radius: 4px;

            margin-top: 10px;

        }


        .update-button:hover {

            background: #256628;

        }


    </style>

</head>


<body>


<?php

require 'menu.php';

?>


<div
    class="profile-page"
    style="padding-top:50px !important;"
>


    <!-- Profile Picture -->

    <img
        src="<?php
            echo 'images/profileImages/' .
                 $_SESSION['picName'] .
                 '?' .
                 mt_rand();
        ?>"
        alt="Profile Picture"

        class="profile-picture"
    >


    <h2 class="profile-name">

        <?php
            echo $_SESSION['Name'];
        ?>

    </h2>


    <!-- Upload Profile Picture -->

    <div class="upload-section">


        <form
            method="post"
            action="Profile/updatePic.php"
            enctype="multipart/form-data"
        >


            <input
                type="file"
                name="profilePic"
                id="profilePic"
                accept=".jpg,.jpeg,.png"
            >


            <br>


            <input
                type="submit"
                name="upload"
                value="Upload"
                class="upload-button"
            >


            <input
                type="submit"
                name="remove"
                value="Remove"
                class="remove-button"
            >


        </form>


    </div>


    <!-- Profile Details -->

    <form
        method="post"
        action="Profile/updateProfile.php"
    >


        <div class="form-fields">


            <!-- Full Name -->

            <label>
                Full Name
            </label>


            <input
                type="text"
                name="name"
                value="<?php echo $_SESSION['Name']; ?>"
                placeholder="Full Name"
                required
            >


            <!-- Mobile Number -->

            <label>
                Mobile Number
            </label>


            <input
                type="text"
                name="mobile"
                value="<?php echo $_SESSION['Mobile']; ?>"
                placeholder="Mobile Number"
                required
            >


            <!-- Username -->

            <label>
                Username
            </label>


            <input
                type="text"
                name="uname"
                value="<?php echo $_SESSION['Username']; ?>"
                placeholder="Username"
                required
            >


            <!-- Email -->

            <label>
                Email
            </label>


            <input
                type="email"
                name="email"
                value="<?php echo $_SESSION['Email']; ?>"
                placeholder="Email"
                required
            >


        </div>


        <input
            type="submit"
            value="Update Profile"
            class="update-button"
        >


    </form>


</div>


</body>

</html>