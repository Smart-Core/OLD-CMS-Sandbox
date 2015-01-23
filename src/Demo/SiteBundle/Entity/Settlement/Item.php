<?php

namespace Demo\SiteBundle\Entity\Settlement;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\Unicat2Bundle\Model\ItemModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="u2_settlement_items",
 *      indexes={
 *          @ORM\Index(columns={"position"}),
 *          @ORM\Index(columns={"type"}),
 *      }
 * )
 */
class Item extends ItemModel
{

}
