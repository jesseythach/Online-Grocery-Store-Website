<?php

class Utilities
{
    static function findItem(int $id, string $prefix = '.'): bool|null|SimpleXMLElement
    {
        $products = self::getProducts($prefix);
        foreach ($products as $item) {
            if ($item->Id == $id) return $item;
        }
        return null;
    }

    static function getProducts(string $prefix = '.'): array
    {
        $products = array();
        $productsXmlElement = simplexml_load_file($prefix . "/products.xml");

        foreach ($productsXmlElement->Item as $item) {
            $products[] = $item;
        }

        return $products;
    }

    static function removeProduct(int $id, string $prefix = '..'): bool
    {
        $productsXmlElement = simplexml_load_file($prefix . "/products.xml");

        for ($i = 0; $i < count($productsXmlElement->Item); $i++) {
            if ($productsXmlElement->Item[$i]->Id == $id) {
                unset($productsXmlElement->Item[$i]);
                break;
            }
        }

        return $productsXmlElement->asXML($prefix . "/products.xml");
    }

    static function getAisles(string $prefix = '.'): array
    {
        $aisles = array();
        $productsXmlElement = simplexml_load_file($prefix . "/products.xml");

        foreach ($productsXmlElement->Aisle as $aisle) {
            $aisles[] = $aisle;
        }

        return $aisles;
    }

    static function removeOrder(int $id, string $prefix = '..'): bool
    {
        $ordersXmlElement = simplexml_load_file($prefix . "/orders.xml");

        for ($i = 0; $i < count($ordersXmlElement->Order); $i++) {
            if ($ordersXmlElement->Order[$i]->Order_Id == $id) {
                unset($ordersXmlElement->Order[$i]);
                break;
            }
        }

        return $ordersXmlElement->asXML($prefix . "/orders.xml");
    }

    static function getOrders(string $prefix = '.'): array
    {
        $orders = array();
        $ordersXmlElement = simplexml_load_file($prefix . "/orders.xml");

        foreach ($ordersXmlElement->Order as $order) {
            $orders[] = $order;
        }

        return $orders;
    }

    static function findOrder(int $id, string $prefix = '.'): bool|null|SimpleXMLElement
    {
        $orders = self::getOrders($prefix);
        foreach ($orders as $order) {
            if ($order->Order_Id == $id) return $order;
        }
        return null;
    }
}