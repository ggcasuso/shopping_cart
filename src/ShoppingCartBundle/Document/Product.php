<?php
/**
 * Product.php
 *
 * @author Gabriel G. Casuso <gabriel@gabrielgcasuso.com>
 */

namespace ShoppingCartBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="ShoppingCartBundle\Repository\ProductRepository")
 */
class Product
{

    const CATEGORY_MAIN = "main";
    const CATEGORY_DRINK = "drink";
    const CATEGORY_DESSERT = "dessert";

    const SOLD_BY_UNITS = "units";
    const SOLD_BY_WEIGHT = "weight";

    const WEIGHT_UNITY_CONST = 100;

    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $name;

    /**
     * @MongoDB\Float
     */
    protected $price;

    /**
     * @MongoDB\String
     */
    protected $category;

    /**
     * @MongoDB\String
     */
    protected $soldBy;



    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get price
     *
     * @return float $price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get category
     *
     * @return string $category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set soldBy
     *
     * @param string $soldBy
     * @return $this
     */
    public function setSoldBy($soldBy)
    {
        $this->soldBy = $soldBy;
        return $this;
    }

    /**
     * Get soldBy
     *
     * @return string $soldBy
     */
    public function getSoldBy()
    {
        return $this->soldBy;
    }
}
