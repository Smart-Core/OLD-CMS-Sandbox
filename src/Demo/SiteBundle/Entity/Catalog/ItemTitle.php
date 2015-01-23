<?php

namespace Demo\SiteBundle\Entity\Catalog;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\UnicatBundle\Model\TypeStringModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog_items_title",
 *      indexes={
 *          @ORM\Index(columns={"value"})
 *      }
 * )
 */
class ItemTitle extends TypeStringModel
{
}
