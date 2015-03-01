<?php

namespace SandboxSiteBundle\Entity\Blog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Module\Unicat\Model\AttributesGroupModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="unicat_blog_attributes_groups")
 */
class AttributesGroup extends AttributesGroupModel
{
}
