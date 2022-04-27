<?php
require_once "./utilities.php";


class CartInfo
{
    private array $productList = [];
    private array $aisleListFromFile;
    private int $productCount = 0;
    private array $currentCart = [];
    private array $totalArr = [];

    public function __construct()
    {
        $this->fetchCart();
    }

    public function getTotalPriceAfterTax()
    {
        return $this->getTotalPriceBeforeTax() + $this->getQST() + $this->getGST();
    }


    public function getTotalPriceBeforeTax()
    {
        return $this->totalArr['total_price_before_tax'];
    }


    public function getGST()
    {
        return $this->totalArr['gst'];
    }

    public function getQST()
    {
        return $this->totalArr['qst'];
    }

    public function getElementTotalCost(int $id)
    {
        return $this->productList[$id]['totalCost'];
    }

    public function getElementCount($item_name): int
    {
        return $this->productList[$item_name]['count'];
    }

    public function getTotalAmount()
    {


        return $this->totalArr['total_amount'] ?? 0;
    }

    /**
     * @return array
     */
    public function getProductList(): array
    {
        return $this->productList;
    }

    public function addCart(int $id, int $amount, bool $overrideAmount = false)

    {
        if (!is_null(Utilities::findItem($id))) {


            if (isset($this->currentCart[$id])) {
                if ($overrideAmount) $this->currentCart[$id] = min($amount, Utilities::findItem($id)->Amount); else {
                    $this->currentCart[$id] = min(($this->currentCart[$id] + $amount), Utilities::findItem($id)->Amount);
                }


            } else {

                $this->currentCart[$id] = min($amount, Utilities::findItem($id)->Amount);
            }
            $this->fetchCart();
            return true;

        }

        return false;

    }


    public function removeCart($id): bool
    {
        $success = false;
        $id = intval($id);
        if (isset($this->productList[$id])) {
            $success = true;
            unset($this->currentCart[$id]);
        }
        $this->fetchCart();
        return $success;
    }

//todo fix cart stuff
    public function fetchCart()
    {
        $productCount = 0.00;
        $totalPriceBeforeTax = 0.00;
        $totalAmount = 0.00;
        $this->productList = [];
        if ($this->currentCart != []) {


            foreach ($this->currentCart as $cart_product_id => $amount) {

                $contentArr = Utilities::findItem($cart_product_id);

                if (!is_null($contentArr)) {
                    $contentArr = (array)$contentArr;

                    $productCount++;
                    $contentArr['Amount'] = (int)$amount;
                    $this->productList[$cart_product_id] = $contentArr;
                    $this->productList[$cart_product_id]['count'] = $productCount;
                    $this->productList[$cart_product_id]['totalCost'] = (float)number_format((float)$amount * (float)$contentArr['Price'], 2);
                    $totalPriceBeforeTax += $this->productList[$cart_product_id]['totalCost'];
                    $totalAmount += (int)$amount;

                }
            }


        }
        $totalGST = (float)$totalPriceBeforeTax * 0.05;

        $totalQST = (float)$totalPriceBeforeTax * 0.0975;
        $this->totalArr['total_price_before_tax'] = $totalPriceBeforeTax;
        $this->totalArr['gst'] = $totalGST;
        $this->totalArr['qst'] = $totalQST;
        $this->totalArr['total_amount'] = $totalAmount;
    }


}