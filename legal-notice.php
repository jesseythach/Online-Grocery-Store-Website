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
    <div class="landing" id="legal-notice">
        <div class="fade-cover"></div>
        <h1>Cookies policy</h1>
        <h2>Introduction</h2>
        <p>Our website uses cookies. 🍪</br>
            Insofar as those cookies are not strictly necessary for the provision of <a href="https://green-tree-groceries.systems/">https://green-tree-groceries.systems/</a>, we won’t ask you to consent to our cookies.
        </p>
        <h2>Cookies that we use</h2>
        <p>We use cookies for <b>authentication and status</b> (cuz we love you ♥). We use cookies to identify you when you visit our website and as you navigate our website, and to help us determine if you are logged into our website. If you see a “401 UNAUTHORIZED”, please stop hacking us.
        </p>
        <h2>Managing cookies</h2>
        <p>Our cookies are UNSTOPPABLE 🤯
        </p>
        <h2>Our details</h2>
        <p>This website is owned and operated by students at Concordia University in Montreal, Canada with Microsoft Azure. Our principal place of business is in <a href="https://marvelcinematicuniverse.fandom.com/wiki/Knowhere">Knowhere</a>.
        </p>
    </div>

    <footer id="foot">
        <a href="about-us.php">About us</a>
        <a href="legal-notice.php">Legal notice</a>
    </footer>
</body>

</html>