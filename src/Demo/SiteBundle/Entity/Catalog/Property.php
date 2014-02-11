<?php

namespace Demo\SiteBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\UnicatBundle\Model\PropertyModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_properties",
 *      indexes={
 *          @ORM\Index(name="is_enabled", columns={"is_enabled"}),
 *          @ORM\Index(name="show_in_admin", columns={"show_in_admin"}),
 *          @ORM\Index(name="show_in_list", columns={"show_in_list"}),
 *          @ORM\Index(name="show_in_view", columns={"show_in_view"}),
 *          @ORM\Index(name="position", columns={"position"}),
 *      }
 * )
 */
class Property extends PropertyModel
{
}
