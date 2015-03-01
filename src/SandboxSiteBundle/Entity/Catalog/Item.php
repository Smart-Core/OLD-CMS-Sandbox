<?php

namespace SandboxSiteBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Module\Unicat\Model\ItemModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="unicat_catalog_items",
 *      indexes={
 *          @ORM\Index(columns={"position"}),
 *      }
 * )
 */
class Item extends ItemModel
{
    /**
     * @var Category[]
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="items", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="unicat_catalog_items_categories_relations")
     */
    protected $categories;

    /**
     * @var Category[]
     *
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="itemsSingle", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="unicat_catalog_items_categories_relations_single")
     */
    protected $categoriesSingle;
}
