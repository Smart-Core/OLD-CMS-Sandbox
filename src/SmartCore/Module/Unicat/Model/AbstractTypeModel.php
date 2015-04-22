<?php

namespace SmartCore\Module\Unicat\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * ORM\Entity()
 * ORM\Table(name="unicat_items_attributename",
 *      indexes={
 *          @ORM\Index(columns={"value"})
 *      }
 * )
 *
 * @todo переименовать в AbstractValueModel
 */
abstract class AbstractTypeModel
{
    /**
     * @var ItemModel
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Item")
     * ORM\JoinColumn(name="item_id")
     */
    protected $item;

    /**
     * @var user defined type
     *
     * ORM\Column(type="string")
     */
    //protected $value;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->item->getId();
    }

    /**
     * @param \SmartCore\Module\Unicat\Model\ItemModel $item
     *
     * @return $this
     */
    public function setItem(ItemModel $item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * @return \SmartCore\Module\Unicat\Model\ItemModel
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
