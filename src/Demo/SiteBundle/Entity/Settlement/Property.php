<?php

namespace Demo\SiteBundle\Entity\Settlement;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\Unicat2Bundle\Model\PropertyModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="u2_settlement_properties",
 *      indexes={
 *          @ORM\Index(columns={"is_enabled"}),
 *          @ORM\Index(columns={"show_in_admin"}),
 *          @ORM\Index(columns={"show_in_list"}),
 *          @ORM\Index(columns={"show_in_view"}),
 *          @ORM\Index(columns={"position"}),
 *      }
 * )
 */
class Property extends PropertyModel
{
}
