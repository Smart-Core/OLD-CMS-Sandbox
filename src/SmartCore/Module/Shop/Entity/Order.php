<?php

namespace SmartCore\Module\Shop\Entity;

use Doctrine\ORM\Mapping as ORM;
use Smart\CoreBundle\Doctrine\ColumnTrait;

/**
 * @ORM\Entity(repositoryClass="SmartCore\Module\Shop\Entity\OrderRepository")
 * @ORM\Table(name="shop_orders",
 *      indexes={
 *          @ORM\Index(columns={"created_at"}),
 *          @ORM\Index(columns={"user_id"})
 *      }
 * )
 */
class Order
{
    use ColumnTrait\Id;
    use ColumnTrait\CreatedAt;
    use ColumnTrait\UserId;

    const STATUS_NEW        = 0;
    const STATUS_SENDING    = 0;
    const STATUS_FINISHED   = 0;
    const STATUS_PROCESSING = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"default":0})
     */
    protected $status;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
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
