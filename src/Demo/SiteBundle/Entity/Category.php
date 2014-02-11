<?php

namespace Demo\SiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\UnicatBundle\Model\CategoryModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="unicat_categories",
 *      indexes={
 *          @ORM\Index(name="is_enabled", columns={"is_enabled"}),
 *          @ORM\Index(name="position", columns={"position"})
 *      }
 * )
 */
class Category extends CategoryModel
{
}
