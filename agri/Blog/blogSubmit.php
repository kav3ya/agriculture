<?php

session_start();

require '../db.php';


/* =====================================================
   CHECK LOGIN
===================================================== */

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1)
{
    $_SESSION['message'] =
        "You need to first login to write a blog !!!";

    header("Location: ../Login/error.php");
    exit();
}


/* =====================================================
   ONLY FARMER CAN WRITE BLOG
===================================================== */

if (!isset($_SESSION['Category']) || $_SESSION['Category'] != 1)
{
    $_SESSION['message'] =
        "Only farmers can write blogs.";

    header("Location: ../Login/error.php");
    exit();
}


/* =====================================================
   CHECK POST
===================================================== */

if ($_SERVER['REQUEST_METHOD'] != "POST")
{
    header("Location: ../blogWrite.php");
    exit();
}


/* =====================================================
   GET FARMER DETAILS
===================================================== */

$fid = intval($_SESSION['id']);

$userName = dataFilter($_SESSION['Username']);

$title = dataFilter($_POST['blogTitle']);

$content = $_POST['blogContent'];


/* =====================================================
   VALIDATION
===================================================== */

if ($fid <= 0)
{
    $_SESSION['message'] =
        "Invalid farmer account.";

    header("Location: ../Login/error.php");
    exit();
}


if (empty($title) || empty(trim($content)))
{
    $_SESSION['message'] =
        "Please enter blog title and content.";

    header("Location: ../blogWrite.php");
    exit();
}


/* =====================================================
   INSERT BLOG
===================================================== */

$sql = "
    INSERT INTO blogdata
    (
        blogUser,
        fid,
        blogTitle,
        blogContent
    )
    VALUES
    (
        '$userName',
        '$fid',
        '$title',
        '$content'
    )
";


$result = mysqli_query($conn, $sql);


/* =====================================================
   CHECK INSERT
===================================================== */

if (!$result)
{
    $_SESSION['message'] =
        "Unable to publish blog: " .
        mysqli_error($conn);

    header("Location: ../Login/error.php");
    exit();
}


/* =====================================================
   SUCCESS
===================================================== */

$_SESSION['message'] =
    "Blog published successfully!";

header("Location: ../blogView.php");
exit();


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