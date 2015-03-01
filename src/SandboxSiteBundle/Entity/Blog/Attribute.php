<?php

namespace SandboxSiteBundle\Entity\Blog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Module\Unicat\Model\AttributeModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="unicat_blog_catalog_attributes",
 *      indexes={
 *          @ORM\Index(columns={"is_enabled"}),
 *          @ORM\Index(columns={"show_in_admin"}),
 *          @ORM\Index(columns={"show_in_list"}),
 *          @ORM\Index(columns={"show_in_view"}),
 *          @ORM\Index(columns={"position"}),
 *      }
 * )
 */
class Attribute extends AttributeModel
{
}
