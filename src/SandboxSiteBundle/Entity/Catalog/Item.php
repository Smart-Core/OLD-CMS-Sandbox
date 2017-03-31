<?php

namespace SandboxSiteBundle\Entity\Catalog;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SmartCore\Module\Unicat\Model\ItemModel;

/**
 * @ORM\Entity(repositoryClass="SmartCore\Module\Unicat\Model\ItemRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\OrderBy({"position" = "ASC", "id" = "ASC"})
     */
    protected $taxons;

    /**
     * @var Taxon[]
     *
     * @ORM\ManyToMany(targetEntity="Taxon", inversedBy="itemsSingle", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="unicat_catalog_items_taxons_single_relations")
     * @ORM\OrderBy({"position" = "ASC", "id" = "ASC"})
     */
    protected $taxonsSingle;

    /**
     * ItemModel constructor.
     */
    public function __construct()
    {
        parent::__construct();                                                
    }                                                
}
