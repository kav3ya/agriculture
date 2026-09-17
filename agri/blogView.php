<?php

session_start();
require 'db.php';

/* =====================================================
   CHECK LOGIN
===================================================== */

if (
    !isset($_SESSION['logged_in']) ||
    $_SESSION['logged_in'] != 1
) {
    $_SESSION['message'] = "You need to login first.";

    header("Location: Login/login.php");
    exit();
}

/* =====================================================
   CURRENT USER DETAILS
===================================================== */

$userid = intval($_SESSION['id']);

$username = isset($_SESSION['Username'])
    ? $_SESSION['Username']
    : "";

$category = isset($_SESSION['Category'])
    ? intval($_SESSION['Category'])
    : 0;

/*
    Category 1 = Farmer
    Category 0 = Buyer
*/

$isFarmer = ($category == 1);

/* =====================================================
   DATA FILTER
===================================================== */

function dataFilter($data)
{
    return htmlspecialchars(
        trim(stripslashes($data)),
        ENT_QUOTES,
        'UTF-8'
    );
}

/* =====================================================
   FORMAT COMMENT TIME
===================================================== */

function formatDate($date)
{
    if (empty($date)) {
        return "";
    }

    return date(
        'g:i a',
        strtotime($date)
    );
}

/* =====================================================
   POST PROCESSING
===================================================== */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (
        !isset($_POST['blogId']) ||
        intval($_POST['blogId']) <= 0
    ) {
        header("Location: blogView.php");
        exit();
    }

    $blogId = intval($_POST['blogId']);

    /* =================================================
       ADD COMMENT
    ================================================= */

    if (isset($_POST['submit_comment'])) {

        if (
            isset($_POST['comment']) &&
            trim($_POST['comment']) != ""
        ) {

            $comment = mysqli_real_escape_string(
                $conn,
                trim($_POST['comment'])
            );

            $commentUser = mysqli_real_escape_string(
                $conn,
                $_SESSION['Username']
            );

            if (
                isset($_SESSION['picName']) &&
                $_SESSION['picName'] != ""
            ) {
                $commentPic = mysqli_real_escape_string(
                    $conn,
                    $_SESSION['picName']
                );
            } else {
                $commentPic = "profile0.png";
            }

            $commentSql = "
                INSERT INTO blogfeedback
                (
                    blogId,
                    comment,
                    commentUser,
                    commentPic
                )
                VALUES
                (
                    '$blogId',
                    '$comment',
                    '$commentUser',
                    '$commentPic'
                )
            ";

            $commentResult = mysqli_query(
                $conn,
                $commentSql
            );

            if (!$commentResult) {
                $_SESSION['message'] =
                    "Unable to add comment.";
            }
        }

        header("Location: blogView.php");
        exit();
    }

    /* =================================================
       LIKE BLOG
    ================================================= */

    if (isset($_POST['like_blog'])) {

        $userId = intval($_SESSION['id']);

        $checkLikeSql = "
            SELECT *
            FROM likedata
            WHERE blogId = '$blogId'
            AND blogUserId = '$userId'
        ";

        $checkLikeResult = mysqli_query(
            $conn,
            $checkLikeSql
        );

        if (
            $checkLikeResult &&
            mysqli_num_rows($checkLikeResult) == 0
        ) {

            $insertLikeSql = "
                INSERT INTO likedata
                (
                    blogId,
                    blogUserId
                )
                VALUES
                (
                    '$blogId',
                    '$userId'
                )
            ";

            $insertLikeResult = mysqli_query(
                $conn,
                $insertLikeSql
            );

            if ($insertLikeResult) {

                $updateLikeSql = "
                    UPDATE blogdata
                    SET likes = likes + 1
                    WHERE blogId = '$blogId'
                ";

                mysqli_query(
                    $conn,
                    $updateLikeSql
                );
            }
        }

        $scroll = isset($_POST['scrollPosition'])
            ? intval($_POST['scrollPosition'])
            : 0;

        header(
            "Location: blogView.php?scroll=" . $scroll
        );

        exit();
    }
}

/* =====================================================
   GET ALL BLOGS
===================================================== */

$sql = "
    SELECT *
    FROM blogdata
    ORDER BY blogId DESC
";

$result = mysqli_query(
    $conn,
    $sql
);

if (!$result) {
    die(
        "Blog database error: " .
        mysqli_error($conn)
    );
}

/* =====================================================
   SCROLL POSITION
===================================================== */

$scrollPosition = 0;

