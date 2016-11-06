<?php
/**
 * OrderItem.php
 *
 * @author Gabriel G. Casuso <gabriel@gabrielgcasuso.com>
 */

namespace ShoppingCartBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\EmbeddedDocument
 */
class OrderItem
{

    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\ReferenceOne(targetDocument="Product")
     */
    protected $product;

    /**
     * @MongoDB\Int
     */
    protected $quantity;

    /**
     * @MongoDB\Float
     */
    protected $unityPrice;

    /**
     * @MongoDB\Float
     */
    protected $price;

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
     * Set product
     *
     * @param \ShoppingCartBundle\Document\Product $product
     * @return $this
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Get product
     *
     * @return \ShoppingCartBundle\Document\Product $product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set quantity
     *
     * @param int $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Get quantity
     *
     * @return int $quantity
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set unityPrice
     *
     * @param float $unityPrice
     * @return $this
     */
    public function setUnityPrice($unityPrice)
    {
        $this->unityPrice = $unityPrice;
        return $this;
    }

    /**
     * Get unityPrice
     *
     * @return float $unityPrice
     */
    public function getUnityPrice()
    {
        return $this->unityPrice;
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
}
