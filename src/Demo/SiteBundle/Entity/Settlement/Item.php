<?php

namespace Demo\SiteBundle\Entity\Settlement;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\Unicat2Bundle\Model\ItemModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="u2_settlement_items",
 *      indexes={
 *          @ORM\Index(name="position__u2_settlement_items", columns={"position"}),
 *          @ORM\Index(name="type__u2_settlement_items", columns={"type"}),
 *      }
 * )
 */
class Item extends ItemModel
{

}
