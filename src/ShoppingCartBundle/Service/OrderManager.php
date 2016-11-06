<?php
/**
 * OrderManager.php
 *
 * @author Gabriel G. Casuso <gabriel@gabrielgcasuso.com>
 */

namespace ShoppingCartBundle\Service;


use Doctrine\ODM\MongoDB\DocumentManager;
use ShoppingCartBundle\Document\Order;
use ShoppingCartBundle\Document\Product;
use Symfony\Component\DependencyInjection\Container;

class OrderManager
{

    /**
     * OrderService constructor.
     * @param DocumentManager $dm
     * @param Container $container
     * @param OrderFactory $orderFactory
     */
    public function __construct(
        DocumentManager $dm,
        Container $container,
        OrderFactory $orderFactory
    ) {
        $this->dm = $dm;
        $this->container = $container;
        $this->orderFactory = $orderFactory;
    }

    /**
     * @return \ShoppingCartBundle\Document\Order
     */
    public function createOrder()
    {
        $order = $this->orderFactory->createNewOrder();
        $this->dm->persist($order);
        $this->dm->flush();

        return $order;
    }

    /**
     * @param Product $product
     * @param $quantity
     * @return \ShoppingCartBundle\Document\OrderItem
     */
    public function createOrderItem(Product $product, $quantity)
    {
        $orderItem = $this->orderFactory->createOrderItem($product, $quantity);

        return $orderItem;
    }

    /**
     * @param Order $order
     */
    public function updateDiscounts(Order $order)
    {
        $menuDiscounts = [];
        $menuDiscount = 0;
        $save3x2Discounts = 0;
        $mainProductsIndex = 0;
        $drinkProductsIndex = 0;
        $dessertProductsIndex = 0;

        foreach ($order->getOrderItem() as $orderItem) {
            $product = $orderItem->getProduct();

            if ($product->getSoldBy() == Product::SOLD_BY_UNITS) {
                $productId = $product->getId();

                if (!isset($productsListCount[$productId])) {
                    $productsListCount[$productId] = 0;
                }

                $productsListCount[$productId]++;

                if ($productsListCount[$productId] % 3 == 0) {
                    $save3x2Discounts += $orderItem->getPrice();
                }
            }

            switch ($product->getCategory()) {
                case Product::CATEGORY_MAIN:
                    if (!isset($menuDiscounts[$mainProductsIndex])) {
                        $menuDiscounts[$mainProductsIndex] = 0;
                    }

                    $menuDiscounts[$mainProductsIndex] += $orderItem->getPrice();
                    $mainProductsIndex++;
                    break;
                case Product::CATEGORY_DRINK:
                    if (!isset($menuDiscounts[$drinkProductsIndex])) {
                        $menuDiscounts[$drinkProductsIndex] = 0;
                    }
                    $menuDiscounts[$drinkProductsIndex] += $orderItem->getPrice();
                    $drinkProductsIndex++;
                    break;
                case Product::CATEGORY_DESSERT:
                    if (!isset($menuDiscounts[$dessertProductsIndex])) {
                        $menuDiscounts[$dessertProductsIndex] = 0;
                    }

                    $menuDiscounts[$dessertProductsIndex] += $orderItem->getPrice();
                    $dessertProductsIndex++;
                    break;
            }
        }

        if ($mainProductsIndex > 0 &&
            $drinkProductsIndex > 0 &&
            $dessertProductsIndex  > 0
        ) {
            $menus = min($mainProductsIndex, $drinkProductsIndex, $dessertProductsIndex);

            for ($index = 0; $index < $menus; $index++) {
                $menuDiscount += $menuDiscounts[$index] * 0.2;
            }
        }

        $order->setDiscount($menuDiscount + $save3x2Discounts);

    }

    /**
     * @param Order $order
     */
    public function updateOrderPrice(Order $order)
    {
        $subTotal = 0;
        foreach ($order->getOrderItem() as $orderItem) {
            $subTotal += $orderItem->getPrice();
        }

        $subTotal = number_format($subTotal, 2);
        $total = $subTotal - $order->getDiscount();

        $order->setSubTotal($subTotal);
        $order->setTotal($total);
    }

    /**
     * @param Order $order
     * @param $orderItemId
     * @return null|\ShoppingCartBundle\Document\OrderItem
     */
    public function findOrderItem(Order $order, $orderItemId)
    {
        foreach ($order->getOrderItem() as $item) {
            if ($item->getId() == $orderItemId) {
                return $item;
            }
        }

        return null;
    }
}
