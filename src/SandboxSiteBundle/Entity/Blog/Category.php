<?php

namespace SandboxSiteBundle\Entity\Blog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Module\Unicat\Model\CategoryModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="unicat_blog_categories",
 *      indexes={
 *          @ORM\Index(columns={"is_enabled"}),
 *          @ORM\Index(columns={"position"})
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"slug", "parent_id", "structure_id"}),
 *      }
 * )
 */
class Category extends CategoryModel
{
}
