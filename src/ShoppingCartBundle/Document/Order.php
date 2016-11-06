<?php
/**
 * Order.php
 *
 * @author Gabriel G. Casuso <gabriel@gabrielgcasuso.com>
 */

namespace ShoppingCartBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="ShoppingCartBundle\Repository\OrderRepository")
 */
class Order
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\Date
     */
    protected $date;

    /**
     * @MongoDB\EmbedMany(targetDocument="OrderItem")
     */
    protected $orderItem = [];

    /**
     * @MongoDB\Float
     */
    protected $discount;

    /**
     * @MongoDB\Float
     */
    protected $subTotal;

    /**
     * @MongoDB\Float
     */
    protected $total;

    public function __construct()
    {
        $this->orderItem = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set date
     *
     * @param date $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return date $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Add orderItem
     *
     * @param \ShoppingCartBundle\Document\OrderItem $orderItem
     */
    public function addOrderItem(OrderItem $orderItem)
    {
        $this->orderItem[] = $orderItem;
    }

    /**
     * Remove orderItem
     *
     * @param \ShoppingCartBundle\Document\OrderItem $orderItem
     */
    public function removeOrderItem(OrderItem $orderItem)
    {
        $this->orderItem->removeElement($orderItem);
    }

    /**
     * Get orderItem
     *
     * @return OrderItem[] $orderItem
     */
    public function getOrderItem()
    {
        return $this->orderItem;
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
     * @param $productId
     * @return bool
     */
    public function existProduct($productId)
    {
        foreach ($this->orderItem as $item) {
            if ($item->getProduct()->getId() == $productId) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set discount
     *
     * @param float $discount
     * @return $this
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * Get discount
     *
     * @return float $discount
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set subTotal
     *
     * @param float $subTotal
     * @return $this
     */
    public function setSubTotal($subTotal)
    {
        $this->subTotal = $subTotal;
        return $this;
    }

    /**
     * Get subTotal
     *
     * @return float $subTotal
     */
    public function getSubTotal()
    {
        return $this->subTotal;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return $this
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Get total
     *
     * @return float $total
     */
    public function getTotal()
    {
        return $this->total;
    }
}
