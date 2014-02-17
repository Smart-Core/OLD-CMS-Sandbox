<?php

namespace Demo\SiteBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\UnicatBundle\Model\CategoryModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_categories",
 *      indexes={
 *          @ORM\Index(name="is_enabled", columns={"is_enabled"}),
 *          @ORM\Index(name="position",   columns={"position"})
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="slug_parent_structure", columns={"slug", "parent_id", "structure_id"}),
 *      }
 * )
 */
class Category extends CategoryModel
{
}
