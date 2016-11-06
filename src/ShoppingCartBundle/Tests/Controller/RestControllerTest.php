<?php
/**
 * RestControllerTest.php
 *
 * @author Gabriel G. Casuso <gabriel@gabrielgcasuso.com>
 */

namespace ShoppingCartBundle\Tests;

use Doctrine\ODM\MongoDB\DocumentManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use ShoppingCartBundle\Document\Order;
use ShoppingCartBundle\Document\OrderItem;
use ShoppingCartBundle\Document\Product;
use ShoppingCartBundle\Service\OrderManager;

class RestControllerTest extends WebTestCase
{

    public function testCreateNewOrder()
    {
        $order = $this->getOrderManager()->createOrder();
        $this->assertContainsOnlyInstancesOf(Order::class, [$order]);

        $this->getDocumentManager()->remove($order);
        $this->getDocumentManager()->flush();
    }

    public function testCreateNewOrderItem()
    {
        $fooProduct = new Product();
        $orderItem = $this->getOrderManager()->createOrderItem($fooProduct, 1);
        $this->assertContainsOnlyInstancesOf(OrderItem::class, [$orderItem]);

    }

    public function testAddOrderItem()
    {
        $order = $this->getOrderManager()->createOrder();
        $fooProduct = $this->createFooProduct();

        $client = $this->callAddOrderItem($order, $fooProduct);

        $this->assertTrue($client->getResponse()->isSuccessful());

        $response = json_decode($client->getResponse()->getContent());
        $responseOrder = json_decode($response->order);

        $this->assertEquals($order->getId(), $responseOrder->id);
        $this->assertEquals(1, count($responseOrder->order_item));
        $this->assertEquals($fooProduct->getId(), $responseOrder->order_item[0]->product->id);

        $this->getDocumentManager()->remove($order);
        $this->getDocumentManager()->remove($fooProduct);
        $this->getDocumentManager()->flush();
    }

    public function testRemoveOrderItem()
    {
        $order = $this->getOrderManager()->createOrder();
        $fooProduct = $this->createFooProduct();

        $client = $this->callAddOrderItem($order, $fooProduct);

        $this->assertTrue($client->getResponse()->isSuccessful());

        $response = json_decode($client->getResponse()->getContent());
        $responseOrder = json_decode($response->order);

        $orderItemId = $responseOrder->order_item[0]->id;
        $client = $this->callRemoveOrderItem($order, $orderItemId);

        $this->assertTrue($client->getResponse()->isSuccessful());

        $response = json_decode($client->getResponse()->getContent());
        $responseOrder = json_decode($response->order);

        $this->assertEquals($order->getId(), $responseOrder->id);
        $this->assertEquals(0, count($responseOrder->order_item));

        $this->getDocumentManager()->remove($order);
        $this->getDocumentManager()->remove($fooProduct);
        $this->getDocumentManager()->flush();
    }

    public function testOrderItemPriceSoldByWeight()
    {
        $order = $this->getOrderManager()->createOrder();
        $fooProduct = $this->createFooProduct(Product::CATEGORY_MAIN, Product::SOLD_BY_WEIGHT, 1.50);

        //Add 150gr of $fooProduct to OrderItem
        $client = $this->callAddOrderItem($order, $fooProduct, 130);

        $this->assertTrue($client->getResponse()->isSuccessful());

        $response = json_decode($client->getResponse()->getContent());
        $responseOrder = json_decode($response->order);

        $this->assertEquals($order->getId(), $responseOrder->id);
        $this->assertEquals(1, count($responseOrder->order_item));
        $this->assertEquals($fooProduct->getId(), $responseOrder->order_item[0]->product->id);
        $this->assertEquals(130, $responseOrder->order_item[0]->quantity);
        $this->assertEquals(1.5, $responseOrder->order_item[0]->unity_price);
        $this->assertEquals(1.95, $responseOrder->order_item[0]->price);


    }

