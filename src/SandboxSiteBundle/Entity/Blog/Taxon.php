<?php

namespace SandboxSiteBundle\Entity\Blog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Module\Unicat\Model\TaxonModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="unicat_blog_taxons",
 *      indexes={
 *          @ORM\Index(columns={"is_enabled"}),
 *          @ORM\Index(columns={"position"})
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"slug", "parent_id", "taxonomy_id"}),
 *      }
 * )
 */
class Taxon extends TaxonModel
{
}
