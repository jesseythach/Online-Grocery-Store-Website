<?php
include "CartInfo.php";
session_start();

if (isset($_POST['id']) && isset($_POST['amount']) && is_float((float)$_POST['amount'])) {
    $productID =intval( $_POST['id']);
    $product_amount =(float) ($_POST['amount']);
    $isAmountOverridden = false;
    if (isset($_POST['override_amount'])) {
        $isAmountOverridden = (bool)$_POST['override_amount'];
    }
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = new CartInfo();
    }

    // array_push($_SESSION['cart'], [$product_name => $product_amount]);


    if (floor($product_amount)===$product_amount) {
        $product_amount= intval($product_amount);
        $success = $_SESSION['cart']->addCart($productID, (int)$product_amount,$isAmountOverridden);
        header("content-type: application/json");
        if ($success) {
            $cartInfoGetter = $_SESSION['cart'];
            $cartInfoGetter->fetchCart();
            $productCost = $cartInfoGetter->getElementTotalCost($productID);
            $totalCartCost = $cartInfoGetter->getTotalPriceAfterTax();
            $totalGST = $cartInfoGetter->getGST();
            $totalQST = $cartInfoGetter->getQST();
            $totalAmount = $cartInfoGetter->getTotalAmount();
            echo json_encode(['success' => true, 'gst' => $totalGST, 'qst' => $totalQST, 'total_cart_cost' => $totalCartCost, 'product_cost' => $productCost, 'total_amount' => $totalAmount]);

        } else {
            echo json_encode(['success' => false]);
        }


    } else {
        echo json_encode(['success' => false]);
    }
}


