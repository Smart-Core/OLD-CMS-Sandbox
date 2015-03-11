<?php

namespace SmartCore\Module\Unicat\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * {@inheritDoc}
 */
abstract class ValueSmallintModel extends AbstractTypeModel
{
    /**
     * @ORM\Column(type="smallint")
     */
    protected $value;
}
