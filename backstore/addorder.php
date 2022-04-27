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
if (!isset($_POST['checkout']) && !$_SESSION['login']->checkToken(true)) {
    echo("<pre>401 UNAUTHORIZED</pre>");
    http_response_code(401);
    exit();
}
$xmlElement = simplexml_load_file("../orders.xml");

if (isset($_POST['submit'])) { //Submit changes to order

    createOrder($_POST['items'], $_POST['order_id'], false, $xmlElement, null);

} else if (isset($_POST['remove'])) { //Remove selected items

    if (!isset($_POST['box'])) { //No items were selected
        header("location:orderlist.php");
        exit();
    }
    $ids = array(); //Array of all the item ids (including soon to be removed items)
    for ($i = 0; $i < count($_POST['items']); $i += 2) {
        $ids[$i / 2] = $_POST['items'][$i];
    }

    $newItems = array(); //Contains only the items we want; same format as original items array (Id, Amount, Id, Amount, etc. so 2 indexes per item)
    $index = 0;

    foreach ($ids as $id) {
        if (!in_array($id, $_POST['box'])) { //Checkbox array only contains the checked ones, and their value is the item's id (it's what I decided in editorder.php)
            //If id isn't in array, then it wasn't checked
            $newItems[$index] = $id; //Insert id
            $newItems[$index + 1] = $_POST['items'][1 + array_search($id, $_POST['items'])]; //Insert amount
            $index += 2;
        }
    }

    createOrder($newItems, $_POST['order_id'], false, $xmlElement, null);

} else if (isset($_POST['add'])) { //Adding a product to order

    $items = Utilities::getProducts('..');
    $nameFound = false;
    $id = null;
    foreach ($items as $item) { //Try to find given name in all the products
        if ($item->Name == $_POST['name']) {
            $id = $item->Id;
            $nameFound = true;
            break;
        }
    }
    if (!$nameFound) {
        header("location:editorder.php?invalid&id=" . $_POST['order_id']); //Redirect to order editor page with appropriate tag in url
        exit();

    }
    if (((int)$_POST['order_id']) <= 0) {
        $newItems = array();
        $newItems[] = $id;
        $newItems[] = $_POST['amount'];
        createOrder($newItems, null, true, $xmlElement, null);

    } else {
        $newItems = array(); //Contains all the order's items; same format as usual (Id, Amount, Id, Amount, etc. so 2 indexes per item)
        $order = Utilities::findOrder((int)$_POST['order_id'], '..');
        foreach ($order->Item as $item) {
            $newItems[] = $item->Id;
            if ($id == (int)$item->Id) { //Product already in order, so just increase amount :P
                $newItems[] = $item->Amount + $_POST['amount'];
                $foundId = true;
            } else {
                $newItems[] = $item->Amount;
            }
        }
        if (!isset($foundId)) { //If product isn't in order, then add it
            $newItems[] = $id;
            $newItems[] = $_POST['amount'];
        }

        createOrder($newItems, $_POST['order_id'], false, $xmlElement, null);
    }

} else if (isset($_POST['checkout'])) {

    createOrder(unserialize($_POST['items']), null, true, $xmlElement, '../cart.php?success&id=');

} else { //No button pressed
    header("location:orderlist.php");
    exit();
}

/**
 * Adds new order in given xml file, deletes old one if needed, redirects to orderlist.php and exits this page
 * @param array() $itemList Final list of items; format in Id, Amount, Id, Amount, etc. so 2 indexes per item
 * @param $order_id Id of the order (if it's a new order, this has to be a new and unique id)
 * @param bool $isNewOrder True if new order, false if not (delete old order)
 * @param SimpleXMLElement $xmlElement XMLElement of orders.xml
 * @return void
 */
function createOrder($itemList, $order_id, bool $isNewOrder, SimpleXMLElement $xmlElement, ?string $redirect)
{
    if ($isNewOrder) {
        $order_arr = Utilities::getOrders('..');
        usort($order_arr, function ($a, $b) {
            return $a->Order_Id - $b->Order_Id;
        });


        if (count($order_arr) == 0) $largest_id = 1;
        else {
            $largest_id = (int)$order_arr[count($order_arr) - 1]->Order_Id;
            $largest_id++;
        }
        $order = $xmlElement->addChild('Order');
        $order->addChild('Order_Id', $largest_id);
        $order->addChild('User_Id', $_POST['user_id']);
        $date = (new DateTime())->setTimezone(new DateTimeZone('-0400')); //Eastern time
        $order->addChild('Order_Date', strtotime($date->format('Y-m-d H:i:s')));
        for ($i = 0; $i < count($itemList); $i++) {
            if ($i % 2 == 0) {
                $current_item = $order->addChild('Item');
                $current_item->addChild('Id', $itemList[$i]);
            } else {
                $current_item->addChild('Amount', $itemList[$i]);
            }
        }

    } else if (!$isNewOrder) {
        $old_order = Utilities::findOrder((int)$order_id, '..');
        $order = $xmlElement->addChild('Order');
        $order->addChild('Order_Id', $order_id);
        $order->addChild('User_Id', $old_order->User_Id);
        $order->addChild('Order_Date', $old_order->Order_Date);

        $current_item = null;

        for ($i = 0; $i < count($itemList); $i++) {
            if ($i % 2 == 0) {
                $current_item = $order->addChild('Item');
                $current_item->addChild('Id', $itemList[$i]);
            } else {
                $current_item->addChild('Amount', $itemList[$i]);
            }
        }
    }
    $xmlElement->asXML("../orders.xml");
    if ($redirect == null) {
        if (!$isNewOrder) header("location:removeorder.php?id=" . $order_id);
        else header("location:orderlist.php");
    } else {
        header("location:" . $redirect . $largest_id);
    }

    exit();
}