<?php
session_start();
require "../utilities.php";
require "./UserHandlingAPI.php";
$sessionLoginHandler = null;
if (isset($_SERVER["login"])) {
    $sessionLoginHandler =& $_SESSION['login'];
    $sessionLoginHandler->checkToken();

} else {
    $_SESSION['login'] = new UserHandlingAPI();
    $sessionLoginHandler =& $_SESSION['login'];
}
if (!$_SESSION['login']->checkToken(true)) {
    echo("<pre>401 UNAUTHORIZED</pre>");
    http_response_code(401);
    exit();
}
$xmlElement = simplexml_load_file("../products.xml");

$isNew = $_POST['id'] == null;

$old_item = !$isNew ? Utilities::findItem((int)$_POST['id'], '..') : null; //editing an item, save old one to compare name and image

//Format entered name
$product_name = str_replace("_", " ", trim($_POST["name"]));
$formatted_name = "";

for ($i = 0; $i < strlen($product_name); $i++) {
    if ($i == 0 || substr($product_name, $i - 1, 1) == " ")
        $formatted_name = $formatted_name . strtoupper(substr($product_name, $i, 1));
    else
        $formatted_name = $formatted_name . substr($product_name, $i, 1);
}

$newId = 0;
$items = Utilities::getProducts('..');
foreach ($items as $item) {

    if ($isNew && $item->Id >= $newId) $newId = $item->Id + 1;

    if (trim($item->Name) == $formatted_name && ($isNew || trim($item->Name) != trim($old_item->Name))) { //found it
        unset($_FILES);
        header("location:editproduct.php?nonValid");
        exit();
    }
}

$product_price = $_POST["price"];
$product_amount = $_POST["amount"];
$product_big_desc = $_POST["desc1"];
$product_small_desc = $_POST["desc2"];
$aisle_name = $_POST["aisle"];

//New product

if ($isNew) {

    $product_image = $_FILES["fileToUpload"]["tmp_name"];
    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/Assets/Items/$newId.png");

    $item = $xmlElement->addChild('Item');
    $item->addChild('Id', $newId);
    $item->addChild('Name', $formatted_name);
    $item->addChild('Aisle', $aisle_name);
    $item->addChild('Big_Description', $product_big_desc);
    $item->addChild('Small_Description', $product_small_desc);
    $item->addChild('Price', $product_price);
    $item->addChild('Amount', $product_amount);
    $item->addChild('Image_Link', "/Assets/Items/$newId.png");

    $xmlElement->asXML("../products.xml");

    header("location:productlist.php?");
    exit();
}


//Editing existing product

$old_hash = md5(file_get_contents($_SERVER['DOCUMENT_ROOT'] . ($old_item->Image_Link)));
if ($_FILES["fileToUpload"]["tmp_name"] != null) //Editing product and uploading image
    $new_hash = md5(file_get_contents($_FILES["fileToUpload"]["tmp_name"]));
else
    $new_hash = null;

if ($new_hash != null && $old_hash != $new_hash) { //Uploading and the images aren't the same
    unlink($_SERVER['DOCUMENT_ROOT'] . "/Assets/Items/" . $old_item->Id . ".png");
    $item_image = $_FILES["fileToUpload"]["tmp_name"];

    move_uploaded_file($item_image, $_SERVER['DOCUMENT_ROOT'] . "/Assets/Items/$old_item->Id.png");
}

$item = $xmlElement->addChild('Item');
$item->addChild('Id', $old_item->Id);
$item->addChild('Name', $formatted_name);
$item->addChild('Aisle', $aisle_name);
$item->addChild('Big_Description', trim($product_big_desc));
$item->addChild('Small_Description', trim($product_small_desc));
$item->addChild('Price', $product_price);
$item->addChild('Amount', $product_amount);
$item->addChild('Image_Link', "/Assets/Items/$old_item->Id.png");

$xmlElement->asXML("../products.xml");

header("location:removeproduct.php?id=$old_item->Id");
exit();