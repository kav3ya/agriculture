<?php
    session_start();
    require '../db.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $name = dataFilter($_POST['name']);
        $mobile = dataFilter($_POST['mobile']);
        $user = dataFilter($_POST['uname']);
        $email = dataFilter($_POST['email']);
        

        $_SESSION['Email'] = $email;
        $_SESSION['Name'] = $name;
        $_SESSION['Username'] = $user;
        $_SESSION['MobileNo'] = $mobile;
        
    }
    $id = $_SESSION['id'];

    $sql = "UPDATE farmer SET fname='$name', fusername='$user', fmobile='$mobile', femail='$email' WHERE fid='$id';";

    $result = mysqli_query($conn, $sql);
    if($result)
    {
        $_SESSION['Email'] = $email;
        $_SESSION['Name'] = $name;
        $_SESSION['Username'] = $user;
        $_SESSION['Mobile'] = $mobile;

        $_SESSION['message'] = "Profile Updated successfully !!!";

        header("Location: ../profileView.php");
        exit();
    }
    else
    {
    die(mysqli_error($conn));
    }

function dataFilter($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


?>
