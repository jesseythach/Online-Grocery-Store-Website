<?php
require "./utilities.php";
$aisle_name = $_GET["aisle_name"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $aisle_name ?></title>
    <!--<link rel="stylesheet" type="text/css" href="./template.css"/>
    <link rel="stylesheet" type="text/css" href="./navbar.css"/>-->
    <link rel="stylesheet" type="text/css" href="css/main.minified.css" />
    <script src="menu.js"></script>
    <script src="js/scroll-hiding.js"></script>
    <script src="js/mobile-menu.js"></script>
</head>

<body>
    <header id="head">
    </header>
    <div class="content">
        <div class="section">
            <a class="actionbutton" href="./">Back to
                Home
            </a>
        </div>
        <div class="products section little-margin">
            <h3><?= $aisle_name ?></h3>

            <div class="horizontal-list">

                <?php
                $items = Utilities::getProducts();
                usort($items, function ($a, $b) {
                    return strcmp($a->Name, $b->Name);
                });
                foreach ($items as $item) {
                    if ($item->Aisle == $aisle_name) {
                        $product_name = $item->Name; ?>
                        <a class="products-item shadow-button" href="./product.php?id=<?= $item->Id ?>">
                            <img src=" <?= $item->Image_Link ?> ">
                            <h4> <?= $product_name ?></h4>
                            <h4 class="italic"><?= $item->Price ?>$</h4>
                        </a>
                <?php
                    }
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