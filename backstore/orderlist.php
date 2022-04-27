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
$xmlOrders = simplexml_load_file("../orders.xml");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <script src='../backstore_menu.js'></script>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <!--<link rel="stylesheet" href="backtemplate.css">
    <link rel="stylesheet" type="text/css" href="../navbar.css"/>-->
    <link rel="stylesheet" type="text/css" href="../css/main.minified.css" />
    <script src="../js/scroll-hiding.js"></script>
    <script src="../js/mobile-menu.js"></script>

    <title>Backstore - Order list</title>

</head>

<body id="backstore">
    <header id="head">
    </header>

    <div class="content">
        <div class="section">

            <a class="editor-button fake-button" href="editorder.php?">Add order</a>
            <h2 class="separator">Order Management</h2>

            <div class="vertical-list">
                <?php
                foreach ($xmlOrders as $order) {

                    $order_id = $order->Order_Id;
                    $user_id = $order->User_Id;
                    $order_date = date("Y-m-d", (int)$order->Order_Date);

                    foreach (UserHandlingAPI::getUserList() as $userdata) {
                        if ($userdata['uid'] == $user_id) {
                            $user = $userdata['username'];
                            break;
                        }
                    }
                    if (!isset($user)) {
                        $user = "DELETED USER";
                    }
                ?>
                    <div class="backstore-order">
                        <p><?= $order_id ?></p>
                        <div class="separator"></div>
                        <p><?= $user ?></p>
                        <div class="separator"></div>
                        <p><?= $order_date ?></p>
                        <div class="separator"></div>
                        <div class="order-products">
                            <?php
                            $sum = 0;
                            foreach ($order->Item as $item) {
                                $sum += $item->Amount;
                            }
                            echo ("<p style=\"text-align:center;\">" . $sum . ' ITEM(S) </p>'); ?>
                            <div class="horizontal-list">
                                <?php
                                //find product
                                $counter = 0;
                                foreach ($order->Item as $item) {
                                    $counter++;
                                    if ($counter > 3) break;

                                    $item = Utilities::findItem((int)($item->Id), '..');
                                    if (!isset($item)) $image = "/Assets/removed.png";
                                    else $image = $item->Image_Link;

                                    echo ("<img src=\"$image\"/> ");
                                }
                                ?>
                            </div>
                        </div>
                        <div class="separator"></div>
                        <div class="vertical-list">
                            <a class="fake-button" href="editorder.php?id=<?= $order->Order_Id ?>">Edit</a>
                            <a class="fake-button" href="removeorder.php?id=<?= $order->Order_Id ?>">Remove</a>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <footer id="foot">
        <a href="../about-us.php">About us</a>
        <a href="../legal-notice.php">Legal notice</a>
    </footer>
</body>

</html>