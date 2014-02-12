<?php

namespace Demo\SiteBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\UnicatBundle\Model\PropertyGroupModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_properties_groups" )
 */
class PropertyGroup extends PropertyGroupModel
{
}
