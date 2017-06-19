<?php

namespace SandboxSiteBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Module\Unicat\Model\TaxonModel;

/**
 * @ORM\Entity(repositoryClass="SmartCore\Module\Unicat\Model\TaxonRepository")
 * @ORM\Table(name="unicat_catalog_taxons",
 *      indexes={
 *          @ORM\Index(columns={"is_enabled"}),
 *          @ORM\Index(columns={"position"})
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="slug_parent_taxonomy", columns={"slug", "parent_id", "taxonomy_id"}),
 *          @ORM\UniqueConstraint(name="title_parent_taxonomy", columns={"title", "parent_id", "taxonomy_id"}),
 *      }
 * )
 */
class Taxon extends TaxonModel
{
}
