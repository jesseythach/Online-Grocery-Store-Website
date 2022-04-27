<?php
require 'utilities.php';
require "CartInfo.php";

session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = new CartInfo();
}
$isLoggedIn = isset($_SESSION['user']);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <!--<link rel="stylesheet" type="text/css" href="template.css"/>
    <link rel="stylesheet" type="text/css" href="navbar.css"/>-->
    <link rel="stylesheet" type="text/css" href="css/main.minified.css"/>
    <link rel="stylesheet" type="text/css" href="css/animation.css"/>
    <script src="./cart.js"></script>
    <?php
    if (isset($_GET["success"])) {

        echo("<script type=\"text/javascript\">");
        echo("window.addEventListener('DOMContentLoaded', function removeAll() {");
        foreach ($_SESSION['cart']->getProductList() as $id => $productValue) {
            ?>
            removeFromCart(<?= $id ?>, "<?= ((array)$productValue)['Name'] ?>", <?= ((array)$productValue)['count'] ?>);
            <?php
        }
        echo("});</script>");
    } ?>

    <script src="menu.js"></script>
    <script src="js/scroll-hiding.js"></script>
    <script src="js/mobile-menu.js"></script>
</head>

<body>

<header id="head">
</header>

<?php
if (isset($_GET['success'])) {
    ?>
    <div class="success-background">
        <div style="transform: scale(1.5);" class="success-checkmark">
            <div class="check-icon">
                <span class="icon-line line-tip"></span>
                <span class="icon-line line-long"></span>
                <div class="icon-circle"></div>
                <div class="icon-fix"></div>
            </div>
        </div>
    </div>
    <?php
}
?>

<div class="content">
    <div class="section vertical-list">
        <h2>My Cart</h2>
        <?php

        $productList = [];

        $checkoutList = [];

        if (isset($_SESSION['cart']) && !isset($_GET['success'])) {
            foreach ($_SESSION['cart']->getProductList() as $id => $productValue) {
                $productValue = (array)$productValue;
                $checkoutList[] = $id;
                $checkoutList[] = $productValue['Amount'];

                $cart_product_name = $productValue['Name'];
                $price = number_format((float)$productValue['Price'], 2);
                $total_cost = number_format($productValue['totalCost'], 2); ?>

                <div class="cart" id='cartItem<?= $productValue['count'] ?>'>
                    <a class="cart-img" href="./product.php?id=<?= $id ?>">
                        <img src=".<?= $productValue['Image_Link'] ?>" width="100">
                    </a>

                    <span style="grid-row-start: 2;">
                            <span><?= $cart_product_name ?> - </span>
                            <span class="italic"><?= $price ?>$</span>
                        </span>

                    <div style="width: 80%;">
                        <div class="fieldcartinfo">
                            <label for="amountProduct<?= $productValue['count'] ?>">Amount:</label>
                            <input type="number" id="amountProduct<?= $productValue['count'] ?>"
                                   name="amountProduct<?= $productValue['count'] ?>" min="1"
                                   max="<?= Utilities::findItem($id)->Amount ?>" step='1'
                                   value="<?= $productValue['Amount'] ?>"
                                   oninput="updateCartProduct('<?= $id ?>',this, 'price<?= $productValue['count'] ?>','<?= $cart_product_name ?>',<?= $price ?>)">
                        </div>

                        <div class="fieldcartinfo extra-margin">
                            <label for="price<?= $productValue['count'] ?>">Total:</label>
                            <b id="price<?= $productValue['count'] ?>"><?= $total_cost ?>$</b>
                        </div>
                    </div>

                    <a class="actionbutton removeFromCart fake-button" style="grid-column-start: 2;"
                       onclick="removeFromCart('<?= $id ?>','<?= $cart_product_name ?>','cartItem<?= $productValue['count'] ?>')"
                       href="javascript:void(0);">Remove</a>
                </div>
                <?php
            }
        }
        ?>

        <h2>Order summary</h2>
        <div class="vertical-list">

            <?php
            if (isset($_GET['id'])) {
                echo("Order confirmed! Your order is #" . $_GET['id'] . '.');
            }
            ?>
            <div id="cart-items">
                <?php
                $totalPriceBeforeTaxes = 0;
                $totalAmount = $_SESSION['cart']->getTotalAmount();
                $gstTax = 0;
                $qstTax = 0;
                $finalTotalPrice = 0;
                foreach ($_SESSION['cart']->getProductList() as $cart_product_name => $productValues) {
                    $totalPriceBeforeTaxes += (float)$productValues['totalCost'];
                    ?>
                    <div style="display: grid; grid-template-columns: 50% 20% 30%"
                         id="summary_<?= $productValues['Name'] ?>">
                            <span class="summaryCol1" style=""><?= $productValues['Name'] ?>
                            </span>
                        <span class="summaryCol2">X<b
                                    id="price<?= $productValues['count'] ?>amount"><?= $productValues['Amount'] ?></b>
                            </span>
                        <span class="summaryCol3" style="text-align: right;"><b
                                    id="price<?= $productValues['count'] ?>summary"><?= number_format($productValues['totalCost'], 2) ?>
                                    $</b>
                            </span>

                    </div>
                    <?php
                }
                $gstTax = $totalPriceBeforeTaxes * 0.05;
                $qstTax = $totalPriceBeforeTaxes * 0.0975;
                $finalTotalPrice = $totalPriceBeforeTaxes + $qstTax + $gstTax;
                ?>

            </div>
            <hr style="width:30%;">
            <div class="summaryTable"
                 style="display: grid; grid-template-columns: 70% 30%; grid-template-rows: 40% 10% 40%" id="bill">
                <span class="summaryCol1">GST (5%)</span>
                <span class="summaryCol3" style="text-align: right;"><b id="gst"><?= number_format($gstTax, 2) ?>$</b>
                    </span>
                <span style="grid-row-start: 3" class="summaryCol1">QST (9.975%) </span>
                <span class="summaryCol3" style="text-align: right; grid-row-start: 3"><b
                            id="qst"><?= number_format($qstTax, 2) ?>$</b></span>
            </div>
            <hr style="width:30%;">
            <div class="summaryTable" id="bill">
                    <span id="bill-total" style="display: grid; grid-template-columns: 50% 20% 30%">
                        <span class="summaryCol1">Total
                        </span>
                        <span class="summaryCol2">X<b id="totalAmount"><?= $totalAmount ?></b>
                        </span>
                        <span class="summaryCol3" style="text-align: right"><b
                                    id="totalPrice"><?= number_format($finalTotalPrice, 2) ?>$</b>
                        </span>
                    </span>
            </div>
        </div>
        <form <?= $isLoggedIn ? "method=\"post\" action=\"backstore/addOrder.php\"" : "method=\"get\" action=\"login.php\"" ?>>
            <input type="hidden" name="items" value="<?= htmlentities(serialize($checkoutList)) ?>"/>
            <input type="hidden" name="user_id"
                   value="<?= isset($_SESSION['user']) ? $_SESSION['user']['id'] : null ?>">
            <button type="submit" name="checkout" class="fake-button-cart"
            <?php
            if ($isLoggedIn && ($totalAmount == 0 || isset($_GET['success']))) echo("disabled");
            echo(">");
            if (!$isLoggedIn) echo("Log in to checkout");
            else if ($totalAmount == 0 || isset($_GET['success'])) echo("Empty cart");
            else echo("Checkout"); ?>
            </button>
        </form>
    </div>
</div>
<footer id="foot">
    <a href="about-us.php">About us</a>
    <a href="legal-notice.php">Legal notice</a>
</footer>
</body>

</html>