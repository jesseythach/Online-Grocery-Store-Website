<?php
include "CartInfo.php";
session_start();


if (isset($_POST["id"])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = new CartInfo();
    }
    $cartInfo = $_SESSION['cart'];
    $success = $cartInfo->removeCart($_POST['id']);
    header("content-type: application/json");

    if ($success) {
        echo json_encode(['total_amount' => $cartInfo->getTotalAmount(), "gst" => $cartInfo->getGST(), "qst" => $cartInfo->getQST(), 'total_cart_cost' => $cartInfo->getTotalPriceAfterTax(), 'success' => true]);

    } else {
        echo json_encode(['success' => false]);
    }

} else {
    http_response_code(401);
}