if (isset($_GET['scroll'])) {
    $scrollPosition = intval($_GET['scroll']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>AgroCulture - Blogs</title>

    <link
        rel="stylesheet"
        href="bootstrap/css/bootstrap.min.css"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100%;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #eef6f0;
            color: #333;
        }

        /* =================================================
           MAIN BLOG PAGE
        ================================================= */

        .blog-page {
            width: 100%;
            min-height: 100vh;
            padding: 100px 20px 60px;
        }

        /* =================================================
           PAGE HEADER
        ================================================= */

        .blog-header {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto 30px;
            padding: 25px 30px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.10);

            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
        }

        .blog-header h1 {
            margin: 0;
            color: #238b45;
            font-size: 34px;
            font-weight: bold;
        }

        .blog-header p {
            margin: 7px 0 0;
            color: #777;
            font-size: 16px;
        }

        .write-blog-btn {
            display: inline-block;
            padding: 12px 22px;
            background: #238b45;
            color: white;
            text-decoration: none;
            border-radius: 7px;
            font-size: 16px;
            font-weight: bold;
            white-space: nowrap;
        }

        .write-blog-btn:hover {
            background: #176b34;
            color: white;
            text-decoration: none;
        }

        /* =================================================
           INFORMATION MESSAGE
        ================================================= */

        .farmer-message {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto 30px;
            padding: 16px 20px;
            background: #e8f5e9;
            border-left: 5px solid #238b45;
            border-radius: 8px;
            color: #256d36;
            font-size: 16px;
            text-align: center;
        }

        /* =================================================
           BLOG CONTAINER
        ================================================= */

        .blogs-container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
        }

        /* =================================================
           BLOG CARD
        ================================================= */

        .blog-card {
            background: white;
            border: 1px solid #e1e8e3;
            border-radius: 12px;
            padding: 28px;
            margin-bottom: 30px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.10);
        }

        .blog-title {
            margin: 0 0 18px;
            color: #238b45;
            font-size: 29px;
            font-weight: bold;
            line-height: 1.3;
        }

        .blog-content {
            color: #444;
            font-size: 17px;
            line-height: 1.8;
            margin-bottom: 20px;
            word-wrap: break-word;
        }

        .blog-content p {
            margin: 8px 0;
        }

        /* =================================================
           AUTHOR
        ================================================= */

        .blog-author {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-top: 18px;
            border-top: 1px solid #eeeeee;
        }

        .author-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #e8f5e9;

            display: flex;
            justify-content: center;
            align-items: center;

            font-size: 20px;
        }

        .author-name {
            color: #333;
            font-size: 16px;
            font-weight: bold;
        }

        .blog-date {
            color: #999;
            font-size: 13px;
            margin-top: 3px;
        }

        /* =================================================
           BLOG ACTIONS
        ================================================= */

        .blog-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid #eeeeee;
        }

        .like-form {
            margin: 0;
            padding: 0;
        }

        .like-btn {
            border: none;
            background: #238b45;
            color: white;
            padding: 10px 20px;
            border-radius: 7px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
        }

        .like-btn:hover {
            background: #176b34;
        }

        .like-btn:disabled {
            background: #6c9f7b;
            cursor: not-allowed;
        }

        .like-count,
        .comment-count {
            color: #555;
            font-size: 15px;
            font-weight: bold;
        }

        /* =================================================
           COMMENT FORM
        ================================================= */

        .comment-form {
            margin-top: 20px;
            padding: 20px;
            background: #f7faf8;
            border-radius: 9px;
            border: 1px solid #e2e9e4;
        }

        .comment-form textarea {
            width: 100%;
            min-height: 90px;
            padding: 12px 14px;
            border: 1px solid #d0d9d3;
            border-radius: 7px;
            font-size: 15px;
            resize: vertical;
            outline: none;
        }

        .comment-form textarea:focus {
            border-color: #238b45;
            box-shadow: 0 0 5px rgba(35, 139, 69, 0.20);
        }

        .comment-submit {
            margin-top: 10px;
            padding: 9px 20px;
            background: #238b45;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .comment-submit:hover {
            background: #176b34;
        }

        /* =================================================
           COMMENT CARD
        ================================================= */

        .comment-card {
            display: flex;
            gap: 14px;
            margin-top: 15px;
            padding: 15px;
            background: #f8faf9;
            border: 1px solid #e0e6e2;
            border-radius: 8px;
        }

        .comment-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #d9e8dc;
            flex-shrink: 0;
        }

        .comment-body {
            flex: 1;
            min-width: 0;
        }

        .comment-user {
            color: #238b45;
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .comment-text {
            color: #444;
            font-size: 15px;
            line-height: 1.5;
            word-break: break-word;
        }

        .comment-time {
            margin-top: 5px;
            color: #999;
            font-size: 12px;
        }

        /* =================================================
           NO BLOGS
        ================================================= */

        .no-blog {
            background: white;
            text-align: center;
            padding: 60px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.10);
        }

        .no-blog h3 {
            color: #555;
            margin-bottom: 12px;
        }

        .no-blog p {
            color: #888;
            margin-bottom: 25px;
        }

        /* =================================================
           MOBILE
        ================================================= */

        @media screen and (max-width: 768px) {

            .blog-page {
                padding: 95px 12px 40px;
            }

            .blog-header {
                flex-direction: column;
                align-items: stretch;
                padding: 20px;
            }

            .blog-header h1 {
                font-size: 28px;
            }

            .write-blog-btn {
                text-align: center;
            }

            .blog-card {
                padding: 20px;
            }

            .blog-title {
                font-size: 25px;
            }

            .blog-content {
                font-size: 16px;
            }

            .blog-actions {
                gap: 10px;
            }

        }

    </style>

