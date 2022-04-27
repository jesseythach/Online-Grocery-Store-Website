<?php
require "../utilities.php";
require "./UserHandlingAPI.php";
session_start();
$sessionLoginHandler = null;
if (isset($_SERVER["login"])) {
    $sessionLoginHandler = &$_SESSION['login'];
    $sessionLoginHandler->checkToken();
} else {
    $_SESSION['login'] = new UserHandlingAPI();
    $sessionLoginHandler = &$_SESSION['login'];
}
if (!$_SESSION['login']->checkToken(true)) {
    echo ("<pre>401 UNAUTHORIZED</pre>");
    http_response_code(401);
    exit();
}
if (isset($_GET["id"])) {
    $order = Utilities::findOrder((int)$_GET["id"], '..');
    $isNew = $order == null;
} else $isNew = true;

if (isset($_GET["invalid"])) echo ('<script>alert("No product with that name exists! Please change the name.")</script>');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script src='../backstore_menu.js'></script>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="title">Order Editor</title>
    <!--<link rel="stylesheet" type="text/css" href="backtemplate.css"/>
    <link rel="stylesheet" type="text/css" href="../navbar.css"/>-->
    <link rel="stylesheet" type="text/css" href="../css/main.minified.css" />
    <script src="../js/mobile-menu.js"></script>
    <script src="../js/scroll-hiding.js"></script>
</head>

<body id="editor">
    <header id="head">
    </header>
    <div class="content">
        <div class="section">
            <h3>To delete products, check their box and click "Remove Selected Products"!</h3>
            <form id="ordereditorform" method="post" action="addOrder.php">
                <div class="vertical-list">
                    <input type="hidden" name="order_id" value="<?= $_GET['id'] ?? null ?>" />
                    <div class="horizontal-list" id="order-editor">
                        <?php
                        if (isset($order)) {
                            foreach ($order->Item as $item) {

                        ?>
                                <input type="hidden" name="items[]" value="<?= $item->Id ?>" />
                                <div class="order-product">
                                    <input type="checkbox" name="box[]" value="<?= $item->Id ?>" /> <br />
                                    <img class="order-product-img" width="100" src="<?= Utilities::findItem((int)$item->Id, '..')->Image_Link ?>">
                                    <p class="order-product-name"><?= Utilities::findItem((int)$item->Id, '..')->Name ?></p>
                                    <p>Amount: <input type="number" name="items[]" value="<?= $item->Amount ?>" min="1" required /></p>
                                </div>
                        <?php
                            }
                        }
                        ?>

                    </div>
                    <div>
                        <!--Submit buttons-->
                        <button type="submit" name="submit" <?= $isNew ? "disabled" : "" ?>>Submit</button>
                        <button type="submit" name="remove" <?= $isNew ? "disabled" : "" ?>>Remove Selected Products
                        </button>
                    </div>
                </div>
            </form>

            <hr class="extra-margin">

            <form class="extra-margin" id="ordereditoraddform" method="post" action="addorder.php">

                <div class="vertical-list">
                    <div class="field">
                        <label for="user_id"><b>Enter user ID to create new order:</b>
                        </label>
                        <input type="text" id="user_id" name="user_id" <?= !$isNew ? "disabled" : "required" ?> value="<?= !$isNew ? Utilities::findOrder($_GET['id'], "..")->User_Id : null ?>" />
                        <input type="hidden" name="order_id" value="<?= !$isNew ? $_GET['id'] : null ?>" />
                    </div>
                    <div class="field">
                        <label for="name"><b>Name:</b></label>
                        <input type="text" name="name" class="fontsize1" id="name" required />
                    </div>

                    <div class="field">
                        <label for="amount"><b>Amount:</b></label>
                        <input type="number" name="amount" min="1" class="fontsize1" id="amount" required />
                    </div>

                    <button type="submit" name="add">Add product
                        <!--Increase opacity when mouse over?-->
                        <!--Add product submit button-->
                    </button>
                </div>
            </form>
        </div>

    </div>
    <footer id="foot">
        <a href="../about-us.php">About us</a>
        <a href="../legal-notice.php">Legal notice</a>
    </footer>
</body>

</html>