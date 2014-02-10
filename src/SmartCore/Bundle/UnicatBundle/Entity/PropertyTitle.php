<?php

namespace SmartCore\Bundle\UnicatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="unicat_property_title")
 */
class PropertyTitle
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="item_id")
     */
    protected $item;

    /**
     * @ORM\Column(type="text")
     */
    protected $value;

    /**
     * Constructor.
     */
    public function __construct()
    {

    }
}
