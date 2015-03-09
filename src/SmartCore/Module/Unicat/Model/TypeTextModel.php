<?php

namespace SmartCore\Module\Unicat\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * {@inheritDoc}
 */
abstract class TypeTextModel extends AbstractTypeModel
{
    /**
     * @ORM\Column(type="text")
     */
    protected $value;
}
