<?php

namespace ShoppingCartBundle\Controller;

use JMS\Serializer\SerializerBuilder;
use ShoppingCartBundle\Service\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ODM\MongoDB\DocumentManager;

class ShoppingCartController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newOrderAction()
    {
        $dm = $this->getDocumentManager();

        $productList = $dm->getRepository("ShoppingCartBundle:Product")->findAll();
        $order = $this->getOrderManager()->createOrder();

        return $this->render(
            'ShoppingCartBundle:ShoppingCart:new.html.twig',
            [
                "productList" => $productList,
                "orderId" => $order->getId()
            ]
        );
    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function orderAction($id)
    {
        $dm = $this->getDocumentManager();

        $order = $dm->getRepository("ShoppingCartBundle:Order")->findOneById($id);

        return $this->render(
            'ShoppingCartBundle:ShoppingCart:order.html.twig',
            [
                "order" => $order
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $dm = $this->getDocumentManager();

        $orderList = $dm->getRepository("ShoppingCartBundle:Order")->findAll();

        return $this->render(
            'ShoppingCartBundle:ShoppingCart:list.html.twig',
            [
                "orderList" => $orderList,
            ]
        );
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
}