</head>

<body>

<?php require 'menu.php'; ?>

<div class="blog-page">

    <!-- =================================================
         HEADER
    ================================================= -->

    <div class="blog-header">

        <div>

            <h1>
                AgroCulture Blogs
            </h1>

            <p>
                Share knowledge, ideas and experiences about agriculture.
            </p>

        </div>

        <?php if ($isFarmer) { ?>

            <a
                href="blogWrite.php"
                class="write-blog-btn"
            >
                ✎ &nbsp; Write a Blog
            </a>

        <?php } ?>

    </div>

    <!-- =================================================
         INFORMATION MESSAGE
    ================================================= -->

    <div class="farmer-message">

        🌾 Read and share useful agricultural knowledge
        with the AgroCulture community.

    </div>

    <!-- =================================================
         BLOGS CONTAINER
    ================================================= -->

    <div class="blogs-container">

        <?php if (mysqli_num_rows($result) > 0) { ?>

            <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                <?php

                $blogId = intval(
                    $row['blogId']
                );

                $blogTitle = !empty(
                    $row['blogTitle']
                )
                    ? $row['blogTitle']
                    : "Untitled Blog";

                $blogUser = !empty(
                    $row['blogUser']
                )
                    ? $row['blogUser']
                    : "Unknown User";

                $blogTime = !empty(
                    $row['blogTime']
                )
                    ? $row['blogTime']
                    : "";

                $likes = isset($row['likes'])
                    ? intval($row['likes'])
                    : 0;

                /* =================================================
                   GET COMMENTS
                ================================================= */

                $commentSql = "
                    SELECT *
                    FROM blogfeedback
                    WHERE blogId = '$blogId'
                    ORDER BY commentTime ASC
                ";

                $commentResult = mysqli_query(
                    $conn,
                    $commentSql
                );

                $numComment = 0;

                if ($commentResult) {
                    $numComment = mysqli_num_rows(
                        $commentResult
                    );
                }

                /* =================================================
                   CHECK CURRENT USER LIKE
                ================================================= */

                $userLiked = false;

                $likeCheckSql = "
                    SELECT *
                    FROM likedata
                    WHERE blogId = '$blogId'
                    AND blogUserId = '$userid'
                ";

                $likeCheckResult = mysqli_query(
                    $conn,
                    $likeCheckSql
                );

                if (
                    $likeCheckResult &&
                    mysqli_num_rows($likeCheckResult) > 0
                ) {
                    $userLiked = true;
                }

                ?>

                <!-- =================================================
                     BLOG CARD
                ================================================= -->

                <div class="blog-card">

                    <!-- BLOG TITLE -->

                    <h2 class="blog-title">

                        <?php
                        echo htmlspecialchars(
                            $blogTitle
                        );
                        ?>

                    </h2>

                    <!-- BLOG CONTENT -->

                    <div class="blog-content">

                        <?php

                        /*
                            Blog content may contain HTML
                            from CKEditor.
                        */

                        echo $row['blogContent'];

                        ?>

                    </div>

                    <!-- AUTHOR -->

                    <div class="blog-author">

                        <div class="author-icon">
                            👤
                        </div>

                        <div>

                            <div class="author-name">

                                <?php
                                echo htmlspecialchars(
                                    $blogUser
                                );
                                ?>

                            </div>

                            <div class="blog-date">

                                <?php
                                echo htmlspecialchars(
                                    $blogTime
                                );
                                ?>

                            </div>

                        </div>

                    </div>

                    <!-- LIKE AND COMMENT AREA -->

                    <div class="blog-actions">

                        <form
                            method="POST"
                            action="blogView.php"
                            class="like-form"
                        >

                            <input
                                type="hidden"
                                name="blogId"
                                value="<?php echo $blogId; ?>"
                            >

                            <input
                                type="hidden"
                                name="scrollPosition"
                                value="0"
                                class="scroll-position"
                            >

                            <?php if ($userLiked) { ?>

                                <button
                                    type="button"
                                    class="like-btn"
                                    disabled
                                >
                                    👍 Liked
                                </button>

                            <?php } else { ?>

                                <button
                                    type="submit"
                                    name="like_blog"
                                    class="like-btn"
                                >
                                    👍 Like
                                </button>

                            <?php } ?>

                        </form>

                        <span class="like-count">

                            👍
                            <?php echo $likes; ?>
                            Likes

                        </span>

                        <span class="comment-count">

                            💬
                            <?php echo $numComment; ?>
                            Comments

                        </span>

                    </div>

                    <!-- COMMENT FORM -->

                    <div class="comment-form">

                        <form
                            method="POST"
                            action="blogView.php"
                        >

                            <input
                                type="hidden"
                                name="blogId"
                                value="<?php echo $blogId; ?>"
                            >

                            <textarea
                                name="comment"
                                placeholder="Write your comment..."
                                required
                            ></textarea>

                            <button
                                type="submit"
                                name="submit_comment"
                                class="comment-submit"
                            >
                                Submit Comment
                            </button>

                        </form>

                    </div>

                    <!-- DISPLAY COMMENTS -->

                    <?php if (
                        $commentResult &&
                        mysqli_num_rows($commentResult) > 0
                    ) { ?>

                        <?php while (
                            $comment = mysqli_fetch_assoc(
                                $commentResult
                            )
                        ) { ?>

                            <?php

                            $commentPic = !empty(
                                $comment['commentPic']
                            )
                                ? $comment['commentPic']
                                : "profile0.png";

                            $commentImage =
                                "images/profileImages/" .
                                $commentPic;

                            ?>

                            <div class="comment-card">

                                <img
                                    src="<?php
                                    echo htmlspecialchars(
                                        $commentImage
                                    );
                                    ?>"
                                    class="comment-avatar"
                                    alt="Profile"
                                    onerror="this.src='images/profileImages/profile0.png';"
                                >

                                <div class="comment-body">

                                    <div class="comment-user">

                                        <?php
                                        echo htmlspecialchars(
                                            $comment['commentUser']
                                        );
                                        ?>

                                    </div>

                                    <div class="comment-text">

                                        <?php
                                        echo htmlspecialchars(
                                            $comment['comment']
                                        );
                                        ?>

                                    </div>

                                    <div class="comment-time">

                                        <?php
                                        echo formatDate(
                                            $comment['commentTime']
                                        );
                                        ?>

                                    </div>

                                </div>

                            </div>

                        <?php } ?>

                    <?php } ?>

                </div>

            <?php } ?>

        <?php } else { ?>

            <!-- =================================================
                 NO BLOGS
            ================================================= -->

            <div class="no-blog">

                <?php if ($isFarmer) { ?>

                    <h3>
                        No Blogs Available
                    </h3>

                    <p>
                        You have not written any blogs yet.
                    </p>

                    <a
                        href="blogWrite.php"
                        class="write-blog-btn"
                    >
                        ✎ Write Your First Blog
                    </a>

                <?php } else { ?>

                    <h3>
                        No Blogs Available
                    </h3>

                    <p>
                        Be the first person to share
                        an agricultural blog.
                    </p>

                <?php } ?>

            </div>

        <?php } ?>

    </div>

</div>

<!-- =====================================================
     SCROLL POSITION SCRIPT
===================================================== -->

<script>

document.querySelectorAll(
    'button[name="like_blog"]'
).forEach(function(button) {

    button.addEventListener(
        'click',
        function() {

            var form = this.closest('form');

            var hiddenInput = form.querySelector(
                '.scroll-position'
            );

            if (hiddenInput) {

                hiddenInput.value =
                    window.pageYOffset ||
                    document.documentElement.scrollTop ||
                    document.body.scrollTop ||
                    0;

            }

        }
    );

});

window.addEventListener(
    'load',
    function() {

        var position =
            <?php echo $scrollPosition; ?>;

        if (position > 0) {

            setTimeout(
                function() {

                    window.scrollTo(
                        0,
                        position
                    );

                },
                50
            );

        }

    }
);

</script>

</body>

</html>