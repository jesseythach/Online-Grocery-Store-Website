<?php
include 'backstore/UserHandlingAPI.php';

session_start();
$sessionLoginHandler = &$_SERVER["login"];
if (isset($sessionLoginHandler)) {
    $sessionLoginHandler->checkToken();
} else {
    $_SESSION['login'] = new UserHandlingAPI();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!--<link rel="stylesheet" type="text/css" href="template.css" />
    <link rel="stylesheet" type="text/css" href="navbar.css" />-->
    <link rel="stylesheet" type="text/css" href="css/main.minified.css" />
    <script src="menu.js"></script>
    <script src="js/mobile-menu.js"></script>
    <script src="js/scroll-hiding.js"></script>
</head>

<body>

    <header id="head">
    </header>
    <div class="landing" id="about-us">
        <div class="fade-cover"></div>
        <h1>A real-life<br><span class="italic" style="font-family: 'Courier New'"> digital-only store</span><br> right next-door!</h1>
        <h2>Founded in 1898, we're proud to serve the Canadian people <br> with <span class="green">quality</span> foods and <span class="green">quality</span> service! </h2>
    </div>

    <footer id="foot">
        <a href="about-us.php">About us</a>
        <a href="legal-notice.php">Legal notice</a>
    </footer>
</body>

</html>