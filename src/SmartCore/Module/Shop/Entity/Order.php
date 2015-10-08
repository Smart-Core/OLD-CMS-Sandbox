<?php

namespace SmartCore\Module\Shop\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="SmartCore\Module\Shop\Entity\OrderRepository")
 * @ORM\Table(name="shop_orders",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"payment_status"}),
 *          @ORM\Index(columns={"shipping_status"}),
 *          @ORM\Index(columns={"user_id"}),
 *      }
 * )
 */
class Order
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\ExpiresAt;
    use ColumnTrait\UpdatedAt;
    use ColumnTrait\UserId;

    const STATUS_PENDING    = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_SHIPPING   = 2;
    const STATUS_CANCELLED   = 3;
    const STATUS_FINISHED   = 4;

    const PAYMENT_STATUS_NEW       = 0;
    const PAYMENT_STATUS_ABORTED   = 1;
    const PAYMENT_STATUS_COMPLETED = 2;

    const SHIPPING_STATUS_ONHOLD     = 0;
    const SHIPPING_STATUS_READY      = 1; // Готов к отправке
    const SHIPPING_STATUS_CANCELLED  = 2;
    const SHIPPING_STATUS_RETURNED   = 3;
    const SHIPPING_STATUS_INPROGRESS = 4; // В процессе доставки
    const SHIPPING_STATUS_SHIPPED    = 5; // Доставлено

    /**
     * @var int
     *
     * @ORM\Column(type="float")
     */
    protected $amount;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"default":0})
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $payment_status;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $shipping_status;

    /**
     * @var OrderItem[]
     *
     * @ORM\OneToMany(targetEntity="OrderItem", mappedBy="order")
     * @ORM\OrderBy({"created_at" = "ASC"})
     */
    protected $order_items;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->amount       = 0;
        $this->created_at   = new \DateTime();
        $this->order_items  = new ArrayCollection();
        $this->status       = self::STATUS_PENDING;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return $this
     */
    public function addOrderItem(OrderItem $orderItem)
    {
        if (!$this->order_items->contains($orderItem)) {
            $orderItem->setOrder($this);
            $this->order_items->add($orderItem);
        }

        return $this;
    }

    /**
     * @return OrderItem[]|ArrayCollection
     */
    public function getOrderItems()
    {
        return $this->order_items;
    }

    /**
     * @param OrderItem[]|ArrayCollection $order_items
     *
     * @return $this
     */
    public function setOrderItems($order_items)
    {
        $this->order_items = $order_items;

        return $this;
    }

    /**
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->payment_status;
    }

    /**
     * @param string $payment_status
     *
     * @return $this
     */
    public function setPaymentStatus($payment_status)
    {
        $this->payment_status = $payment_status;

        return $this;
    }

    /**
     * @return string
     */
    public function getShippingStatus()
    {
        return $this->shipping_status;
    }

    /**
     * @param string $shipping_status
     *
     * @return $this
     */
    public function setShippingStatus($shipping_status)
    {
        $this->shipping_status = $shipping_status;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}
