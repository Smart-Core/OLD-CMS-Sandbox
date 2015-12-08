<?php

namespace SandboxSiteBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Module\Unicat\Model\ItemModel;

/**
 * @ORM\Entity(repositoryClass="SmartCore\Module\Unicat\Model\ItemRepository")
 * @ORM\Table(name="unicat_catalog_items",
 *      indexes={
 *          @ORM\Index(columns={"position"}),
 *      }
 * )
 */
class Item extends ItemModel
{
    /**
     * @var Taxon[]
     *
     * @ORM\ManyToMany(targetEntity="Taxon", inversedBy="items", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="unicat_catalog_items_taxons_relations")
     */
    protected $taxons;

    /**
     * @var Taxon[]
     *
     * @ORM\ManyToMany(targetEntity="Taxon", inversedBy="itemsSingle", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="unicat_catalog_items_taxons_single_relations")
     */
    protected $taxonsSingle;
}
