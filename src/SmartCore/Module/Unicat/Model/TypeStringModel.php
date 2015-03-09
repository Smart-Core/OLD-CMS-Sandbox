<?php

namespace SmartCore\Module\Unicat\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * {@inheritDoc}
 */
abstract class TypeStringModel extends AbstractTypeModel
{
    /**
     * @ORM\Column(type="string")
     */
    protected $value;
}
