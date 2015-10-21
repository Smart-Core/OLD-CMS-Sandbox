<?php

namespace SandboxSiteBundle\Entity\Blog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Module\Unicat\Model\ItemModel;

/**
 * @ORM\Entity(repositoryClass="SmartCore\Module\Unicat\Model\ItemRepository")
 * @ORM\Table(name="unicat_blog_items",
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
     * @ORM\JoinTable(name="unicat_blog_items_taxons_relations")
     */
    protected $taxons;

    /**
     * @var Taxon[]
     *
     * @ORM\ManyToMany(targetEntity="Taxon", inversedBy="itemsSingle", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="unicat_blog_items_taxons_relations_single")
     */
    protected $taxonsSingle;
}
