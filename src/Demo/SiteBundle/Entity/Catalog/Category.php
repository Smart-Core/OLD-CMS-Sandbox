<?php

namespace Demo\SiteBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\UnicatBundle\Model\CategoryModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_categories",
 *      indexes={
 *          @ORM\Index(name="is_enabled_catalog_categories", columns={"is_enabled"}),
 *          @ORM\Index(name="position_catalog_categories",   columns={"position"})
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="slug_parent_structure__catalog_categories", columns={"slug", "parent_id", "structure_id"}),
 *      }
 * )
 */
class Category extends CategoryModel
{
}
