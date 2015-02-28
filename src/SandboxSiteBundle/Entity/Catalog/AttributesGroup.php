<?php

namespace SandboxSiteBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Module\Unicat\Model\AttributesGroupModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_attributes_groups")
 */
class AttributesGroup extends AttributesGroupModel
{
}
