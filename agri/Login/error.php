<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    AgroCulture - Login Error
</title>


<style>

* {

    margin: 0;

    padding: 0;

    box-sizing: border-box;

}


body {

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    background:
        #f7faf7;

    min-height: 100vh;

    display: flex;

    align-items: center;

    justify-content: center;

}


.error-box {

    width: 430px;

    background: white;

    padding: 45px 40px;

    text-align: center;

    border-radius: 18px;

    border: 1px solid #eeeeee;

    box-shadow:
        0 10px 35px
        rgba(0,0,0,0.10);

}


.error-icon {

    width: 70px;

    height: 70px;

    margin: auto;

    border-radius: 50%;

    background: #fff1f1;

    color: #d64545;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 35px;

    font-weight: bold;

}


h1 {

    margin-top: 20px;

    color: #263238;

    font-size: 28px;

}


.message {

    margin-top: 12px;

    color: #777;

    font-size: 16px;

}


.retry-button {

    display: inline-block;

    margin-top: 25px;

    padding: 12px 30px;

    background: #239447;

    color: white;

    text-decoration: none;

    border-radius: 8px;

    font-size: 16px;

    font-weight: bold;

    transition: 0.2s;

}


.retry-button:hover {

    background: #19743a;

}


.logo {

    position: absolute;

    top: 25px;

    left: 35px;

    color: #239447;

    font-size: 25px;

    font-weight: bold;

}

</style>

</head>


<body>


<div class="logo">

    AgroCulture

</div>


<div class="error-box">


    <div class="error-icon">

        !

    </div>


    <h1>

        Login Failed

    </h1>


    <p class="message">

        Invalid User Credentials!

        <br>

        Please check your username
        and password.

    </p>


    <a
        href="login.php"
        class="retry-button"
    >

        Try Again

    </a>


</div>


</body>

</html>