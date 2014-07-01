<?php

namespace Demo\SiteBundle\Entity\Settlement;

use Doctrine\ORM\Mapping as ORM;
use SmartCore\Bundle\Unicat2Bundle\Model\PropertiesGroupModel;

/**
 * @ORM\Entity
 * @ORM\Table(name="u2_settlement_properties_groups")
 */
class PropertiesGroup extends PropertiesGroupModel
{
}
