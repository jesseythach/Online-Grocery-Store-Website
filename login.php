<?php
include 'backstore/UserHandlingAPI.php';
session_start();
$loginSuccess = null;
$sessionLoginHandler = &$_SESSION["login"];
if (isset($sessionLoginHandler)) {
    if ($sessionLoginHandler->checkToken(true)) {
        header("location:backstore/orderlist.php");
    } elseif ($sessionLoginHandler->checkToken()) {
        header("location:index.php");
    }
} else {
    $_SESSION['login'] = new UserHandlingAPI();
}


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    if (isset($email) && isset($password)) {

        $loginSuccess = $_SESSION['login']->login($email, $password);
        if ($loginSuccess !== false) {


            if ($loginSuccess[1]) {
                header("location:backstore/productlist.php");
            } else {
                header("location:index.php");
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<link rel="stylesheet" type="text/css" href="template.css"/>
    <link rel="stylesheet" type="text/css" href="navbar.css"/>-->
    <link rel="stylesheet" type="text/css" href="css/main.minified.css" />
    <script src="./menu.js"></script>
    <script src="./js/scroll-hiding.js"></script>
    <script src="js/mobile-menu.js"></script>

    <title>Login page</title>


</head>

<body id="editor">
    <header id="head">
    </header>
    <div class="content">

        <div class="section">

            <h2>Login</h2>

            <form id="editorform" method="post">

                <?php
                if ($loginSuccess === false) {
                    echo "<p class=\"error\">Invalid Username or Password</p>";
                }
                ?>
                <div class="vertical-list">
                    <div class="field">
                        <label for="email"><b>E-mail:</b></label>
                        <input type="text" name="email" value="" id="email" />
                    </div>
                    <div class="field">
                        <label for="password"><b>Password:</b></label></td>
                        <input type="password" name="password" id="password" />
                    </div>
                    <div>
                        <button type="submit" class="actionbutton" href="index.php">Login</button>

                        <a class="actionbutton fake-button" href="signup.php">Sign Up</a>
                        <!--<button class="save actionbutton" href="login.php">Forgot Password?</button>-->
                    </div>
                </div>
            </form>
        </div>
    </div>
    <footer id="foot">
        <a href="about-us.php">About us</a>
        <a href="legal-notice.php">Legal notice</a>
    </footer>
</body>

</html>