<?php

namespace Demo\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\UnicatBundle\Model\ItemModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="unicat_items")
 */
class Item extends ItemModel
{
    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="items", cascade={"persist"})
     * @ORM\JoinTable(name="unicat_items_categories")
     */
    protected $categories;
}
