<?php

namespace SandboxSiteBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Module\Unicat\Model\PropertiesGroupModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_properties_groups")
 */
class PropertiesGroup extends PropertiesGroupModel
{
}
