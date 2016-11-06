<?php

namespace ShoppingCartBundle\Controller;

use ShoppingCartBundle\Service\OrderManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ODM\MongoDB\DocumentManager;

class ShoppingCartController extends Controller
{
    public function indexAction()
    {
        $dm = $this->getDocumentManager();

        $productList = $dm->getRepository("ShoppingCartBundle:Product")->findAll();
        $order = $this->getOrderManager()->createOrder();

        return $this->render(
            'ShoppingCartBundle:ShoppingCart:index.html.twig',
            [
                "productList" => $productList,
                "orderId" => $order->getId()
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
