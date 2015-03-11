<?php

namespace SmartCore\Module\Unicat\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * {@inheritDoc}
 */
abstract class ValueBoolModel extends AbstractTypeModel
{
    /**
     * @ORM\Column(type="boolean")
     */
    protected $value;
}
