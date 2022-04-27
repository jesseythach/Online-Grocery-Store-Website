<?php
require '../utilities.php';
require "./UserHandlingAPI.php";
session_start();
$sessionLoginHandler = &$_SERVER["login"];
if (isset($sessionLoginHandler)) {
    $sessionLoginHandler->checkToken();
} else {
    $sessionLoginHandler = new UserHandlingAPI();
}

if (!$sessionLoginHandler->checkToken(true)) {
    echo ("<pre>401 UNAUTHORIZED</pre>");
    http_response_code(401);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <script src='../backstore_menu.js'></script>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <title>Back Store - Products</title>
    <!--<link rel="stylesheet" type="text/css" href="backtemplate.css"/>
    <link rel="stylesheet" type="text/css" href="../navbar.css"/>-->
    <link rel="stylesheet" type="text/css" href="../css/main.minified.css" />
    <script src="../js/scroll-hiding.js"></script>
    <script src="../js/mobile-menu.js"></script>
</head>

<body id="backstore">
    <header id="head">
    </header>

    <div class="content">
        <div class="section">
            <a class="editor-button fake-button" href="editproduct.php">Add product</a>
            <div class="vertical-list">

                <?php
                $items = Utilities::getProducts('..');

                usort($items, function ($a, $b) {
                    return strcmp($a->Name, $b->Name);
                });

                usort($items, function ($a, $b) {
                    return strcmp($a->Aisle, $b->Aisle);
                });

                $previous_aisle = "";

                foreach ($items as $item) {
                    if ($previous_aisle != (string)($item->Aisle)) {
                        echo "<h3>" . $item->Aisle . "</h3>";
                        $previous_aisle = (string)$item->Aisle;
                    }
                ?>
                    <div class="backstore-product">

                        <img class="image" src="..<?= $item->Image_Link ?>" width="100">
                        <div class="separator"></div>
                        <p><?= $item->Name ?></p>
                        <div class="separator"></div>
                        <p> <?= $item->Price ?>$</p>
                        <div class="separator"></div>
                        <p> <?= $item->Amount ?> in stock</p>
                        <div class="separator"></div>

                        <div class="vertical-list">
                            <a class="fake-button" href="editproduct.php?id=<?= $item->Id ?>">Edit</a>

                            <a class="fake-button" href="removeproduct.php?id=<?= $item->Id ?>&delete">Remove</a>
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