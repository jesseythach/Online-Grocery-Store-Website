<?php
session_start();
require 'utilities.php';

$product = Utilities::findItem($_GET['id']);

$str = "";
if (!isset($product)) {
    exit("<h1 style='font-size: 5em;'>404 Product/aisle not found.</h1>");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product->Name ?></title>
    <!--<link rel="stylesheet" type="text/css" href="./template.css"/>
    <link rel="stylesheet" type="text/css" href="./navbar.css"/>-->
    <link rel="stylesheet" type="text/css" href="css/main.minified.css" />
    <script src="./productactions.js"></script>
    <script src="./menu.js"></script>
    <script src="js/mobile-menu.js"></script>
    <script src="js/scroll-hiding.js"></script>
</head>

<body>
    <header id="head">
    </header>

    <div class="content">
        <div class="section">
            <a class="actionbutton" href="./aisle.php?aisle_name=<?= $product->Aisle ?>">Back to
                <b><?= $product->Aisle ?></b>
            </a>
        </div>

        <div class="product-info section">

            <img src=".<?= $product->Image_Link ?>" class="product-img">
            <h2><?= str_replace("_", " ", $product->Name) ?></h2>
            <p><?= $product->Big_Description ?></p>
            <button onclick="showDesc()">Learn More</button>
            <div id="more-description" style="transform: scaleY(0);">
                <p><?= $product->Small_Description ?></p>
            </div>
        </div>

        <div class="product-buy section">
            <div class="product-cost">
                <h3><?= $product->Price ?>$ each</h3>
                <div>
                    <div> Amount: <input type="number" id="amountProduct" name="amountProduct" min="1" max="<?= $product->Amount ?>" value="1" oninput="updateProductPrice(<?= $_GET['id'] ?>, this, 'price', <?= $product->Price ?>)"></br>
                    </div>
                    <div id="product-price"> Total: <b id="price"><?= $product->Price ?>$</b></div>
                </div>
            </div>

            <button class="product-cart actionbutton" name="amountProduct" id="add-to-cart" onclick="addToCart(<?= $_GET['id'] ?>, document.getElementById('amountProduct').value) ">
                Add to cart
            </button>
        </div>
    </div>
</body>

</html>