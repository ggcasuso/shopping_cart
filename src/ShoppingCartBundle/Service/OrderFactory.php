<?php
/**
 * OrderFactory.php
 *
 * @author Gabriel G. Casuso <gabriel@gabrielgcasuso.com>
 */

namespace ShoppingCartBundle\Service;

use ShoppingCartBundle\Document\Order;
use ShoppingCartBundle\Document\OrderItem;
use ShoppingCartBundle\Document\Product;

class OrderFactory
{

    /**
     * @return Order
     */
    public function createNewOrder()
    {
        return new Order();
    }

    /**
     * @return OrderItem
     */
    public function createNewOrderItem()
    {
        return new OrderItem();
    }

    /**
     * @param Product $product
     * @param $quantity
     * @return OrderItem
     */
    public function createOrderItem(Product $product, $quantity)
    {

        $unityPrice = $product->getPrice();
        $orderItem = $this->createNewOrderItem();
        $orderItem->setProduct($product);
        $orderItem->setQuantity($quantity);
        $orderItem->setUnityPrice($unityPrice);

        $price = $this->calculateOrderItemPrice($quantity, $unityPrice, $product->getSoldBy());

        $orderItem->setPrice($price);

        return $orderItem;
    }

    public function updateOrderItem(OrderItem $orderItem, Product $product, $quantity)
    {
        $newQuantity = $orderItem->getQuantity() + $quantity;
        $unityPrice = $product->getPrice();
        $newPrice = $this->calculateOrderItemPrice($newQuantity, $unityPrice, $product->getSoldBy());

        $orderItem->setQuantity($newQuantity);
        $orderItem->setPrice($newPrice);
        $orderItem->setUnityPrice($unityPrice);

    }

    /**
     * @param $quantity
     * @param $unityPrice
     * @param $type
     * @return string
     */
    protected function calculateOrderItemPrice($quantity, $unityPrice, $type)
    {
        $units = $quantity;
        if ($type == Product::SOLD_BY_WEIGHT) {
            $units = $quantity / Product::WEIGHT_UNITY_CONST;
        }

        $price = $unityPrice * $units;

        return number_format($price, 2);
    }

}