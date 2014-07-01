<?php

namespace Demo\SiteBundle\Entity\Catalog2;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\Unicat2Bundle\Model\ItemModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="catalog2_items",
 *      indexes={
 *          @ORM\Index(name="position", columns={"position"}),
 *          @ORM\Index(name="type", columns={"type"}),
 *      }
 * )
 */
class Item extends ItemModel
{

}
