<?php

namespace ShoppingCartBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\MongoDB\DocumentManager;
use ShoppingCartBundle\Document\Product;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * LoadProductData.php
 *
 * @author Gabriel G. Casuso <gabriel@gabrielgcasuso.com>
 */
class LoadProductData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var  DocumentManager
     */
    protected $dm;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $dm)
    {
        $this->dm = $dm;

        $this->generateProducts();

        $dm->flush();
    }

    protected function generateProducts()
    {

        $productsData[] = [
            "name" => "Ensalada de espinacas",
            "price" => 1.65,
            "category" => Product::CATEGORY_MAIN,
            "soldBy" => Product::SOLD_BY_WEIGHT
        ];

        $productsData[] = [
            "name" => "Tortellini alla carbonara",
            "price" => 1.30,
            "category" => Product::CATEGORY_MAIN,
            "soldBy" => Product::SOLD_BY_WEIGHT
        ];

        $productsData[] = [
            "name" => "Pollo al curry",
            "price" => 1.55,
            "category" => Product::CATEGORY_MAIN,
            "soldBy" => Product::SOLD_BY_WEIGHT
        ];

        $productsData[] = [
            "name" => "Arroz con verduras",
            "price" => 1.55,
            "category" => Product::CATEGORY_MAIN,
            "soldBy" => Product::SOLD_BY_WEIGHT
        ];

        $productsData[] = [
            "name" => "Pizza primavera",
            "price" => 2.00,
            "category" => Product::CATEGORY_MAIN,
            "soldBy" => Product::SOLD_BY_UNITS
        ];

        $productsData[] = [
            "name" => "Carne estofada",
            "price" => 2.15,
            "category" => Product::CATEGORY_MAIN,
            "soldBy" => Product::SOLD_BY_WEIGHT
        ];

        $productsData[] = [
            "name" => "Agua",
            "price" => 1.20,
            "category" => Product::CATEGORY_DRINK,
            "soldBy" => Product::SOLD_BY_UNITS
        ];

        $productsData[] = [
            "name" => "Zumo de naranja",
            "price" => 2.00,
            "category" => Product::CATEGORY_DRINK,
            "soldBy" => Product::SOLD_BY_UNITS
        ];

        $productsData[] = [
            "name" => "Manzana",
            "price" => 2.00,
            "category" => Product::CATEGORY_DESSERT,
            "soldBy" => Product::SOLD_BY_UNITS
        ];

        $productsData[] = [
            "name" => "Tarta de queso",
            "price" => 2.50,
            "category" => Product::CATEGORY_DESSERT,
            "soldBy" => Product::SOLD_BY_UNITS
        ];

        foreach ($productsData as $productData) {

            $product = new Product();
            $product->setName($productData["name"]);
            $product->setPrice($productData["price"]);
            $product->setCategory($productData["category"]);
            $product->setSoldBy($productData["soldBy"]);

            $this->dm->persist($product);

        }
    }

    public function getOrder()
    {
        return 1;
    }

}
