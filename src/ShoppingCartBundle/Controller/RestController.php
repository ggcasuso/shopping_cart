<?php
/**
 * RestController.php
 *
 * @author Gabriel G. Casuso <gabriel@gabrielgcasuso.com>
 */

namespace ShoppingCartBundle\Controller;

use Doctrine\ODM\MongoDB\DocumentManager;
use JMS\Serializer\SerializerBuilder;
use ShoppingCartBundle\Service\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RestController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $order = null;
        if ($request->isMethod('POST')) {

            $dm = $this->getDocumentManager();

            $orderId = $request->request->get("orderId");
            $productId = $request->request->get("productId");
            $quantity = $request->request->get("quantity");

            $order = $dm->getRepository("ShoppingCartBundle:Order")->findOneById($orderId);
            $product = $dm->getRepository("ShoppingCartBundle:Product")->findOneById($productId);

            $orderItem = $this->getOrderManager()->createOrderItem($product, $quantity);
            $order->addOrderItem($orderItem);

            $this->getOrderManager()->updateDiscounts($order);
            $this->getOrderManager()->updateOrderPrice($order);
            $dm->flush();

        }

        $serializer = $this->getSerializer();
        $orderData = $serializer->serialize($order, 'json');

        $responseData = [
            "order" => $orderData
        ];

        return new JsonResponse($responseData);
    }

    public function removeAction(Request $request)
    {
        $order = null;
        if ($request->isMethod('POST')) {

            $dm = $this->getDocumentManager();

            $orderId = $request->request->get("orderId");
            $orderItemId = $request->request->get("orderItemId");

            $order = $this->getDocumentManager()->getRepository("ShoppingCartBundle:Order")->findOneById($orderId);

            $orderItem = $this->getOrderManager()->findOrderItem($order, $orderItemId);
            $order->removeOrderItem($orderItem);

            $this->getOrderManager()->updateDiscounts($order);
            $this->getOrderManager()->updateOrderPrice($order);
            $dm->flush();

        }

        $serializer = $this->getSerializer();
        $orderData = $serializer->serialize($order, 'json');

        $responseData = [
            "order" => $orderData
        ];

        return new JsonResponse($responseData);
    }

    /**
     * @return DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->get('doctrine_mongodb.odm.document_manager');
    }

    /**
     * @return OrderManager
     */
    public function getOrderManager()
    {
        return $this->get('shopping_cart.manager.order');
    }

    /**
     * @return \JMS\Serializer\Serializer
     */
    public function getSerializer()
    {
        return SerializerBuilder::create()->build();
    }
}