    public function test3x2Discount()
    {
        $order = $this->getOrderManager()->createOrder();
        $fooProduct = $this->createFooProduct();

        $client = $this->callAddOrderItem($order, $fooProduct);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $client = $this->callAddOrderItem($order, $fooProduct);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $client = $this->callAddOrderItem($order, $fooProduct);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $response = json_decode($client->getResponse()->getContent());
        $responseOrder = json_decode($response->order);

        $this->assertEquals($order->getId(), $responseOrder->id);
        $this->assertEquals(3, count($responseOrder->order_item));
        $this->assertEquals(6, $responseOrder->sub_total);
        $this->assertEquals(4, $responseOrder->total);
        $this->assertEquals(2, $responseOrder->discount);

        $this->getDocumentManager()->remove($order);
        $this->getDocumentManager()->remove($fooProduct);
        $this->getDocumentManager()->flush();
    }

    public function testMenuDiscount()
    {
        $order = $this->getOrderManager()->createOrder();
        $fooMainProduct = $this->createFooMainProduct();
        $fooDrinkProduct = $this->createFooDrinkProduct();
        $fooDessertProduct = $this->createFooDessertProduct();

        $client = $this->callAddOrderItem($order, $fooMainProduct);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $client = $this->callAddOrderItem($order, $fooDrinkProduct);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $client = $this->callAddOrderItem($order, $fooDessertProduct);
        $this->assertTrue($client->getResponse()->isSuccessful());

        $response = json_decode($client->getResponse()->getContent());
        $responseOrder = json_decode($response->order);

        $this->assertEquals($order->getId(), $responseOrder->id);
        $this->assertEquals(3, count($responseOrder->order_item));
        $this->assertEquals(5, $responseOrder->sub_total);
        $this->assertEquals(4, $responseOrder->total);
        $this->assertEquals(1, $responseOrder->discount);

        $this->getDocumentManager()->remove($order);
        $this->getDocumentManager()->remove($fooMainProduct);
        $this->getDocumentManager()->remove($fooDrinkProduct);
        $this->getDocumentManager()->remove($fooDessertProduct);
        $this->getDocumentManager()->flush();
    }

    /**
     * @param string $category
     * @param string $soldBy
     * @param int $price
     * @return Product
     */
    protected function createFooProduct(
        $category = Product::CATEGORY_MAIN,
        $soldBy = Product::SOLD_BY_UNITS,
        $price = 2
    ) {
        $fooProduct = new Product();
        $fooProduct->setName("Foo_" . $category);
        $fooProduct->setCategory($category);
        $fooProduct->setSoldBy($soldBy);
        $fooProduct->setPrice($price);

        $this->getDocumentManager()->persist($fooProduct);
        $this->getDocumentManager()->flush();

        return $fooProduct;
    }

    /**
     * @return Product
     */
    protected function createFooMainProduct()
    {
        return $this->createFooProduct();
    }

    /**
     * @return Product
     */
    protected function createFooDrinkProduct()
    {
        return $this->createFooProduct(Product::CATEGORY_DRINK, Product::SOLD_BY_UNITS, 1);
    }

    /**
     * @return Product
     */
    protected function createFooDessertProduct()
    {
        return $this->createFooProduct(Product::CATEGORY_DESSERT, Product::SOLD_BY_UNITS, 2);
    }

    /**
     * @param Order $order
     * @param Product $fooProduct
     * @param float $quantity
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function callAddOrderItem(Order $order, Product $fooProduct, $quantity = 1.00)
    {
        $path = $this->getUrl('shopping_cart_add_product');
        $parameters = [
            "orderId" => $order->getId(),
            "productId" => $fooProduct->getId(),
            "quantity" => $quantity
        ];

        $client = static::makeClient();
        $client->request('POST', $path, $parameters);

        return $client;
    }

    /**
     * @param Order $order
     * @param $orderItemId
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function callRemoveOrderItem(Order $order, $orderItemId)
    {
        $path = $this->getUrl('shopping_cart_remove_product');
        $parameters = [
            "orderId" => $order->getId(),
            "orderItemId" => $orderItemId,
        ];

        $client = static::makeClient();
        $client->request('POST', $path, $parameters);

        return $client;
    }

    /**
     * @return OrderManager
     */
    protected function getOrderManager()
    {
        return $this->getContainer()->get('shopping_cart.manager.order');
    }

    /**
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->getContainer()->get('doctrine_mongodb.odm.document_manager');
    }
}