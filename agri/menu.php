<?php
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1) {
    $loginProfile = "My Profile: " . $_SESSION['Username'];
    $logo = "glyphicon glyphicon-user";

    if ($_SESSION['Category'] != 1) {
        $link = "Login/profile.php";
    } else {
        $link = "profileView.php";
    }
} else {
    $loginProfile = "Login";
    $link = "index.php";
    $logo = "glyphicon glyphicon-log-in";
}
?>

<header id="header" style="
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:75px;
    background:#202222;
    z-index:10000;
    color:white;
">

    <h1 style="
        position:absolute;
        left:40px;
        top:0;
        margin:0;
        line-height:75px;
        font-size:24px;
    ">
        <a href="index.php" style="color:white;text-decoration:none;">
            AgroCulture
        </a>
    </h1>

    <nav style="
        position:absolute;
        right:30px;
        top:0;
        height:75px;
    ">
        <ul style="
            list-style:none;
            margin:0;
            padding:0;
            height:75px;
            display:flex;
            align-items:center;
            gap:25px;
        ">

            <li>
                <a href="index.php" style="color:#cee8d8;text-decoration:none;">
                    <span class="glyphicon glyphicon-home"></span> Home
                </a>
            </li>

            <li>
                <a href="myCart.php" style="color:#cee8d8;text-decoration:none;">
                    <span class="glyphicon glyphicon-shopping-cart"></span> MyCart
                </a>
            </li>

            <li>
                <a href="myOrders.php" style="color:#cee8d8;text-decoration:none;">
                    <span class="glyphicon glyphicon-list-alt"></span> My Orders
                </a>
            </li>

            <li>
                <a href="<?php echo $link; ?>" style="color:#cee8d8;text-decoration:none;">
                    <span class="<?php echo $logo; ?>"></span>
                    <?php echo " " . $loginProfile; ?>
                </a>
            </li>

            <li>
                <a href="market.php" style="color:#cee8d8;text-decoration:none;">
                    <span class="glyphicon glyphicon-grain"></span> Digital-Market
                </a>
            </li>

            <li>
                <a href="blogView.php" style="color:#cee8d8;text-decoration:none;">
                    <span class="glyphicon glyphicon-comment"></span> BLOG
                </a>
            </li>

        </ul>
    </nav>

</header>