<?php
include 'backstore/UserHandlingAPI.php';
include 'utilities.php';

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

    <!--<div class="banner">
        <img src="https://i2.wp.com/www.additudemag.com/wp-content/uploads/2014/10/natural-adhd-treatment-add-food-rules-what-to-eat-avoid-healthy-foods-grocery-bag.jpg" id="bannerImage" />
    </div>-->
    <div class="landing">
        <div class="fade-cover"></div>
        <h1> <span class="green">Green Tree</span> Groceries</h1>
        <h2>Your <span class="italic">goto</span> place for groceries!</h2>
        <p style="transform:rotateX(180deg); font-size: 3rem; color:white;">^</p>
    </div>

    <div class="content">

        <!--<h3 class="separator">Featured Items</h3>-->
        <div class="section featured">
            <h3>Featured Items</h3>
            <div class="horizontal-list">
                <?php

                $currFeatured = array();
                $product_count = count(Utilities::getProducts());

                if ($product_count == 0) return;

                for ($i = 0; $i < 4; $i++) {
                    $tryingToBeFeatured = 0;

                    do {
                        $tryingToBeFeatured = (int)Utilities::getProducts()[rand(0, $product_count - 1)]->Id;
                    } while (in_array($tryingToBeFeatured, $currFeatured));

                    $currFeatured[$i] = $tryingToBeFeatured;
                ?>
                    <a class="featured-item shadow-button" href="product.php?id=<?= $tryingToBeFeatured ?>">
                        <img src="Assets/Items/<?= $tryingToBeFeatured ?>.png">
                    </a>
                <?php
                }
                ?>
            </div>
        </div>

        <div class="section aisles" id="aisles">
            <h3>Aisles</h3>
            <div class="horizontal-list">

                <?php foreach (Utilities::getAisles() as $aisle) { ?>
                    <a class="aisles-item shadow-button" id="<?= strtolower($aisle) ?>" style="background-image: url('Assets/Aisles/<?= $aisle ?>.png');" href="aisle.php?aisle_name=<?= $aisle ?>">
                        <!--<img src="Assets/Aisles/<?= $aisle ?>.png" class="aisles-caption" />-->
                        <div class="fade-cover"></div>
                        <h3 class="aisles-caption"><?= $aisle ?></h3>
                    </a>
                <?php
                }
                ?>
            </div>
        </div>
    </div>

    <footer id="foot">
        <a href="about-us.php">About us</a>
        <a href="legal-notice.php">Legal notice</a>
    </footer>
</body>

</html>