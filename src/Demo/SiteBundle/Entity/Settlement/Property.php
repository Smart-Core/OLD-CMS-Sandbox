<?php

namespace Demo\SiteBundle\Entity\Settlement;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\Unicat2Bundle\Model\PropertyModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="u2_settlement_properties",
 *      indexes={
 *          @ORM\Index(name="is_enabled__u2_settlement_properties", columns={"is_enabled"}),
 *          @ORM\Index(name="show_in_admin__u2_settlement_properties", columns={"show_in_admin"}),
 *          @ORM\Index(name="show_in_list__u2_settlement_properties", columns={"show_in_list"}),
 *          @ORM\Index(name="show_in_view__u2_settlement_properties", columns={"show_in_view"}),
 *          @ORM\Index(name="position__u2_settlement_properties", columns={"position"}),
 *      }
 * )
 */
class Property extends PropertyModel
{
}
